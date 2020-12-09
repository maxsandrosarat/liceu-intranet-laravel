<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Turma;
use App\Categoria;
use App\CompraProduto;
use App\Ocorrencia;
use App\Diario;
use App\Disciplina;
use App\EntradaSaida;
use App\ListaCompra;
use App\Produto;
use App\Questao;
use App\Simulado;
use PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OutroController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:outro');
    }
    
    public function index(){
        return view('outros.home_outro');
    }

    //CATEGORIAS
    public function indexCategorias()
    {
        $cats = Categoria::where('ativo',true)->get();
        return view('outros.categorias',compact('cats'));
    }

    public function novaCategoria(Request $request)
    {
        $cat = new Categoria();
        $cat->nome = $request->input('nomeCategoria');
        $cat->save();
        return back();
    }

    public function editarCategoria(Request $request, $id)
    {
        $cat = Categoria::find($id);
        if(isset($cat)){
            $cat->nome = $request->input('nomeCategoria');
            $cat->save();
        }
        return back();
    }

    public function apagarCategoria($id)
    {
        $cat = Categoria::find($id);
        if(isset($cat)){
            $cat->ativo = false;
            $cat->save();
        }
        return back();
    }

    //PRODUTOS
    public function indexProdutos()
    {
        $cats = Categoria::where('ativo',true)->get();
        $prods = Produto::where('ativo',true)->orderBy('nome')->paginate(10);
        $view = "inicial";
        return view('outros.produtos', compact('view','cats','prods'));
    }

    public function novoProduto(Request $request)
    {
        $prod = new Produto();
        $prod->nome = $request->input('nomeProduto');
        $prod->estoque = $request->input('estoqueProduto');
        $prod->categoria_id = $request->input('categoriaProduto');
        $prod->save();
        return back();
    }

    public function filtroProdutos(Request $request)
    {
        $nomeProd = $request->input('nomeProduto');
        $cat = $request->input('categoria');
        if(isset($nomeProd)){
            if(isset($cat)){
                $prods = Produto::where('ativo',true)->where('nome','like',"%$nomeProd%")->where('categoria_id',"$cat")->orderBy('nome')->paginate(100);
            } else {
                $prods = Produto::where('ativo',true)->where('nome','like',"%$nomeProd%")->orderBy('nome')->paginate(100);
            }
        } else {
            if(isset($cat)){
                $prods = Produto::where('ativo',true)->where('categoria_id',"$cat")->orderBy('nome')->paginate(100);
            } else {
                return redirect('/outro/produtos');
            }
        }
        $cats = Categoria::where('ativo',true)->get();
        $view = "filtro";
        return view('outros.produtos', compact('view','cats','prods'));
    }

    public function editarProduto(Request $request, $id)
    {
        $prod = Produto::find($id);
        if(isset($prod)){
            $prod->nome =$request->input('nomeProduto');
            $prod->categoria_id =$request->input('categoriaProduto');
            $prod->save();
        }
        return back();
    }

    public function apagarProduto($id)
    {
        $prod = Produto::find($id);
        if(isset($prod)){
            $prod->ativo = false;
            $prod->save();
        }
        return back();
    }

    //ENTRADAS & SAIDAS
    public function indexEntradaSaidas()
    {
        $rels = EntradaSaida::orderBy('created_at', 'desc')->paginate(10);
        $prods = Produto::where('ativo',true)->orderBy('nome')->get();
        $view = "inicial";
        return view('outros.entrada_saida', compact('view','rels','prods'));
    }

    public function novaEntradaSaida(Request $request)
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
        return back();
    }

    public function filtroEntradaSaidas(Request $request)
    {
        $tipo = $request->input('tipo');
        $produto = $request->input('produto');
        $dataInicio = $request->input('dataInicio');
        $dataFim = $request->input('dataFim');
        if(isset($tipo)){
            if(isset($produto)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->where('produto_id',"$produto")->whereBetween('data',["$dataInicio", "$dataFim"])->paginate(100);
                    } else {
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->where('produto_id',"$produto")->whereBetween('data',["$dataInicio", date("Y/m/d")])->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->where('produto_id',"$produto")->whereBetween('data',["", "$dataFim"])->paginate(100);
                    } else {
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->where('produto_id',"$produto")->paginate(100);
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->whereBetween('data',["$dataInicio", "$dataFim"])->paginate(100);
                    } else {
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->whereBetween('data',["$dataInicio", date("Y/m/d")])->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->whereBetween('data',["", "$dataFim"])->paginate(100);
                    } else {
                        $rels = EntradaSaida::where('tipo','like',"%$tipo%")->paginate(100);
                    }
                }
            }
        } else {
            if(isset($produto)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = EntradaSaida::where('produto_id',"$produto")->whereBetween('data',["$dataInicio", "$dataFim"])->paginate(100);
                    } else {
                        $rels = EntradaSaida::where('produto_id',"$produto")->whereBetween('data',["$dataInicio", date("Y/m/d")])->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = EntradaSaida::where('produto_id',"$produto")->whereBetween('data',["", "$dataFim"])->paginate(100);
                    } else {
                        $rels = EntradaSaida::where('produto_id',"$produto")->paginate(100);
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $rels = EntradaSaida::whereBetween('data',["$dataInicio", "$dataFim"])->paginate(100);
                    } else {
                        $rels = EntradaSaida::whereBetween('data',["$dataInicio", date("Y/m/d")])->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $rels = EntradaSaida::whereBetween('data',["", "$dataFim"])->paginate(100);
                    } else {
                        $rels = EntradaSaida::paginate(10);
                    }
                }
            }
        }
        $prods = Produto::where('ativo',true)->orderBy('nome')->get();
        $view = "filtro";
        return view('outros.entrada_saida', compact('view','rels','prods'));
    }

    //LISTAS DE COMPRAS
    public function indexListaCompras(){
        $listaProds = ListaCompra::with('produtos')->orderBy('created_at','desc')->paginate(10);
        return view('outros.lista_compra', compact('listaProds'));
    }

    public function selecionarListaCompra(){
        $prods = Produto::where('ativo',true)->orderBy('nome')->get();
        return view('outros.lista_compra_selecionar', compact('prods'));
    }

    public function novaListaCompra(Request $request){
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
            return view('outros.compras', compact('rels','lista_id'));
        }
    }

    public function gerarPdfListaCompra($lista_id)
    {
        $lista = ListaCompra::find($lista_id);
        $produtos = CompraProduto::where('lista_compra_id',"$lista_id")->get();
        $pdf = PDF::loadview('outros.compras_pdf', compact('lista','produtos'));
        return $pdf->setPaper('a4')->stream('ListaCompra'.date("d-m-Y", strtotime($lista->data)).'.pdf');
    }

    //DIÁRIO
    public function indexDiario(){
        $turmas = Turma::where('ativo',true)->orderBy('ensino')->orderBy('serie')->get();
        return view('outros.home_diario', compact('turmas'));
    }

    public function consultaDiario(Request $request){
        $turmaId = $request->input('turma');
        $dia = $request->input('data');
        $turma = Turma::find($turmaId);
        $diarios = Diario::where('turma_id',"$turmaId")->where('dia', "$dia")->orderBy('tempo')->get();
        $ocorrencias = Ocorrencia::where('data',"$dia")->get();
        return view('outros.diario_outro', compact('dia','turma','diarios','ocorrencias'));
    }

    //SIMULADOS
    public function indexSimulados(Request $request){
        $ano = $request->input('ano');
        $turmas = Turma::where('ativo',true)->where('turma','A')->get();
        $anos = DB::table('simulados')->select(DB::raw("ano"))->groupBy('ano')->get();
        $simulados = Simulado::where('ano',"$ano")->get();
        return view('outros.home_simulados',compact('ano','turmas','anos','simulados'));
    }

    public function indexSimuladosAno($ano){
        if($ano==""){
            $ano = date("Y");
        }
        $turmas = Turma::where('ativo',true)->where('turma','A')->get();
        $anos = DB::table('simulados')->select(DB::raw("ano"))->groupBy('ano')->get();
        $simulados = Simulado::where('ano',"$ano")->get();
        return view('outros.home_simulados',compact('ano','turmas','anos','simulados'));
    }

    public function painelSimulados($simId){
        $simulado = Simulado::find($simId);
        $ano = $simulado->ano;
        $fundTurmas = "";
        $fundDiscs = "";
        $contFunds = "";
        $medioTurmas = "";
        $medioDiscs = "";
        $contMedios = "";
        $ensino = "";
        $validadorFund = Questao::where('simulado_id', "$simId")->where('ensino','fund')->count();
        if($validadorFund!=0){
            $ensino = "fund";
            $fundTurmas = DB::table('questoes')->where('simulado_id', "$simId")->where('ensino','fund')->select(DB::raw("serie"))->groupBy('serie')->get();
            $fundDiscs = Disciplina::where('ensino','fund')->get();
            $contFunds = Questao::where('simulado_id', "$simId")->where('ensino','fund')->orderBy('disciplina_id')->get();
        }
        $validadorMedio = Questao::where('simulado_id', "$simId")->where('ensino','medio')->count();
        if($validadorMedio!=0){
            $ensino = "medio";
            $medioTurmas = DB::table('questoes')->where('simulado_id', "$simId")->where('ensino','medio')->select(DB::raw("serie"))->groupBy('serie')->get();
            $medioDiscs = Disciplina::where('ensino','medio')->get();
            $contMedios = Questao::where('simulado_id', "$simId")->where('ensino','medio')->orderBy('disciplina_id')->get();
        }
        if($validadorFund!=0 && $validadorMedio!=0){
            $ensino = "todos";
        }
        return view('outros.simulados',compact('ensino','simulado','ano','fundTurmas','medioTurmas','fundDiscs','medioDiscs','contFunds','contMedios'));
    }

    public function downloadSimulado($id)
    {
        $cont = Questao::find($id);
        $discId = $cont->disciplina_id;
        $disciplina = Disciplina::find($discId);
        $simulado = Simulado::find($cont->simulado_id);
        $nameFile = $cont->serie."º - Questões ".$simulado->descricao." ".$simulado->bimestre."º Bim - ".$disciplina->nome;
        if(isset($cont)){
            $path = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($cont->arquivo);
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $name = $nameFile.".".$extension;
            return response()->download($path, $name);
        }
        return back();
    }

    public function conferirSimulado(Request $request)
    {
        $id = $request->id;
        $cont = Questao::find($id);
        $cont->comentario = $request->comentario;
        $cont->conferido = true;
        $cont->save();
        return back();
    }
}
