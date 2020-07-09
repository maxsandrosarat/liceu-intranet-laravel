<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Admin;
use App\Disciplina;
use App\Turma;
use App\Atividade;
use App\Aluno;
use App\AtividadeExtra;
use App\Categoria;
use App\CompraProduto;
use App\TipoOcorrencia;
use App\Ocorrencia;
use App\Conteudo;
use App\EntradaSaida;
use App\ListaAtividade;
use App\ListaCompra;
use App\Outro;
use App\Produto;
use App\Prof;
use App\ProfDisciplina;
use App\Recado;
use App\Responsavel;
use App\ResponsavelAluno;
use App\TurmaDisciplina;
use Excel;
use PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    public function index(){
        return view('admin.home_admin');
    }

    public function cadastroAdmin()
    {
        return view('auth.admin-register');
    }

    public function novoAdmin(Request $request)
    {
        $adm = new Admin();
        $adm->name = $request->input('name');
        $adm->email = $request->input('email');
        $adm->password = Hash::make($request->input('password'));
        $adm->save();
        return back()->with('mensagem', 'Novo Administrador(a) cadastrado com Sucesso!');
    }

    //DISCIPLINAS
    public function indexDisciplinas()
    {
        $discs = Disciplina::where('ativo',true)->get();
        return view('admin.disciplinas',compact('discs'));
    }

    public function novaDisciplina(Request $request)
    {
        $disc = new Disciplina();
        $disc->nome = $request->input('nome');
        $disc->ensino = $request->input('ensino');
        $disc->save();
        return back();
    }

    public function apagarDisciplina($id)
    {
        $disc = Disciplina::find($id);
        if(isset($disc)){
            $disc->ativo = false;
            $disc->save();
            TurmaDisciplina::where('disciplina_id',"$id")->delete();
            ProfDisciplina::where('disciplina_id',"$id")->delete();
        }
        return back();
    }

    //TURMAS
    public function indexTurmas()
    {
        $turmas = Turma::where('ativo',true)->get();
        return view('admin.turmas',compact('turmas'));
    }

    public function novaTurma(Request $request)
    {
        $turma = new Turma();
        $turma->serie = $request->input('serie');
        $turma->turma = $request->input('turma');
        $turma->turno = $request->input('turno');
        $turma->ensino = $request->input('ensino');
        $turma->save();
        return back();
    }

    public function apagarTurma($id)
    {
        $turma = Turma::find($id);
        if(isset($turma)){
            $turma->ativo = false;
            $turma->save();
        }
        return back();
    }

    //TURMAS & DISCIPLINAS
    public function indexTurmaDiscs()
    {
        $turmaDiscs = Turma::where('ativo',true)->with('disciplinas')->get();
        $turmas = Turma::where('ativo',true)->get();
        $discs = Disciplina::where('ativo',true)->get();
        return view('admin.turmas_disciplinas',compact('turmaDiscs','turmas','discs'));
    }

    public function novaTurmaDisc(Request $request)
    {
        if($request->input('disciplina')=="todasFund"){
            $discFunds = Disciplina::where('ativo',true)->where('ensino','fund')->get();
            foreach($discFunds as $discFund){
                $turmaDisc = new TurmaDisciplina();
                $turmaDisc->turma_id = $request->input('turma');
                $turmaDisc->disciplina_id = $discFund->id;
                $turmaDisc->save();
            }
        } elseif($request->input('disciplina')=="todasMedio"){
            $discs = Disciplina::where('ativo',true)->where('ensino','medio')->get();
            foreach($discs as $disc){
                $turmaDisc = new TurmaDisciplina();
                $turmaDisc->turma_id = $request->input('turma');
                $turmaDisc->disciplina_id = $disc->id;
                $turmaDisc->save();
            }
        } elseif($request->input('disciplina')==""){
            if($request->input('disciplinas')==""){
                return redirect('/turmasDiscs');
            } else {
                $disciplinas = $request->input('disciplinas');
                foreach($disciplinas as $disciplina){
                    $turmaDisc = new TurmaDisciplina();
                    $turmaDisc->turma_id = $request->input('turma');
                    $turmaDisc->disciplina_id = $disciplina;
                    $turmaDisc->save();
                }
            }
        }
        return back();
    }

    public function apagarTurmaDisc($turma_id, $disciplina_id)
    {
        TurmaDisciplina::where('turma_id',"$turma_id")->where('disciplina_id',"$disciplina_id")->delete();
        return back();
    }

    //TIPOS DE OCORRENCIAS
    public function indexTiposOcorrencia()
    {
        $tipos = TipoOcorrencia::where('ativo',true)->get();
        return view('admin.tipo_ocorrencia',compact('tipos'));
    }

    public function novoTipoOcorrencia(Request $request)
    {
        $tipo = new TipoOcorrencia();
        $tipo->codigo = $request->input('codigo');
        $tipo->descricao = $request->input('descricao');
        $tipo->tipo = $request->input('tipo');
        $tipo->pontuacao = $request->input('pontuacao');
        $tipo->save();
        return back();
    }

    public function editarTipoOcorrencia(Request $request, $id)
    {
        $tipo = TipoOcorrencia::find($id);
        if(isset($tipo)){
            if($request->input('codigo')!=""){
                $tipo->codigo = $request->input('codigo');
            }
            if($request->input('descricao')!=""){
                $tipo->descricao = $request->input('descricao');
            }
            if($request->input('tipo')!=""){
                $tipo->tipo = $request->input('tipo');
            }
            if($request->input('pontuacao')!=""){
                $tipo->pontuacao = $request->input('pontuacao');
            }
            $tipo->save();
        }
        return back();
    }

    public function apagarTipoOcorrencia($id)
    {
        $tipo = TipoOcorrencia::find($id);
        if(isset($tipo)){
            $tipo->ativo = false;
            $tipo->save();
        }
        return back();
    }

    //TEMPLATES    
	public function templates($nome){
        if($nome=="aluno"){
            $nameFile = "import_alunos";
            $path = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix("templates/import_alunos.xlsx");
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $name = $nameFile.".".$extension;
            return response()->download($path, $name);
        } else if($nome=="outro"){
            $nameFile = "import_outro";
            $path = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix("templates/import_outro.xlsx");
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $name = $nameFile.".".$extension;
            return response()->download($path, $name);
        } else {
            return back();
        }
    }

    //PROFESSORES
    public function indexProfs()
    {
        $profs = Prof::where('ativo',true)->with('disciplinas')->orderBy('name')->paginate(10);
        $discs = Disciplina::where('ativo',true)->get();
        $view = "inicial";
        return view('admin.profs', compact('view','profs','discs'));
    }

    public function novoProf(Request $request)
    {
        $prof = new Prof();
        $prof->name = $request->input('name');
        $prof->email = $request->input('email');
        $prof->password = Hash::make($request->input('password'));
        $prof->save();
        $disciplinas = $request->input('disciplinas');
                foreach($disciplinas as $disciplina){
                    $profDisc = new ProfDisciplina();
                    $profDisc->prof_id = $prof->id;
                    $profDisc->disciplina_id = $disciplina;
                    $profDisc->save();
                }
        return back();
    }

    public function filtroProfs(Request $request)
    {
        $nome = $request->input('nome');
        $disc = $request->input('disciplina');
        if(isset($nome)){
            if(isset($disc)){
                $profDiscs = ProfDisciplina::where('disciplina_id',"$disc")->get();
                $profIds = array();
                foreach($profDiscs as $profDisc){
                    $profIds[] = $profDisc->prof_id;
                }
                $profs = Prof::whereIn('id', $profIds)->where('ativo',true)->where('name','like',"%$nome%")->orderBy('name')->paginate(100);
            } else {
                $profs = Prof::where('ativo',true)->where('name','like',"%$nome%")->orderBy('name')->paginate(100);
            }
        } else {
            if(isset($disc)){
                $profDiscs = DB::table('prof_disciplinas')->select(DB::raw("prof_id"))->where('disciplina_id',"$disc")->get();
                $profIds = array();
                foreach($profDiscs as $profDisc){
                    $profIds[] = $profDisc->prof_id;
                }
                $profs = Prof::whereIn('id', $profIds)->where('ativo',true)->orderBy('name')->paginate(100);
            } else {
                return redirect('/admin/prof');
            }
        }
        $discs = Disciplina::where('ativo',true)->get();
        $view = "filtro";
        return view('admin.profs', compact('view','discs','profs'));
    }

    public function editarProf(Request $request, $id)
    {
        $prof = Prof::find($id);
        if(isset($prof)){
            $prof->name =$request->input('name');
            $prof->email =$request->input('email');
            if($request->input('password')!=""){
            $prof->password = Hash::make($request->input('password'));
            }
            $prof->save();
            $disciplinas = $request->input('disciplinas');
            foreach ($disciplinas as $disciplina) {
                $profDiscs = ProfDisciplina::where('prof_id',"$id")->where('disciplina_id',"$disciplina")->get();
                if($profDiscs->count()==0){
                    $profDisc = new ProfDisciplina();
                    $profDisc->prof_id = $id;
                    $profDisc->disciplina_id = $disciplina;
                    $profDisc->save();
                }
            }
        }
        return back();
    }

    public function apagarProf($id)
    {
        $prof = Prof::find($id);
        if(isset($prof)){
            $prof->ativo = false;
            $prof->save();
        }
        return back();
    }

    public function desvincularDisciplinaProf($prof_id, $disciplina_id)
    {
        ProfDisciplina::where('prof_id',"$prof_id")->where('disciplina_id',"$disciplina_id")->delete();
        return back();
    }

    //ALUNOS
    public function indexAlunos()
    {
        $turmas = Turma::where('ativo',true)->get();
        $alunos = Aluno::where('ativo',true)->orderBy('name')->paginate(10);
        $view = "inicial";
        return view('admin.alunos', compact('view','turmas','alunos'));
    }

    public function novoAluno(Request $request)
    {
        $request->validate([
            'email' => 'unique:alunos',
            'password' => 'min:8',
        ], $mensagens =[
            'email.unique' => 'Já existe um usuário com esse login!',
            'password.min' => 'A senha deve conter no mínimo 8 caracteres!',
        ]);

        $aluno = new Aluno();
        $aluno->name = $request->input('name');
        $aluno->email = $request->input('email');
        $aluno->password = Hash::make($request->input('password'));
        $aluno->turma_id = $request->input('turma');
        if($request->file('foto')!=""){
        $path = $request->file('foto')->store('fotos_perfil','public');
        $aluno->foto = $path;
        }
        $aluno->save();
        return back();
    }

    public function importarAlunoExcel(Request $request)
    {
        Excel::import(new \App\Imports\AlunoImport, $request->file('arquivo'));
        return back()->with('success', 'Dados importados do Excel com Sucesso!');
    }

    public function filtroAluno(Request $request)
    {
        $nome = $request->input('nome');
        $turma = $request->input('turma');
        if(isset($nome)){
            if(isset($turma)){
                $alunos = Aluno::where('ativo',true)->where('name','like',"%$nome%")->where('turma_id',"$turma")->orderBy('name')->paginate(50);
            } else {
                $alunos = Aluno::where('ativo',true)->where('name','like',"%$nome%")->orderBy('name')->paginate(50);
            }
        } else {
            if(isset($turma)){
                $alunos = Aluno::where('ativo',true)->where('turma_id',"$turma")->orderBy('name')->paginate(50);
            } else {
                return redirect('/admin/aluno');
            }
        }
        $turmas = Turma::where('ativo',true)->get();
        $view = "filtro";
        return view('admin.alunos', compact('view','turmas','alunos'));
    }

    public function editarAluno(Request $request, $id)
    {
        $aluno = Aluno::find($id);
        if(isset($aluno)){
            $aluno->name =$request->input('name');
            $aluno->email =$request->input('email');
            if($request->input('password')!=""){
            $aluno->password = Hash::make($request->input('password'));
            }
            $aluno->turma_id = $request->input('turma');
            if($request->file('foto')!=""){
                Storage::disk('public')->delete($aluno->foto);
                $path = $request->file('foto')->store('fotos_perfil','public');
                $aluno->foto = $path;
            }
            $aluno->save();
        }
        return back();
    }

    public function apagarAluno($id)
    {
        $aluno = Aluno::find($id);
        if(isset($aluno)){
            $aluno->ativo = false;
            $aluno->save();
        }
        return back();
    }

    //RESPONSAVEIS
    public function indexResps()
    {
        $resps = Responsavel::where('ativo',true)->with('alunos')->orderBy('name')->paginate(10);
        $alunos = Aluno::where('ativo',true)->orderBy('name')->get();
        $view = "inicial";
        return view('admin.responsavel', compact('view','resps','alunos'));
    }

    public function novoResp(Request $request)
    {
        $resp = new Responsavel();
        $resp->name = $request->input('name');
        $resp->email = $request->input('email');
        $resp->password = Hash::make($request->input('password'));
        $resp->save();
        return back();
    }

    public function filtroResps(Request $request)
    {
        $nome = $request->input('nome');
        $aluno = $request->input('aluno');
        if(isset($nome)){
            if(isset($aluno)){
                $respAlunos = ResponsavelAluno::where('aluno_id',"$aluno")->get();
                $respIds = array();
                foreach($respAlunos as $respAluno){
                    $respIds[] = $respAluno->responsavel_id;
                }
                $resps = Responsavel::whereIn('id', $respIds)->where('ativo',true)->where('name','like',"%$nome%")->orderBy('name')->paginate(100);
            } else {
                $resps = Responsavel::where('ativo',true)->where('name','like',"%$nome%")->orderBy('name')->paginate(100);
            }
        } else {
            if(isset($aluno)){
                $respAlunos = ResponsavelAluno::where('aluno_id',"$aluno")->get();
                $respIds = array();
                foreach($respAlunos as $respAluno){
                    $respIds[] = $respAluno->responsavel_id;
                }
                $resps = Responsavel::whereIn('id', $respIds)->where('ativo',true)->orderBy('name')->paginate(100);
            } else {
                return redirect('/admin/responsavel');
            }
        }
        $alunos = Aluno::where('ativo',true)->orderBy('name')->get();
        $view = "filtro";
        return view('admin.responsavel', compact('view','resps','alunos'));
    }

    public function editarResp(Request $request, $id)
    {
        $resp = Responsavel::find($id);
        if(isset($resp)){
            $resp->name =$request->input('name');
            $resp->email =$request->input('email');
            if($request->input('password')!=""){
            $resp->password = Hash::make($request->input('password'));
            }
            $resp->save();
        }
        return back();
    }

    public function apagarResp($id)
    {
        $resp = Responsavel::find($id);
        if(isset($resp)){
            $resp->ativo = false;
            $resp->save();
        }
        return back();
    }

    public function vincularAlunoResp(Request $request, $id)
    {
        $respAluno = new ResponsavelAluno();
        $respAluno->responsavel_id = $id;
        $respAluno->aluno_id = $request->input('aluno');
        $respAluno->save();
        
        return back();
    }

    public function desvincularAlunoResp($resp_id, $aluno_id)
    {
        ResponsavelAluno::where('responsavel_id',"$resp_id")->where('aluno_id',"$aluno_id")->delete();
        return back();
    }

    //OUTROS(COLABORADOR)
    public function indexOutros()
    {
        $outros = Outro::where('ativo',true)->orderBy('name')->paginate(10);
        $view = "inicial";
        return view('admin.outros', compact('view','outros'));
    }

    public function novoOutro(Request $request)
    {
        $outro = new Outro();
        $outro->name = $request->input('name');
        $outro->email = $request->input('email');
        $outro->password = Hash::make($request->input('password'));
        $outro->save();
        return back();
    }

    public function importarOutroExcel(Request $request)
    {
        Excel::import(new \App\Imports\OutroImport, $request->file('arquivo'));
        return back()->with('success', 'Dados importados do Excel com Sucesso!');
    }

    public function filtroOutros(Request $request)
    {
        $nome = $request->input('nome');
        if(isset($nome)){
                $outros = Outro::where('ativo',true)->where('name','like',"%$nome%")->orderBy('name')->paginate(10);
        } else {
            return back();
        }
        $view = "filtro";
        return view('outros.outros', compact('view','outros'));
    }

    public function editarOutro(Request $request, $id)
    {
        $outro = Outro::find($id);
        if(isset($outro)){
            $outro->name =$request->input('name');
            $outro->email =$request->input('email');
            if($request->input('password')!=""){
            $outro->password = Hash::make($request->input('password'));
            }
            $outro->save();
        }
        return back();
    }

    public function apagarOutro($id)
    {
        $outro = Outro::find($id);
        if(isset($outro)){
            $outro->ativo = false;
            $outro->save();
        }
        return back();
    }

    //CATEGORIAS
    public function indexCategorias()
    {
        $cats = Categoria::where('ativo',true)->get();
        return view('admin.categorias',compact('cats'));
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
        return view('admin.produtos', compact('view','cats','prods'));
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
                return redirect('/admin/produtos');
            }
        }
        $cats = Categoria::where('ativo',true)->get();
        $view = "filtro";
        return view('admin.produtos', compact('view','cats','prods'));
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
        return view('admin.entrada_saida', compact('view','rels','prods'));
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
        return view('admin.entrada_saida', compact('view','rels','prods'));
    }

    //LISTAS DE COMPRAS
    public function indexListaCompras(){
        $listaProds = ListaCompra::with('produtos')->orderBy('created_at','desc')->paginate(10);
        return view('admin.lista_compra', compact('listaProds'));
    }

    public function selecionarListaCompra(){
        $prods = Produto::where('ativo',true)->orderBy('nome')->get();
        return view('admin.lista_compra_selecionar', compact('prods'));
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
            return view('admin.compras', compact('rels','lista_id'));
        }
    }

    public function gerarPdfListaCompra($lista_id)
    {
        $lista = ListaCompra::find($lista_id);
        $produtos = CompraProduto::where('lista_compra_id',"$lista_id")->get();
        $pdf = PDF::loadView('admin.compras_pdf', compact('lista','produtos'));
        return $pdf->setPaper('a4')->stream('ListaCompra'.date("d-m-Y", strtotime($lista->data)).'.pdf');
    }

    //ATIVIDADES
    public function painelAtividades(){
        $discs = Disciplina::where('ativo',true)->orderBy('nome')->get();
        $turmas = Turma::where('ativo',true)->get();
        $atividades = Atividade::orderBy('id','desc')->paginate(10);
        $view = "inicial";
        return view('admin.atividade_admin', compact('view','discs','turmas','atividades'));
    }

    public function novaAtividade(Request $request)
    {
        $discId = $request->input('disciplina');
        $profs = DB::table('profs')->select(DB::raw("id"))->where('disciplina_id', "$discId")->get();
        foreach($profs as $prof){
            $profId = $prof->id;
        }
        $path = $request->file('arquivo')->store('atividades','public');
        $atividade = new Atividade();
        $atividade->prof_id = $profId;
        $atividade->disciplina_id = $request->input('disciplina');
        $atividade->turma_id = $request->input('turma');
        $atividade->data_criacao = date("Y/m/d");
        if($request->input('dataPublicacao')!=""){
            $atividade->data_publicacao = $request->input('dataPublicacao');
        }
        if($request->input('dataExpiracao')!=""){
            $atividade->data_expiracao = $request->input('dataExpiracao');
        }
        $atividade->descricao = $request->input('descricao');
        $atividade->link = $request->input('link');
        $atividade->visualizacoes = 0;
        $atividade->arquivo = $path;
        $atividade->save();
        
        return redirect('/admin/atividade');
    }

    public function filtroAtividade(Request $request)
    {
        $turma = $request->input('turma');
        $disciplina = $request->input('disciplina');
        $descricao = $request->input('descricao');
        $data = $request->input('data');
        if(isset($turma)){
            if(isset($disciplina)){
                if(isset($descricao)){
                    if(isset($data)){
                        $atividades = Atividade::where('descricao','like',"%$descricao%")->where('disciplina_id',"$disciplina")->where('turma_id',"$turma")->where('data_criacao',"$data")->orderBy('id','desc')->paginate(50);
                    } else {
                        $atividades = Atividade::where('descricao','like',"%$descricao%")->where('disciplina_id',"$disciplina")->where('turma_id',"$turma")->orderBy('id','desc')->paginate(50);
                    }
                } else {
                    $atividades = Atividade::where('turma_id',"$turma")->where('disciplina_id',"$disciplina")->orderBy('id','desc')->paginate(50);
                }
            } else {
                $atividades = Atividade::where('turma_id',"$turma")->orderBy('id','desc')->paginate(50);
            }
        } else {
            if(isset($disciplina)){
                if(isset($descricao)){
                    if(isset($data)){
                        $atividades = Atividade::where('descricao','like',"%$descricao%")->where('disciplina_id',"$disciplina")->where('data_criacao',"$data")->orderBy('id','desc')->paginate(50);
                    } else {
                        $atividades = Atividade::where('descricao','like',"%$descricao%")->where('disciplina_id',"$disciplina")->orderBy('id','desc')->paginate(50);
                    }
                } else {
                    $atividades = Atividade::where('disciplina_id',"$disciplina")->orderBy('id','desc')->paginate(50);
                }
            } else {
                if(isset($descricao)){
                    if(isset($data)){
                        $atividades = Atividade::where('descricao','like',"%$descricao%")->where('data_criacao',"$data")->orderBy('id','desc')->paginate(50);
                    } else {
                        $atividades = Atividade::where('descricao','like',"%$descricao%")->orderBy('id','desc')->paginate(50);
                    }
                } else {
                    if(isset($data)){
                        $atividades = Atividade::where('data_criacao',"$data")->orderBy('id','desc')->paginate(50);
                    } else {
                        $atividades = Atividade::orderBy('id','desc')->paginate(10);
                    }
                }
            }
        }
        $discs = Disciplina::where('ativo',true)->orderBy('nome')->get();
        $turmas = Turma::where('ativo',true)->get();
        $view = "filtro";
        return view('admin.atividade_admin', compact('view','discs','turmas','atividades'));
    }

    public function editarAtividade(Request $request, $id)
    {
        $atividade = Atividade::find($id);
        if($request->file('arquivo')!=""){
            $arquivo = $atividade->arquivo;
            Storage::disk('public')->delete($arquivo);
            $path = $request->file('arquivo')->store('atividades','public');
        } else {
            $path = "";
        }
        if($request->input('turma')!=""){
            $atividade->turma_id = $request->input('turma');
        }
        if($request->input('dataPublicacao')!=""){
            $atividade->data_publicacao = $request->input('dataPublicacao');
        }
        if($request->input('dataExpiracao')!=""){
            $atividade->data_expiracao = $request->input('dataExpiracao');
        }
        if($request->input('descricao')!=""){
            $atividade->descricao = $request->input('descricao');
        }
        if($request->input('link')!=""){
            $atividade->link = $request->input('link');
        }
        if($path!=""){
            $atividade->arquivo = $path;
        }
        $atividade->save();
        
        return redirect('/admin/atividade');
    }

    public function apagarAtividade($id){
        $atividade = Atividade::find($id);
        $arquivo = $atividade->arquivo;
        Storage::disk('public')->delete($arquivo);
        if(isset($atividade)){
            $atividade->delete();
        }
        return redirect('/admin/atividade');
    }

    public function downloadAtividade($id)
    {
        $atividade = Atividade::find($id);
        $disc = Disciplina::find($atividade->disciplina_id);
        $turma = Turma::find($atividade->turma_id);
        $nameFile = $turma->serie."º - Atividade ".$atividade->descricao." - ".$disc->nome;
        if(isset($atividade)){
            $path = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($atividade->arquivo);
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $name = $nameFile.".".$extension;
            return response()->download($path, $name);
        }
        return back();
    }

    //LISTAS DE ATIVIDADES
    public function painelLAs($data){
        $lafund = ListaAtividade::where('dia', "$data")->where('ensino','fund')->count();
        $lamedio = ListaAtividade::where('dia', "$data")->where('ensino','medio')->count();
        if($lafund==0){
            $discs = Disciplina::where('ativo',true)->where('ensino','fund')->get();
            $turmas = Turma::select('serie')->where('ativo',true)->where('ensino','fund')->groupby('serie')->get();
            foreach($turmas as $turma){
                foreach($discs as $disc){
                    $lf = new ListaAtividade();
                    $lf->dia = $data;
                    $lf->serie = $turma->serie;
                    $lf->ensino = "fund";
                    $lf->disciplina_id = $disc->id;
                    $lf->save();
                }
            }
        }
        if($lamedio==0){
            $discs = Disciplina::where('ativo',true)->where('ensino','medio')->get();
            $turmas = Turma::where('ativo',true)->where('ensino','medio')->distinct('turma')->get();
            foreach($turmas as $turma){
                foreach($discs as $disc){
                    $lm = new ListaAtividade();
                    $lm->dia = $data;
                    $lm->serie = $turma->serie;
                    $lm->ensino = "medio";
                    $lm->disciplina_id = $disc->id;
                    $lm->save();
                }
            }
        }
        $fundTurmas = Turma::select('serie')->where('ativo',true)->groupby('serie')->where('ensino','fund')->get();
        $medioTurmas = Turma::select('serie')->where('ativo',true)->groupby('serie')->where('ensino','medio')->get();
        $fundDiscs = Disciplina::where('ativo',true)->where('ensino','fund')->get();
        $medioDiscs = Disciplina::where('ativo',true)->where('ensino','medio')->get();
        $laFunds = ListaAtividade::orderBy('disciplina_id')->where('dia', "$data")->where('ensino','fund')->get();
        $laMedios = ListaAtividade::orderBy('disciplina_id')->where('dia', "$data")->where('ensino','medio')->get();
        return view('admin.lista_atividade_admin',compact('data','fundTurmas','medioTurmas','fundDiscs','medioDiscs','laFunds','laMedios'));
    }

    public function anexarLA($id, Request $request)
    {
        $la = ListaAtividade::find($id);
        $path = $request->file('arquivo')->store('las','public');
        if($la->arquivo==null || $la->arquivo==""){
            $la->arquivo = $path;
            $la->save();
        } else {
            $arquivo = $la->arquivo;
            Storage::disk('public')->delete($arquivo);
            $la->arquivo = $path;
            $la->save();
        }
        return back();
    }

    public function downloadLA($id)
    {
        $la = ListaAtividade::find($id);
        $serie = $la->serie;
        $discId = $la->disciplina_id;
        $disciplina = Disciplina::find($discId);
        $nameFile = "";
        switch ($serie) {
                case "6": $nameFile = "6º - LA ".date("d-m-Y", strtotime($la->dia))." - ".$disciplina->nome; break;
                case "7": $nameFile = "7º - LA ".date("d-m-Y", strtotime($la->dia))." - ".$disciplina->nome; break;
                case "8": $nameFile = "8º - LA ".date("d-m-Y", strtotime($la->dia))." - ".$disciplina->nome; break;
                case "9": $nameFile = "9º - LA ".date("d-m-Y", strtotime($la->dia))." - ".$disciplina->nome; break;
                case "1": $nameFile = "1º - LA ".date("d-m-Y", strtotime($la->dia))." - ".$disciplina->nome; break;
                case "2": $nameFile = "2º - LA ".date("d-m-Y", strtotime($la->dia))." - ".$disciplina->nome; break;
                case "3": $nameFile = "3º - LA ".date("d-m-Y", strtotime($la->dia))." - ".$disciplina->nome; break;
                default: $nameFile = "";
        };
        if(isset($la)){
            $path = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($la->arquivo);
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $name = $nameFile.".".$extension;
            return response()->download($path, $name);
        }
        return back();
    }

    public function apagarLA($id){
        $la = ListaAtividade::find($id);
        $arquivo = $la->arquivo;
        Storage::disk('public')->delete($arquivo);
        $la->arquivo = "";
        $la->save();
        return back();
    }

    //OCORRENCIAS
    public function indexOcorrencias(){
        $alunos = Aluno::where('ativo',true)->orderBy('name')->get();
        $tipos = TipoOcorrencia::where('ativo',true)->get();
        $ocorrencias = Ocorrencia::paginate(10);
        $view = "inicial";
        return view('admin.ocorrencias_admin', compact('view','alunos','tipos','ocorrencias'));
    }

    public function filtroOcorrencias(Request $request)
    {
        $tipo = $request->input('tipo');
        $aluno = $request->input('aluno');
        $dataInicio = $request->input('dataInicio');
        $dataFim = $request->input('dataFim');
        if(isset($tipo)){
            if(isset($aluno)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->where('aluno_id',"$aluno")->whereBetween('data',["$dataInicio", "$dataFim"])->paginate(100);
                    } else {
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->where('aluno_id',"$aluno")->whereBetween('data',["$dataInicio", date("Y/m/d")])->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->where('aluno_id',"$aluno")->whereBetween('data',["", "$dataFim"])->paginate(100);
                    } else {
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->where('aluno_id',"$aluno")->paginate(100);
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->whereBetween('data',["$dataInicio", "$dataFim"])->paginate(100);
                    } else {
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->whereBetween('data',["$dataInicio", date("Y/m/d")])->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->whereBetween('data',["", "$dataFim"])->paginate(100);
                    } else {
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->paginate(100);
                    }
                }
            }
        } else {
            if(isset($aluno)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::where('aluno_id',"$aluno")->whereBetween('data',["$dataInicio", "$dataFim"])->paginate(100);
                    } else {
                        $ocorrencias = Ocorrencia::where('aluno_id',"$aluno")->whereBetween('data',["$dataInicio", date("Y/m/d")])->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::where('aluno_id',"$aluno")->whereBetween('data',["", "$dataFim"])->paginate(100);
                    } else {
                        $ocorrencias = Ocorrencia::where('aluno_id',"$aluno")->paginate(100);
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::whereBetween('data',["$dataInicio", "$dataFim"])->paginate(100);
                    } else {
                        $ocorrencias = Ocorrencia::whereBetween('data',["$dataInicio", date("Y/m/d")])->paginate(100);
                    }
                } else {
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::whereBetween('data',["", "$dataFim"])->paginate(100);
                    } else {
                        return back();
                    }
                }
            }
        }
        $alunos = Aluno::where('ativo',true)->orderBy('name')->get();
        $tipos = TipoOcorrencia::where('ativo',true)->get();
        $view = "filtro";
        return view('admin.ocorrencias_admin', compact('view','alunos','tipos','ocorrencias'));
    }

    public function aprovarOcorrencia($id){
        $ocorrencia = Ocorrencia::find($id);
        $ocorrencia->aprovado = true;
        $ocorrencia->save();
        return back();
    }

    public function reprovarOcorrencia($id){
        $ocorrencia = Ocorrencia::find($id);
        $ocorrencia->aprovado = false;
        $ocorrencia->save();
        return back();
    }

    public function apagarOcorrencia($id)
    {
        $ocorrencia = Ocorrencia::find($id);
        if(isset($ocorrencia)){
            $ocorrencia->delete();
        }
        return back();
    }

    //CONTEUDOS
    public function indexConteudos(Request $request){
        $ano = $request->input('ano');
        $anos = DB::table('conteudos')->select(DB::raw("ano"))->groupBy('ano')->get();
        return view('admin.home_conteudos',compact('ano','anos'));
    }

    public function indexConteudosAno($ano){
        if($ano==""){
            $ano = date("Y");
        }
        $anos = DB::table('conteudos')->select(DB::raw("ano"))->groupBy('ano')->get();
        return view('admin.home_conteudos',compact('ano','anos'));
    }

    public function painelConteudos($ano, $bim, $tipo){
        $validador = Conteudo::where('tipo', "$tipo")->where('bimestre',"$bim")->where('ano',"$ano")->count();
        if($validador==0){
            return back()->with('mensagem', 'Os campos para anexar os Conteúdos não foram gerados, por favor gerar!');
        } else {
            $fundTurmas = Turma::select('serie')->where('ensino','fund')->groupby('serie')->get();
            $medioTurmas = Turma::select('serie')->where('ensino','medio')->groupby('serie')->get();
            $fundDiscs = Disciplina::where('ensino','fund')->get();
            $medioDiscs = Disciplina::where('ensino','medio')->get();
            $contFunds = Conteudo::orderBy('disciplina_id')->where('tipo', "$tipo")->where('bimestre',"$bim")->where('ensino','fund')->where('ano',"$ano")->get();
            $contMedios = Conteudo::orderBy('disciplina_id')->where('tipo', "$tipo")->where('bimestre',"$bim")->where('ensino','medio')->where('ano',"$ano")->get();
            return view('admin.conteudos',compact('tipo','bim','fundTurmas','medioTurmas','fundDiscs','medioDiscs','contFunds','contMedios','ano'));
        }
    }

    public function gerarConteudos(Request $request){
        $tipos = $request->input('tipos');
        $ano = $request->input('ano');
        $bimestre = $request->input('bimestre');
        $discs = Disciplina::where('ativo',true)->get();
        $turmas = Turma::distinct('turma')->get();
        foreach($tipos as $tipo){
            foreach($turmas as $turma){
                $serie = $turma->serie;
                $ensino = $turma->ensino;
                    foreach($discs as $disc){
                        if($disc->ensino=="fund" && $ensino=="fund"){
                            $validador = Conteudo::where('tipo',"$tipo")->where('bimestre', "$bimestre")->where('ano', "$ano")->where('serie', "$serie")->where('ensino', 'fund')->where('disciplina_id', "$disc->id")->count();
                            if($validador == 0){
                                $cont = new Conteudo();
                                $cont->tipo = $tipo;
                                $cont->bimestre = $bimestre;
                                $cont->ano = $ano;
                                $cont->serie = $serie;
                                $cont->ensino = "fund";
                                $cont->disciplina_id = $disc->id;
                                $cont->save();
                            }
                        } else if($disc->ensino=="medio" && $ensino=="medio"){
                            $validador = Conteudo::where('tipo',"$tipo")->where('bimestre', "$bimestre")->where('ano', "$ano")->where('serie', "$serie")->where('ensino', 'medio')->where('disciplina_id', "$disc->id")->count();
                            if($validador == 0){
                                $cont = new Conteudo();
                                $cont->tipo = $tipo;
                                $cont->bimestre = $bimestre;
                                $cont->ano = $ano;
                                $cont->serie = $serie;
                                $cont->ensino = "medio";
                                $cont->disciplina_id = $disc->id;
                                $cont->save();
                            }
                        }
                    }
            }
        }
        return back()->with('mensagem', 'Conteúdos gerados com sucesso!');
    }

    public function anexarConteudo(Request $request, $id)
    {
        $path = $request->file('arquivo')->store('conteudos','public');
        $cont = Conteudo::find($id);
        if($cont->arquivo=="" || $cont->arquivo==null){
            $cont->arquivo = $path;
            $cont->save();
        } else {
            $arquivo = $cont->arquivo;
            Storage::disk('public')->delete($arquivo);
            $cont->arquivo = $path;
            $cont->save();
        }
        return back();
    }

    public function downloadConteudo($id)
    {
        $cont = Conteudo::find($id);
        $discId = $cont->disciplina_id;
        $disciplina = Disciplina::find($discId);
        $nameFile = "";
        switch ($cont->serie) {
                case 6: $nameFile = "6º - Conteúdo ".$cont->tipo." ".$cont->bimestre."º Bim - ".$disciplina->nome; break;
                case 7: $nameFile = "7º - Conteúdo ".$cont->tipo." ".$cont->bimestre."º Bim - ".$disciplina->nome; break;
                case 8: $nameFile = "8º - Conteúdo ".$cont->tipo." ".$cont->bimestre."º Bim - ".$disciplina->nome; break;
                case 9: $nameFile = "9º - Conteúdo ".$cont->tipo." ".$cont->bimestre."º Bim - ".$disciplina->nome; break;
                case 1: $nameFile = "1º - Conteúdo ".$cont->tipo." ".$cont->bimestre."º Bim - ".$disciplina->nome; break;
                case 2: $nameFile = "2º - Conteúdo ".$cont->tipo." ".$cont->bimestre."º Bim - ".$disciplina->nome; break;
                case 3: $nameFile = "3º - Conteúdo ".$cont->tipo." ".$cont->bimestre."º Bim - ".$disciplina->nome; break;
                default: $nameFile = "";
        };
        if(isset($cont)){
            $path = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($cont->arquivo);
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $name = $nameFile.".".$extension;
            return response()->download($path, $name);
        }
        return back();
    }

    public function apagarConteudo($id){
        $cont = Conteudo::find($id);
        $arquivo = $cont->arquivo;
        Storage::disk('public')->delete($arquivo);
        $cont->arquivo = "";
        $cont->save();
        return back();
    }

    //RECADOS
    public function indexRecados(){
        $recados = Recado::with(['turma', 'aluno'])->paginate(10);
        $turmas = Turma::where('ativo',true)->get();
        $alunos = Aluno::where('ativo',true)->orderBy('name')->get();
        $view = "inicial";
        return view('admin.recados_admin', compact('view','recados','turmas','alunos'));
    }

    public function novoRecado(Request $request){
        if($request->input('geral')!=""){
            if($request->input('geral')==true){
                $recado = new Recado();
                $recado->titulo = $request->input('titulo');
                $recado->descricao = $request->input('descricao');
                $recado->geral = true;
                $recado->save();
                return back();
            } else{
                if($request->input('turma')!=""){
                    $recado = new Recado();
                    $recado->titulo = $request->input('titulo');
                    $recado->descricao = $request->input('descricao');
                    $recado->geral = false;
                    $recado->turma_id = $request->input('turma');
                    $recado->save();
                    return back();
                } else{
                    $recado = new Recado();
                    $recado->titulo = $request->input('titulo');
                    $recado->descricao = $request->input('descricao');
                    $recado->geral = false;
                    $recado->aluno_id = $request->input('aluno');
                    $recado->save();
                    return back();
                }
            }
        }
        return back();
    }

    public function filtroRecados(Request $request)
    {
        $titulo = $request->input('titulo');
        $dataInicio = $request->input('dataInicio');
        $dataFim = $request->input('dataFim');
        if(isset($titulo)){
            if(isset($dataInicio)){
                if(isset($dataFim)){
                    $recados = Recado::where('titulo','like',"%$titulo%")->whereBetween('data',["$dataInicio", "$dataFim"])->paginate(100);
                } else {
                    $recados = Recado::where('titulo','like',"%$titulo%")->whereBetween('data',["$dataInicio", date("Y/m/d")])->paginate(100);
                }
            } else {
                if(isset($dataFim)){
                    $recados = Recado::where('titulo','like',"%$titulo%")->whereBetween('data',["", "$dataFim"])->paginate(100);
                } else {
                    $recados = Recado::where('titulo','like',"%$titulo%")->paginate(100);
                }
            }
        } else {
            if(isset($dataInicio)){
                if(isset($dataFim)){
                    $recados = Recado::whereBetween('data',["$dataInicio", "$dataFim"])->paginate(100);
                } else {
                    $recados = Recado::whereBetween('data',["$dataInicio", date("Y/m/d")])->paginate(100);
                }
            } else {
                if(isset($dataFim)){
                    $recados = Recado::whereBetween('data',["", "$dataFim"])->paginate(100);
                } else {
                    $recados = Recado::paginate(10);
                }
            }
        }
        $turmas = Turma::where('ativo',true)->get();
        $alunos = Aluno::where('ativo',true)->orderBy('name')->get();
        $view = "filtro";
        return view('admin.recados_admin', compact('view','recados','turmas','alunos'));
    }

    public function editarRecado(Request $request, $id)
    {
        $recado = Recado::find($id);
        if($request->input('geral')!=""){
            if($request->input('geral')==true){
                $recado->titulo = $request->input('titulo');
                $recado->descricao = $request->input('descricao');
                $recado->geral = true;
                $recado->turma_id = NULL;
                $recado->aluno_id = NULL;
                $recado->save();
                return back();
            } else{
                if($request->input('turma')!=""){
                    $recado->titulo = $request->input('titulo');
                    $recado->descricao = $request->input('descricao');
                    $recado->geral = false;
                    $recado->turma_id = $request->input('turma');
                    $recado->aluno_id = NULL;
                    $recado->save();
                    return back();
                } else{
                    $recado->titulo = $request->input('titulo');
                    $recado->descricao = $request->input('descricao');
                    $recado->geral = false;
                    $recado->aluno_id = $request->input('aluno');
                    $recado->turma_id = NULL;
                    $recado->save();
                    return back();
                }
            }
        }
        return back();
    }

    public function apagarRecado($id)
    {
        $recado = Recado::find($id);
        if(isset($recado)){
            $recado->delete();
        }
        return back();
    }

    //ATIVIDADES EXTRAS
    public function painelAEs($ano, $n, $bim){
        $validador = AtividadeExtra::where('numero', "$n")->where('bimestre',"$bim")->where('ano',"$ano")->count();
        if($validador==0){
            return back()->with('mensagem', 'Os campos para anexar as AEs não foram gerados, por favor gerar!');
        } else {
            $fundTurmas = Turma::select('serie')->where('ativo',true)->where('ensino','fund')->groupby('serie')->get();
            $medioTurmas = Turma::select('serie')->where('ativo',true)->where('ensino','medio')->groupby('serie')->get();
            $fundDiscs = Disciplina::where('ativo',true)->where('ensino','fund')->get();
            $medioDiscs = Disciplina::where('ativo',true)->where('ensino','medio')->get();
            $aeFunds = AtividadeExtra::orderBy('disciplina_id')->where('numero', "$n")->where('bimestre',"$bim")->where('ensino','fund')->get();
            $aeMedios = AtividadeExtra::orderBy('disciplina_id')->where('numero', "$n")->where('bimestre',"$bim")->where('ensino','medio')->get();
            return view('admin.atividade_extra',compact('fundTurmas','medioTurmas','fundDiscs','medioDiscs','aeFunds','aeMedios'));
        }
    }

    public function gerarAEs(Request $request){
        $bimestre = $request->input('bimestre');
        $qtd = $request->input('qtd');
        $discs = Disciplina::where('ativo',true)->get();
        $ano = date("Y");
        $turmas = Turma::distinct('turma')->get();
        for($i=1; $i<=$qtd; $i++){
                foreach($turmas as $turma){
                    $serie = $turma->serie;
                    $ensino = $turma->ensino;
                    foreach($discs as $disc){
                        if($disc->ensino=="fund" && $ensino=="fund"){
                            $validador = AtividadeExtra::where('numero',"$i")->where('bimestre', "$bimestre")->where('ano', "$ano")->where('serie', "$serie")->where('ensino', 'fund')->where('disciplina_id', "$disc->id")->count();
                            if($validador == 0){
                                $ae = new AtividadeExtra();
                                $ae->numero = $i;
                                $ae->bimestre = $bimestre;
                                $ae->ano = $ano;
                                $ae->serie = $serie;
                                $ae->ensino = "fund";
                                $ae->disciplina_id = $disc->id;
                                $ae->save();
                            }
                        } else if($disc->ensino=="medio" && $ensino=="medio"){
                            $validador = AtividadeExtra::where('numero',"$i")->where('bimestre', "$bimestre")->where('ano', "$ano")->where('serie', "$serie")->where('ensino', 'medio')->where('disciplina_id', "$disc->id")->count();
                            if($validador == 0){
                                $ae = new AtividadeExtra();
                                $ae->numero = $i;
                                $ae->bimestre = $bimestre;
                                $ae->ano = $ano;
                                $ae->serie = $serie;
                                $ae->ensino = "medio";
                                $ae->disciplina_id = $disc->id;
                                $ae->save();
                            }
                        }
                    }
                }
        }
        return back()->with('mensagem', 'AEs geradas com sucesso!');
    }

    public function anexarAE(Request $request, $id)
    {
        $path = $request->file('arquivo')->store('aes','public');
        $ae = AtividadeExtra::find($id);
        if($ae->arquivo=="" || $ae->arquivo==null){
            $ae->arquivo = $path;
            $ae->save();
        } else {
            $arquivo = $ae->arquivo;
            Storage::disk('public')->delete($arquivo);
            $ae->arquivo = $path;
            $ae->save();
        }
        return back();
    }

    public function downloadAE($id)
    {
        $ae = AtividadeExtra::find($id);
        $discId = $ae->disciplina_id;
        $disciplina = Disciplina::find($discId);
        $nameFile = "";
        switch ($ae->serie) {
                case 6: $nameFile = "6º - AE 0".$ae->numero." ".$ae->bimestre."º Bim - ".$disciplina->nome; break;
                case 7: $nameFile = "7º - AE 0".$ae->numero." ".$ae->bimestre."º Bim - ".$disciplina->nome; break;
                case 8: $nameFile = "8º - AE 0".$ae->numero." ".$ae->bimestre."º Bim - ".$disciplina->nome; break;
                case 9: $nameFile = "9º - AE 0".$ae->numero." ".$ae->bimestre."º Bim - ".$disciplina->nome; break;
                case 1: $nameFile = "1º - AE 0".$ae->numero." ".$ae->bimestre."º Bim - ".$disciplina->nome; break;
                case 2: $nameFile = "2º - AE 0".$ae->numero." ".$ae->bimestre."º Bim - ".$disciplina->nome; break;
                case 3: $nameFile = "3º - AE 0".$ae->numero." ".$ae->bimestre."º Bim - ".$disciplina->nome; break;
                default: $nameFile = "";
        };
        if(isset($ae)){
            $path = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($ae->arquivo);
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $name = $nameFile.".".$extension;
            return response()->download($path, $name);
        }
        return back();
    }

    public function apagarAE($id){
        $ae = AtividadeExtra::find($id);
        $arquivo = $ae->arquivo;
        Storage::disk('public')->delete($arquivo);
        $ae->arquivo = "";
        $ae->save();
        return back();
    }
}
