<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Produto;
use App\Categoria;
use App\EntradaSaida;
use Illuminate\Support\Facades\Auth;
use PDF;

class ProdutoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    public function index()
    {
        $cats = Categoria::all();
        $prods = Produto::orderBy('nome')->paginate(10);
        return view('admin.produtos', compact('cats','prods'));
    }

    public function filtro(Request $request)
    {
        $nomeProd = $request->input('nomeProduto');
        $cat = $request->input('categoria');
        if(isset($nomeProd)){
            if(isset($cat)){
                $prods = Produto::where('nome','like',"%$nomeProd%")->where('categoria_id',"$cat")->orderBy('nome')->paginate(10);
            } else {
                $prods = Produto::where('nome','like',"%$nomeProd%")->orderBy('nome')->paginate(10);
            }
        } else {
            if(isset($cat)){
                $prods = Produto::where('categoria_id',"$cat")->orderBy('nome')->paginate(10);;
            } else {
                return redirect('produtos');
            }
        }
        $cats = Categoria::all();
        return view('admin.produtos', compact('cats','prods'));
    }

    public function store(Request $request)
    {
        $prod = new Produto();
        $prod->nome = $request->input('nomeProduto');
        $prod->estoque = $request->input('estoqueProduto');
        $prod->categoria_id = $request->input('categoriaProduto');
        $prod->save();
        return redirect('/produtos');
    }

    public function update(Request $request, $id)
    {
        $prod = Produto::find($id);
        if(isset($prod)){
            $prod->nome =$request->input('nomeProduto');
            $prod->categoria_id =$request->input('categoriaProduto');
            $prod->save();
        }
        return redirect('/produtos');
    }

    public function destroy($id)
    {
        $prod = Produto::find($id);
        if(isset($prod)){
            $prod->delete();
        }
        return redirect('/produtos');
    }

    public function gerarPdf(){
        $cats = Categoria::all();
        $prods = Produto::paginate(10);
        $pdf = PDF::loadView('admin.pdf_lista_compra', compact('cats','prods'));
        return $pdf->setPaper('a4')->stream('lista_produtos.pdf');
    }
}
