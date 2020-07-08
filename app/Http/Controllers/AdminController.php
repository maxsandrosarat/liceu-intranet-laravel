<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Admin;
use App\Disciplina;
use App\Turma;
use App\Atividade;
use App\Aluno;
use App\AtividadeExtra;
use App\TipoOcorrencia;
use App\Ocorrencia;
use App\Conteudo;
use App\ListaAtividade;
use App\Recado;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Input\Input;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    public function index(){
        return view('admin.home_admin');
    }

    public function create()
    {
        return view('auth.admin-register');
    }

    public function store(Request $request)
    {
        $adm = new Admin();
        $adm->name = $request->input('name');
        $adm->email = $request->input('email');
        $adm->password = Hash::make($request->input('password'));
        $adm->save();
        return back()->with('mensagem', 'Novo Administrador(a) cadastrado com Sucesso!');
    }
	
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

    public function painel_lista_atividade($data){
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

    public function anexar($id, Request $request)
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

    public function download($id)
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

    public function apagar($id){
        $la = ListaAtividade::find($id);
        $arquivo = $la->arquivo;
        Storage::disk('public')->delete($arquivo);
        $la->arquivo = "";
        $la->save();
        return back();
    }

    //PROF
    public function painelAtividades(){
        $discs = Disciplina::where('ativo',true)->orderBy('nome')->get();
        $turmas = Turma::where('ativo',true)->get();
        $atividades = Atividade::orderBy('id','desc')->paginate(10);
        $tipo = "painel";
        return view('admin.atividade_admin', compact('discs','turmas','atividades','tipo'));
    }

    //PROF
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

    //PROF
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

    //PROF
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

    //PROF
    public function apagarAtividade($id){
        $atividade = Atividade::find($id);
        $arquivo = $atividade->arquivo;
        Storage::disk('public')->delete($arquivo);
        if(isset($atividade)){
            $atividade->delete();
        }
        return redirect('/admin/atividade');
    }

    //PROF
    public function filtro_atividade(Request $request)
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
        $tipo = "filtro";
        return view('admin.atividade_admin', compact('discs','turmas','atividades','tipo'));
    }

    public function tipoOcorrencia()
    {
        $tipos = TipoOcorrencia::where('ativo',true)->get();
        return view('admin.tipo_ocorrencia',compact('tipos'));
    }

    public function tipoOcorrenciaNovo(Request $request)
    {
        $tipo = new TipoOcorrencia();
        $tipo->codigo = $request->input('codigo');
        $tipo->descricao = $request->input('descricao');
        $tipo->tipo = $request->input('tipo');
        $tipo->pontuacao = $request->input('pontuacao');
        $tipo->save();
        return back();
    }

    public function tipoOcorrenciaEdit(Request $request, $id)
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

    public function tipoOcorrenciaDelete($id)
    {
        $tipo = TipoOcorrencia::find($id);
        if(isset($tipo)){
            $tipo->ativo = false;
            $tipo->save();
        }
        return back();
    }

    public function indexOcorrencias(){
        $alunos = Aluno::where('ativo',true)->orderBy('name')->get();
        $tipos = TipoOcorrencia::where('ativo',true)->get();
        $ocorrencias = Ocorrencia::paginate(10);
        $busca = "nao";
        return view('admin.ocorrencias_admin', compact('alunos','tipos','ocorrencias','busca'));
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
        $busca = "sim";
        return view('admin.ocorrencias_admin', compact('alunos','tipos','ocorrencias','busca'));
    }

    public function apagarOcorrencia($id)
    {
        $ocorrencia = Ocorrencia::find($id);
        if(isset($ocorrencia)){
            $ocorrencia->delete();
        }
        return back();
    }

    public function aprovarOcorrencia($id){
        $ocorrencia = Ocorrencia::find($id);
        $ocorrencia->aprovado = true;
        $ocorrencia->save();
        //$resps = ResponsavelAluno::where('aluno_id',"$ocorrencia->aluno_id")->get();
        //if(isset($resps)){
            //foreach($resps as $resp){
               // $responsavel = Responsavel::find($resp->responsavel_id);
              //  $responsavel->ocorrencias += 1;
               // $responsavel->save();
           // }
        //}

        return back();
    }

    public function reprovarOcorrencia($id){
        $ocorrencia = Ocorrencia::find($id);
        $ocorrencia->aprovado = false;
        $ocorrencia->save();

        return back();
    }

    public function index_conteudos_ano(Request $request){
        $ano = $request->input('ano');
        $anos = DB::table('conteudos')->select(DB::raw("ano"))->groupBy('ano')->get();
        return view('admin.home_conteudos',compact('ano','anos'));
    }

    public function index_conteudos($ano){
        if($ano==""){
            $ano = date("Y");
        }
        $anos = DB::table('conteudos')->select(DB::raw("ano"))->groupBy('ano')->get();
        return view('admin.home_conteudos',compact('ano','anos'));
    }

    public function painel_conteudo($ano, $bim, $tipo){
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

    public function gerar_conteudo(Request $request){
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

    public function anexar_conteudo(Request $request, $id)
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

    public function download_conteudo($id)
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

    public function apagar_conteudo($id){
        $cont = Conteudo::find($id);
        $arquivo = $cont->arquivo;
        Storage::disk('public')->delete($arquivo);
        $cont->arquivo = "";
        $cont->save();
        return back();
    }

    public function painel_ae($ano, $n, $bim){
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

    public function gerar_ae(Request $request){
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

    public function anexar_ae(Request $request, $id)
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

    public function download_ae($id)
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

    public function apagar_ae($id){
        $ae = AtividadeExtra::find($id);
        $arquivo = $ae->arquivo;
        Storage::disk('public')->delete($arquivo);
        $ae->arquivo = "";
        $ae->save();
        return back();
    }

    public function indexRecados(){
        $recados = Recado::with(['turma', 'aluno'])->paginate(10);
        $turmas = Turma::where('ativo',true)->get();
        $alunos = Aluno::where('ativo',true)->orderBy('name')->get();
        $busca = "nao";
        return view('admin.recados_admin', compact('recados','turmas','alunos','busca'));
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
        $busca = "sim";
        return view('admin.recados_admin', compact('recados','turmas','alunos','busca'));
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

}
