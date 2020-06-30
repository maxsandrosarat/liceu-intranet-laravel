<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Produto;
use App\CompraProduto;
use App\ListaCompra;
use PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ListaCompraController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    public function index(){
        $prods = Produto::orderBy('nome')->get();
        return view('admin.lista_compra', compact('prods'));
    }

    public function store(Request $request){
        $prods = $request->input('produtos');
        if($request->input('produtos')==""){
            return redirect('/listaCompras');
        } else {
            $user = Auth::user()->name;
            $dataAtual = date("Y/m/d");
            $lista = new ListaCompra();
            $lista->data = $dataAtual;
            $lista->usuario = $user;
            $lista->save();
            $lista_id = DB::table('lista_compras')->max('id');
            foreach($prods as $prod){
                $cp = new CompraProduto();
                $cp->lista_compra_id = $lista_id;
                $cp->produto_id = $prod;
                $produto = Produto::find($prod);
                $cp->estoque = $produto->estoque;
                $cp->save();
            }
            $rels = CompraProduto::where('lista_compra_id',"$lista_id")->get();
            return view('admin.compras', compact('rels','lista_id'));
        }
    }

    public function gerarPdf($lista_id)
    {
        $lista = ListaCompra::find($lista_id);
        $produtos = CompraProduto::where('lista_compra_id',"$lista_id")->get();
        $pdf = PDF::loadView('admin.compras_pdf', compact('lista','produtos'));
        return $pdf->setPaper('a4')->stream('ListaCompra'.date("d-m-Y", strtotime($lista->data)).'.pdf');
    }
}
