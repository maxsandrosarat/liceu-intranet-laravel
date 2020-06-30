<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Produto;
use App\Categoria;
use App\EntradaSaida;
use Illuminate\Support\Facades\Auth;
use PDF;

class EntradaSaidaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $rels = EntradaSaida::orderBy('created_at', 'desc')->paginate(10);
        $prods = Produto::orderBy('nome')->get();
        $busca = "nao";
        return view('admin.entrada_saida', compact('rels','prods','busca'));
    }

    public function filtro_entradaSaidaRel(Request $request)
    {
        $tipo = $request->input('tipo');
        $produto = $request->input('produto');
        $dataInicio = $request->input('dataInicio');
        $dataFim = $request->input('dataFim');
        if(isset($tipo)){
            if(isset($produto)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->where('produto_id',"$produto")->whereBetween('data',["$dataInicio", "$dataFim"])->paginate(10);
                    } else {
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->where('produto_id',"$produto")->whereBetween('data',["$dataInicio", date("Y/m/d")])->paginate(10);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->where('produto_id',"$produto")->whereBetween('data',["", "$dataFim"])->paginate(10);
                    } else {
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->where('produto_id',"$produto")->paginate(10);
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->whereBetween('data',["$dataInicio", "$dataFim"])->paginate(10);
                    } else {
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->whereBetween('data',["$dataInicio", date("Y/m/d")])->paginate(10);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->whereBetween('data',["", "$dataFim"])->paginate(10);
                    } else {
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->paginate(10);
                    }
                }
            }
        } else {
            if(isset($produto)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = EntradaSaida::where('produto_id',"$produto")->whereBetween('data',["$dataInicio", "$dataFim"])->paginate(10);
                    } else {
                        $rels = EntradaSaida::where('produto_id',"$produto")->whereBetween('data',["$dataInicio", date("Y/m/d")])->paginate(10);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = EntradaSaida::where('produto_id',"$produto")->whereBetween('data',["", "$dataFim"])->paginate(10);
                    } else {
                        $rels = EntradaSaida::where('produto_id',"$produto")->paginate(10);
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = EntradaSaida::whereBetween('data',["$dataInicio", "$dataFim"])->paginate(10);
                    } else {
                        $rels = EntradaSaida::whereBetween('data',["$dataInicio", date("Y/m/d")])->paginate(10);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = EntradaSaida::whereBetween('data',["", "$dataFim"])->paginate(10);
                    } else {
                        $rels = EntradaSaida::paginate(10);
                    }
                }
            }
        }
        $prods = Produto::orderBy('nome')->get();
        $busca = "sim";
        return view('admin.entrada_saida', compact('rels','prods','busca'));
    }
    
    public function store(Request $request)
    {
        $user = Auth::user();
        $tipo = $request->input('tipo');
        $id = $request->input('produto');
        $qtd = $request->input('qtd');
        $req = $request->input('req');
        $prod = Produto::find($id);
        $es = new EntradaSaida();
        $es->tipo = $tipo;
        $es->produto_id = $id;
        $es->produto_nome = $prod->nome;
        $es->quantidade = $qtd;
        $es->requisitante = $req;
        $es->usuario = $user->name;
        $es->data = date("Y/m/d");
        $es->save();
        if(isset($prod)){
            if($tipo=="entrada"){
                $prod->estoque += $qtd;
            }
            if($tipo=="saida"){
                $prod->estoque -= $qtd;
            }
            $prod->save();
        }
        return redirect('/entradaSaida');
    }
}
