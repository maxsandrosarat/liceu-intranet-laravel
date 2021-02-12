<?php

namespace App\Http\Controllers;

use App\Aluno;
use App\AnexoPlanejamento;
use App\Atividade;
use App\AtividadeRetorno;
use App\Conteudo;
use App\Diario;
use App\Disciplina;
use App\La;
use App\ListaAtividade;
use App\ProfDisciplina;
use App\Turma;
use App\TurmaDisciplina;
use Illuminate\Http\Request;
use App\Ocorrencia;
use App\Planejamento;
use App\Questao;
use App\Simulado;
use App\TipoOcorrencia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProfController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:prof');
    }
    
    //HOME
    public function index(){
        return view('profs.home_prof');
    }

    //ATIVIDADES
    public function disciplinasAtividades(){
        $profId = Auth::user()->id;
        $profDiscs = ProfDisciplina::where('prof_id',"$profId")->get();
        return view('profs.atividade_disciplinas', compact('profDiscs'));
    }

    public function painelAtividades($discId){
        $profId = Auth::user()->id;
        $disciplina = Disciplina::find($discId);
        $turmas = TurmaDisciplina::where('disciplina_id',"$discId")->get();
        $atividades = Atividade::where('prof_id',"$profId")->where('disciplina_id',"$discId")->orderBy('id','desc')->paginate(10);
        $view = "inicial";
        return view('profs.atividade_prof', compact('view','disciplina','turmas','atividades'));
    }

    public function novaAtividade(Request $request)
    {
        $profId = Auth::user()->id;
        $discId = $request->input('disciplina');
        $path = $request->file('arquivo')->store('atividades','public');
        $atividade = new Atividade();
        $atividade->prof_id = $profId;
        $atividade->disciplina_id = $discId;
        $atividade->turma_id = $request->input('turma');
        $atividade->retorno = $request->input('retorno');
        if($request->input('dataPublicacao')!=""){
            $atividade->data_publicacao = $request->input('dataPublicacao').' '.$request->input('horaPublicacao');
        }
        if($request->input('dataRemocao')!=""){
            $atividade->data_remocao = $request->input('dataRemocao').' '.$request->input('horaRemocao');
        }
        if($request->input('dataEntrega')!=""){
            $atividade->data_entrega = $request->input('dataEntrega').' '.$request->input('horaEntrega');
        }
        $atividade->descricao = $request->input('descricao');
        $atividade->link = $request->input('link');
        $atividade->visualizacoes = 0;
        $atividade->usuario = Auth::user()->name;
        $atividade->arquivo = $path;
        $atividade->save();
        
        return back()->with('success', 'Atividade cadastrada com Sucesso!');
    }

    public function filtroAtividades(Request $request, $discId)
    {
        $profId = Auth::user()->id;
        $disciplina = Disciplina::find($discId);
        $turma = $request->input('turma');
        $descricao = $request->input('descricao');
        $data = $request->input('data');
        if(isset($turma)){
            if(isset($descricao)){
                if(isset($data)){
                    $atividades = Atividade::where('prof_id',"$profId")->where('descricao','like',"%$descricao%")->where('turma_id',"$turma")->where('data_criacao',"$data")->orderBy('id','desc')->paginate(50);
                } else {
                    $atividades = Atividade::where('prof_id',"$profId")->where('descricao','like',"%$descricao%")->where('turma_id',"$turma")->orderBy('id','desc')->paginate(50);
                }
            } else {
                $atividades = Atividade::where('prof_id',"$profId")->where('turma_id',"$turma")->orderBy('id','desc')->paginate(50);
            }
        } else {
            if(isset($descricao)){
                if(isset($data)){
                    $atividades = Atividade::where('prof_id',"$profId")->where('descricao','like',"%$descricao%")->where('data_criacao',"$data")->orderBy('id','desc')->paginate(50);
                } else {
                    $atividades = Atividade::where('prof_id',"$profId")->where('descricao','like',"%$descricao%")->orderBy('id','desc')->paginate(50);
                }
            } else {
                if(isset($data)){
                    $atividades = Atividade::where('prof_id',"$profId")->where('data_criacao',"$data")->orderBy('id','desc')->paginate(50);
                } else {
                    $atividades = Atividade::where('prof_id',"$profId")->orderBy('id','desc')->paginate(50);
                }
            }
        }
        $turmas = TurmaDisciplina::where('disciplina_id',"$discId")->get();
        $view = "filtro";
        return view('profs.atividade_prof', compact('view','disciplina','turmas','atividades'));
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
            $atividade->data_publicacao = $request->input('dataPublicacao').' '.$request->input('horaPublicacao');
        }
        if($request->input('dataRemocao')!=""){
            $atividade->data_remocao = $request->input('dataRemocao').' '.$request->input('horaRemocao');
        }
        if($request->input('dataEntrega')!=""){
            $atividade->data_entrega = $request->input('dataEntrega').' '.$request->input('horaEntrega');
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
        if($request->input('retorno')!=""){
        $atividade->retorno = $request->input('retorno');
        }
        $atividade->save();
        
        return back()->with('success', 'Atividade editada com Sucesso!');
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
        return redirect('/prof/atividade');
    }

    public function apagarAtividade($id){
        $atividade = Atividade::find($id);
        $arquivo = $atividade->arquivo;
        Storage::disk('public')->delete($arquivo);
        if(isset($atividade)){
            $atividade->delete();
        }
        
        return back()->with('success', 'Atividade excluída com Sucesso!');
    }

    public function retornos($atividade_id){
        $retornos = AtividadeRetorno::where('atividade_id',"$atividade_id")->get();
        $atividade = Atividade::find($atividade_id);
        $descricao = $atividade->descricao;
        return view('profs.retornos', compact('descricao','retornos'));
    }

    public function downloadRetorno($id)
    {
        $retorno = AtividadeRetorno::find($id);
        $alunoId = $retorno->aluno_id;
        $atividadeId = $retorno->atividade_id;
        $aluno = Aluno::find($alunoId);
        $nomeAluno = $aluno->name;
        $atividade = Atividade::find($atividadeId);
        $descricaoAtividade = $atividade->descricao;
        $turma = Turma::find($atividade->turma_id);
        $nameFile = $turma->serie."º - ".$descricaoAtividade." - ".$nomeAluno;
        if(isset($retorno)){
            $path = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($retorno->arquivo);
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $name = $nameFile.".".$extension;
            return response()->download($path, $name);
        }
        return back();
    }
    
    //LISTA DE ATIVIDADES
    public function indexLAs(Request $request){
        $ano = $request->ano;
        $meses = DB::table('las')->select(DB::raw("mes"))->groupBy('mes')->orderBy('mes')->get();
        $las = La::where('ano',"$ano")->get();
        $anos = DB::table('las')->select(DB::raw("ano"))->groupBy('ano')->get();
        return view('profs.home_las', compact('ano','anos','meses','las'));
    }

    public function indexLAsAno($ano){
        if($ano==""){
            $ano = date("Y");
        }
        $las = La::where('ano',"$ano")->get();
        $meses = DB::table('las')->select(DB::raw("mes"))->groupBy('mes')->orderBy('mes')->get();
        $anos = DB::table('las')->select(DB::raw("ano"))->groupBy('ano')->get();
        return view('profs.home_las',compact('ano','anos','meses','las'));
    }

    public function painelListaAtividades($data){
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
            $turmas = Turma::select('serie')->where('ativo',true)->where('ensino','medio')->groupby('serie')->get();
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
        $profId = Auth::user()->id;
        $profDiscs = ProfDisciplina::where('prof_id',"$profId")->get();
        $fundTurmas = Turma::select('serie')->where('ativo',true)->where('ensino','fund')->groupby('serie')->get();
        $medioTurmas = Turma::select('serie')->where('ativo',true)->where('ensino','medio')->groupby('serie')->get();
        $fundDiscs = Disciplina::where('ativo',true)->where('ensino','fund')->get();
        $medioDiscs = Disciplina::where('ativo',true)->where('ensino','medio')->get();
        $laFunds = ListaAtividade::orderBy('disciplina_id')->where('dia', "$data")->where('ensino','fund')->get();
        $laMedios = ListaAtividade::orderBy('disciplina_id')->where('dia', "$data")->where('ensino','medio')->get();
        return view('profs.lista_atividade',compact('data','profDiscs','fundTurmas','medioTurmas','fundDiscs','medioDiscs','laFunds','laMedios'));
    }

    public function anexarListaAtividade($id, Request $request)
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

    public function downloadListaAtividade($id)
    {
        $la = ListaAtividade::find($id);
        $serie = $la->serie;
        $discId = $la->disciplina_id;
        $disciplina = Disciplina::find($discId);
        $nameFile = $serie."º - LA ".date("d-m-Y", strtotime($la->dia))." - ".$disciplina->nome;
        if(isset($la)){
            $path = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($la->arquivo);
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $name = $nameFile.".".$extension;
            return response()->download($path, $name);
        }
        return back();
    }

    public function apagarListaAtividade($id){
        $la = ListaAtividade::find($id);
        $arquivo = $la->arquivo;
        Storage::disk('public')->delete($arquivo);
        $la->arquivo = "";
        $la->save();
        return back();
    }

    //DIÁRIO
    public function disciplinasDiario(){
        $profId = Auth::user()->id;
        $profDiscs = ProfDisciplina::where('prof_id',"$profId")->get();
        return view('profs.diario_disciplinas', compact('profDiscs'));
    }

    public function turmasDiario($discId){
        $turmaDiscs = TurmaDisciplina::where('disciplina_id',"$discId")->get();
        return view('profs.diario_turmas', compact('turmaDiscs','discId'));
    }

    public function diaDiario($discId, $turmaId){
        $turmaDiscs = TurmaDisciplina::where('disciplina_id',"$discId")->get();
        $disc = Disciplina::find($discId);
        $turma = Turma::find($turmaId);
        $alunos = Aluno::where('ativo',true)->where('turma_id',"$turmaId")->get();
        if(count($alunos)==0){
            return back()->with('mensagem', 'A turma selecionada não possui alunos cadastrados!');
        }
        return view('profs.diario_dia', compact('turmaDiscs','disc','turma'));
    }

    public function indexDiario(Request $request){
        $profId = Auth::user()->id;
        $discId = $request->input('disciplina');
        $turmaId = $request->input('turma');
        $dia = $request->input('data');
        $disciplina = Disciplina::find($discId);
        $turma = Turma::find($turmaId);
        $alunos = Aluno::where('ativo',true)->where('turma_id',"$turmaId")->get();
        if(count($alunos)==0){
            return back()->with('mensagem', 'A turma selecionada não possui alunos cadastrados!');
        }
        $qtdDiario = Diario::where('dia', "$dia")->where('prof_id',"$profId")->where('disciplina_id',"$discId")->where('turma_id',"$turmaId")->count();
        if($qtdDiario==0){
            $diario = new Diario();
            $diario->dia = $dia;
            $diario->turma_id = $turmaId;
            $diario->disciplina_id = $discId;
            $diario->prof_id = $profId;
            $diario->tempo = NULL;
            $diario->outro_tempo = NULL;
            $diario->tema = NULL;
            $diario->conteudo = NULL;
            $diario->referencias = NULL;
            $diario->tipo_tarefa = NULL;
            $diario->tarefa = NULL;
            $diario->entrega_tarefa = NULL;
            $diario->save();
        }
        $diarios = Diario::where('dia', "$dia")->where('prof_id',"$profId")->where('disciplina_id',"$discId")->where('turma_id',"$turmaId")->get();
        $tipos = TipoOcorrencia::where('ativo',true)->orderBy('codigo')->get();
        $alunoIds = array();
        foreach($alunos as $aluno){
            $alunosIds[] = $aluno->id;
        }
        $ocorrencias = Ocorrencia::whereIn('aluno_id', $alunosIds)->where('prof_id',"$profId")->where('disciplina_id',"$discId")->where('data',"$dia")->get();
        return view('profs.diario_prof', compact('dia','alunos','tipos','disciplina','turma','diarios','ocorrencias'));
    }

    public function editarDiario(Request $request)
    {
        $diarioId = $request->input('diario');
        $diario = Diario::find($diarioId);
        if(isset($diario)){
            if($request->input('tempo')!=""){
                $diario->tempo = $request->input('tempo');
            }
            if($request->input('segundoTempo')!=""){
                if($request->input('segundoTempo')==1){
                    $diario->segundo_tempo = true;
                    if($request->input('outroTempo')!=""){
                        $diario->outro_tempo = $request->input('outroTempo');
                    }
                } else {
                    $diario->segundo_tempo = false;
                    $diario->outro_tempo = NULL;
                }
            }
            if($request->input('tema')!=""){
                $diario->tema = $request->input('tema');
            }
            if($request->input('conteudo')!=""){
                $diario->conteudo = $request->input('conteudo');
            }
            if($request->input('referencias')!=""){
                $diario->referencias = $request->input('referencias');
            }
            if($request->input('tipoTarefa')!=""){
                $diario->tipo_tarefa = $request->input('tipoTarefa');
            }
            if($request->input('tarefa')!=""){
                $diario->tarefa = $request->input('tarefa');
            }
            if($request->input('entregaTarefa')!=""){
                $diario->entrega_tarefa = $request->input('entregaTarefa');
            }
            $diario->save();
        }
        return back()->with('mensagem', 'Diário atualizado com Sucesso!');
    }

    public function apagarDiario(Request $request)
    {
        $diarioId = $request->input('ocorrencia');
        $diario = Diario::find($diarioId);
        if(isset($diario)){
            $diario->delete();
        }
        return redirect('/prof/diario/disciplinas')->with('mensagem', 'Diário excluído com Sucesso!');
    }

    //OCORRENCIAS
    public function disciplinasOcorrencias(){
        $profId = Auth::user()->id;
        $profDiscs = ProfDisciplina::where('prof_id',"$profId")->get();
        return view('profs.ocorrencias_disciplinas', compact('profDiscs'));
    }

    public function turmasOcorrencias($disciplina){
        $turmaDiscs = TurmaDisciplina::where('disciplina_id',"$disciplina")->get();
        return view('profs.ocorrencias_turmas', compact('turmaDiscs','disciplina'));
    }

    public function indexOcorrencias($disciplina, $turma){
        $profId = Auth::user()->id;
        $alunos = Aluno::where('ativo',true)->where('turma_id',"$turma")->get();
        $tipos = TipoOcorrencia::where('ativo',true)->orderBy('codigo')->get();
        $ocorrencias = Ocorrencia::where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->paginate(10);
        $view = "inicial";
        return view('profs.ocorrencias_prof', compact('view','alunos','tipos','disciplina','ocorrencias','turma'));
    }

    public function novasOcorrencias(Request $request){
        $profId = Auth::user()->id;
        $alunos = $request->input('alunos');
        $tipo = $request->input('tipo');
        $disciplina = $request->input('disciplina');
        $data = $request->input('data');
        if($request->input('observacao')==""){
            $observacao = "";
        } else {
            $observacao = $request->input('observacao');
        }
        if($request->input('alunos')==""){
            return back();
        } else {
            foreach($alunos as $aluno) {
                $ocorrencia = new Ocorrencia();
                $ocorrencia->aluno_id = $aluno;
                $ocorrencia->tipo_ocorrencia_id = $tipo;
                $ocorrencia->prof_id = $profId;
                $ocorrencia->disciplina_id = $disciplina;
                $ocorrencia->data = $data;
                $ocorrencia->observacao = $observacao;
                $ocorrencia->save();
            }
        }
        return back()->with('mensagem', 'Ocorrência(s) cadastrada(s) com Sucesso!');
    }

    public function filtroOcorrencias(Request $request, $disciplina, $turma)
    {
        $profId = Auth::user()->id;
        $tipo = $request->input('tipo');
        $aluno = $request->input('aluno');
        $dataInicio = $request->input('dataInicio');
        $dataFim = $request->input('dataFim');
        if(isset($tipo)){
            if(isset($aluno)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->where('aluno_id',"$aluno")->whereBetween('data',["$dataInicio", "$dataFim"])->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    } else {
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->where('aluno_id',"$aluno")->whereBetween('data',["$dataInicio", date("Y/m/d")])->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    }
                } else {
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->where('aluno_id',"$aluno")->whereBetween('data',["", "$dataFim"])->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    } else {
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->where('aluno_id',"$aluno")->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->whereBetween('data',["$dataInicio", "$dataFim"])->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    } else {
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->whereBetween('data',["$dataInicio", date("Y/m/d")])->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    }
                } else {
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->whereBetween('data',["", "$dataFim"])->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    } else {
                        $ocorrencias = Ocorrencia::where('tipo_ocorrencia_id',"$tipo")->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    }
                }
            }
        } else {
            if(isset($aluno)){
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::where('aluno_id',"$aluno")->whereBetween('data',["$dataInicio", "$dataFim"])->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    } else {
                        $ocorrencias = Ocorrencia::where('aluno_id',"$aluno")->whereBetween('data',["$dataInicio", date("Y/m/d")])->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    }
                } else {
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::where('aluno_id',"$aluno")->whereBetween('data',["", "$dataFim"])->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    } else {
                        $ocorrencias = Ocorrencia::where('aluno_id',"$aluno")->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    }
                }
            } else {
                if(isset($dataInicio)){
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::whereBetween('data',["$dataInicio", "$dataFim"])->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    } else {
                        $ocorrencias = Ocorrencia::whereBetween('data',["$dataInicio", date("Y/m/d")])->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    }
                } else {
                    if(isset($dataFim)){
                        $ocorrencias = Ocorrencia::whereBetween('data',["", "$dataFim"])->where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    } else {
                        $ocorrencias = Ocorrencia::where('prof_id',"$profId")->where('disciplina_id',"$disciplina")->get();
                    }
                }
            }
        }
        $alunos = Aluno::where('ativo',true)->where('turma_id',"$turma")->get();
        $tipos = TipoOcorrencia::where('ativo',true)->get();
        $view = "filtro";
        return view('profs.ocorrencias_prof', compact('view','alunos','tipos','disciplina','ocorrencias','turma'));
    }

    public function editarOcorrencia(Request $request, $id)
    {
        $ocorrencia = Ocorrencia::find($id);
        if(isset($ocorrencia)){
            if($request->input('tipo')!=""){
                $ocorrencia->tipo_ocorrencia_id = $request->input('tipo');
            }
            if($request->input('data')!=""){
                $ocorrencia->data = $request->input('data');
            }
            if($request->input('observacao')==""){
                $ocorrencia->observacao = "";
            } else { 
                $ocorrencia->observacao = $request->input('observacao');
            }
            $ocorrencia->save();
        }
        return back()->with('mensagem', 'Ocorrência atualizada com Sucesso!');
    }

    public function apagarOcorrencia(Request $request, $id)
    {
        $ocorrenciaId = $request->input('ocorrencia');
        $ocorrencia = Ocorrencia::find($ocorrenciaId);
        if(isset($ocorrencia)){
            $ocorrencia->delete();
        }
        return back()->with('mensagem', 'Ocorrência excluída com Sucesso!');
    }

    //CONTEUDOS
    public function painelConteudos(Request $request){
        $ano = $request->input('ano');
        $anos = DB::table('conteudos')->select(DB::raw("ano"))->groupBy('ano')->get();
        return view('profs.home_conteudos',compact('ano','anos'));
    }

    public function painelConteudosAno($ano){
        if($ano==""){
            $ano = date("Y");
        }
        $anos = DB::table('conteudos')->select(DB::raw("ano"))->groupBy('ano')->get();
        return view('profs.home_conteudos',compact('ano','anos'));
    }

    public function conteudos($ano, $bim, $tipo){
        $validador = Conteudo::where('tipo', "$tipo")->where('bimestre',"$bim")->where('ano',"$ano")->count();
        if($validador==0){
            return back()->with('mensagem', 'Os campos para anexar os conteúdos não foram gerados, solicite ao prof ou aguarde!');
        } else {
            $profId = Auth::user()->id;
            $profDiscs = ProfDisciplina::where('prof_id',"$profId")->get();
            $fundTurmas = Turma::select('serie')->where('ativo',true)->where('ensino','fund')->groupby('serie')->get();
            $medioTurmas = Turma::select('serie')->where('ativo',true)->where('ensino','medio')->groupby('serie')->get();
            $fundDiscs = Disciplina::where('ativo',true)->where('ensino','fund')->get();
            $medioDiscs = Disciplina::where('ativo',true)->where('ensino','medio')->get();
            $contFunds = Conteudo::orderBy('disciplina_id')->where('tipo', "$tipo")->where('bimestre',"$bim")->where('ensino','fund')->where('ano',"$ano")->get();
            $contMedios = Conteudo::orderBy('disciplina_id')->where('tipo', "$tipo")->where('bimestre',"$bim")->where('ensino','medio')->where('ano',"$ano")->get();
            return view('profs.conteudos',compact('profDiscs','tipo','bim','fundTurmas','medioTurmas','fundDiscs','medioDiscs','contFunds','contMedios','ano'));
        }
    }

    public function anexarConteudo(Request $request, $id)
    {
        $path = $request->file('arquivo')->store('conteudos','public');
        $cont = Conteudo::find($id);
        if($cont->arquivo=="" || $cont->arquivo==null){
            $cont->arquivo = $path;
            $cont->update();
        } else {
            $arquivo = $cont->arquivo;
            Storage::disk('public')->delete($arquivo);
            $cont->arquivo = $path;
            $cont->update();
        }
        return back();
    }

    public function downloadConteudo($id)
    {
        $cont = Conteudo::find($id);
        $discId = $cont->disciplina_id;
        $disciplina = Disciplina::find($discId);
        $nameFile = $cont->serie."º - Conteúdo ".$cont->tipo." ".$cont->bimestre."º Bim - ".$disciplina->nome;
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

    //SIMULADOS
    public function indexSimulados(Request $request){
        $profId = Auth::user()->id;
        $profDiscs = ProfDisciplina::where('prof_id',"$profId")->get();
        $turmasIds = array();
        foreach($profDiscs as $profDisc){
            $turmas = TurmaDisciplina::where('disciplina_id',"$profDisc")->get();
            foreach($turmas as $turma){
                $serie = Turma::find($turma->turma_id);
                $turmasIds[] = $serie->serie;
            }
        }
        $simIds = DB::table('questoes')->orWhereIn('serie', $turmasIds)->select(DB::raw("simulado_id"))->groupBy('simulado_id')->get();
        $simuladosIds = array();
        foreach($simIds as $simId){
            $simuladosIds[] = $simId->simulado_id;
        }
        $ano = $request->input('ano');
        $anos = DB::table('simulados')->select(DB::raw("ano"))->groupBy('ano')->get();
        $simulados = Simulado::orWhereIn('id', $simuladosIds)->where('ano',"$ano")->get();
        return view('profs.home_simulados',compact('ano','anos','simulados'));
    }

    public function indexSimuladosAno($ano){
        $profId = Auth::user()->id;
        $profDiscs = ProfDisciplina::where('prof_id',"$profId")->get();
        $turmasIds = array();
        foreach($profDiscs as $profDisc){
            $turmas = TurmaDisciplina::where('disciplina_id',"$profDisc->disciplina_id")->get();
            foreach($turmas as $turma){
                $serie = Turma::find($turma->turma_id);
                $turmasIds[] = $serie->serie;
            }
        }
        $simIds = DB::table('questoes')->orWhereIn('serie', $turmasIds)->select(DB::raw("simulado_id"))->groupBy('simulado_id')->get();
        $simuladosIds = array();
        foreach($simIds as $simId){
            $simuladosIds[] = $simId->simulado_id;
        }
        if($ano==""){
            $ano = date("Y");
        }
        $anos = DB::table('simulados')->select(DB::raw("ano"))->groupBy('ano')->get();
        $simulados = Simulado::orWhereIn('id', $simuladosIds)->where('ano',"$ano")->get();
        return view('profs.home_simulados',compact('ano','anos','simulados'));
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
        $profId = Auth::user()->id;
        $profDiscs = ProfDisciplina::where('prof_id',"$profId")->get();
        $validadorFund = Questao::where('simulado_id', "$simId")->where('ensino','fund')->count();
        if($validadorFund!=0){
            $ensino = "fund";
            $anexosFund = Questao::where('simulado_id',"$simId")->where('ensino','fund')->distinct('disciplina_id')->get();
            $discIdsFund = array();
            foreach($anexosFund as $anexo){
                $discIdsFund[] = $anexo->disciplina_id;
            }
            $fundTurmas = DB::table('questoes')->where('simulado_id', "$simId")->where('ensino','fund')->select(DB::raw("serie"))->groupBy('serie')->get();
            $fundDiscs = Disciplina::orWhereIn('id', $discIdsFund)->where('ativo',true)->with('turmas')->orderBy('nome')->get();
            $contFunds = Questao::where('simulado_id', "$simId")->where('ensino','fund')->orderBy('disciplina_id')->get();
        }
        $validadorMedio = Questao::where('simulado_id', "$simId")->where('ensino','medio')->count();
        if($validadorMedio!=0){
            $ensino = "medio";
            $anexosMed = Questao::where('simulado_id',"$simId")->where('ensino','medio')->distinct('disciplina_id')->get();
            $discIdsMed = array();
            foreach($anexosMed as $anexo){
                $discIdsMed[] = $anexo->disciplina_id;
            }
            $medioTurmas = DB::table('questoes')->where('simulado_id', "$simId")->where('ensino','medio')->select(DB::raw("serie"))->groupBy('serie')->get();
            $medioDiscs = Disciplina::orWhereIn('id', $discIdsMed)->where('ativo',true)->with('turmas')->orderBy('nome')->get();
            $contMedios = Questao::where('simulado_id', "$simId")->where('ensino','medio')->orderBy('disciplina_id')->get();
        }
        if($validadorFund!=0 && $validadorMedio!=0){
            $ensino = "todos";
        }
        return view('profs.simulados',compact('profDiscs','ensino','simulado','ano','fundTurmas','medioTurmas','fundDiscs','medioDiscs','contFunds','contMedios'));
    }

    public function anexarSimulado(Request $request, $id)
    {
        $path = $request->file('arquivo')->store('questoesSimulados','public');
        $cont = Questao::find($id);
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

    public function apagarSimulado($id){
        $cont = Questao::find($id);
        $arquivo = $cont->arquivo;
        Storage::disk('public')->delete($arquivo);
        $cont->arquivo = "";
        $cont->save();
        return back();
    }

    //PLANEJAMENTOS
    public function indexPlanejamentos(Request $request){
        $ano = $request->input('ano');
        $anos = DB::table('planejamentos')->select(DB::raw("ano"))->groupBy('ano')->get();
        $planejamentos = Planejamento::where('ano',"$ano")->get();
        return view('profs.home_planejamentos',compact('ano','anos','planejamentos'));
    }

    public function indexPlanejamentosAno($ano){
        if($ano==""){
            $ano = date("Y");
        }
        $anos = DB::table('planejamentos')->select(DB::raw("ano"))->groupBy('ano')->get();
        $planejamentos = Planejamento::where('ano',"$ano")->get();
        return view('profs.home_planejamentos',compact('ano','anos','planejamentos'));
    }

    public function painelPlanejamentos($simId){
        $planejamento = Planejamento::find($simId);
        $ano = $planejamento->ano;
        $fundTurmas = "";
        $fundDiscs = "";
        $contFunds = "";
        $medioTurmas = "";
        $medioDiscs = "";
        $contMedios = "";
        $ensino = "";
        $validadorFund = 0;
        $validadorMedio = 0;
        $profId = Auth::user()->id;
        $profDiscs = ProfDisciplina::where('prof_id',"$profId")->get();
        $discIds = array();
        foreach($profDiscs as $disc){
            $validador = 0;
            $validador = AnexoPlanejamento::where('planejamento_id',"$simId")->where('disciplina_id',"$disc->disciplina_id")->count();
            if($validador!=0){
                $discIds[] = $disc->disciplina_id;
                $disc = Disciplina::find($disc->disciplina_id);
                if($disc->ensino=="fund"){
                    $validadorFund = 1;
                } else {
                    $validadorMedio = 1;
                }
            }
        }
        if($validadorFund!=0){
            $ensino = "fund";
        }
        if($validadorMedio!=0){
            $ensino = "medio";
        }
        if($validadorFund!=0 && $validadorMedio!=0){
            $ensino = "todos";
        }
        $fundTurmas = DB::table('anexo_planejamentos')->where('planejamento_id', "$simId")->where('ensino','fund')->select(DB::raw("serie"))->groupBy('serie')->get();
        $fundDiscs = Disciplina::orWhereIn('id', $discIds)->where('ativo',true)->where('ensino','fund')->with('turmas')->orderBy('nome')->get();
        $contFunds = AnexoPlanejamento::where('planejamento_id', "$simId")->where('ensino','fund')->orWhereIn('disciplina_id', $discIds)->orderBy('disciplina_id')->get();

        $medioTurmas = DB::table('anexo_planejamentos')->where('planejamento_id', "$simId")->where('ensino','medio')->select(DB::raw("serie"))->groupBy('serie')->get();
        $medioDiscs = Disciplina::orWhereIn('id', $discIds)->where('ativo',true)->where('ensino','medio')->with('turmas')->orderBy('nome')->get();
        $contMedios = AnexoPlanejamento::where('planejamento_id', "$simId")->where('ensino','medio')->orWhereIn('disciplina_id', $discIds)->orderBy('disciplina_id')->get();
        return view('profs.planejamentos',compact('ensino','planejamento','ano','fundTurmas','medioTurmas','fundDiscs','medioDiscs','contFunds','contMedios'));
    }

    public function anexarPlanejamento(Request $request, $id)
    {
        $path = $request->file('arquivo')->store('anexosPlanejamentos','public');
        $cont = AnexoPlanejamento::find($id);
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

    public function downloadPlanejamento($id)
    {
        $cont = AnexoPlanejamento::find($id);
        $discId = $cont->disciplina_id;
        $disciplina = Disciplina::find($discId);
        $planejamento = Planejamento::find($cont->planejamento_id);
        $nameFile = $cont->serie."º - Planejamento ".$planejamento->descricao." - ".$disciplina->nome;
        if(isset($cont)){
            $path = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($cont->arquivo);
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $name = $nameFile.".".$extension;
            return response()->download($path, $name);
        }
        return back();
    }
}
