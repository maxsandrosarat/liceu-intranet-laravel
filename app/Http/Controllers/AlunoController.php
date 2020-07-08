<?php

namespace App\Http\Controllers;

use App\Aluno;
use App\Turma;
use App\Atividade;
use App\AtividadeRetorno;
use App\Conteudo;
use App\Disciplina;
use App\Prof;
use App\ProfDisciplina;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AlunoController extends Controller
{
    public function __construct()
    {
        
    }
    
    public function index(){
        return view('alunos.home_aluno');
    }

    public function disciplinasAtividades(){
        $profs = ProfDisciplina::all();
        $turmaId = Auth::user()->turma_id;
        $turma = Turma::find($turmaId);
        $turmaDiscs = Turma::where('ativo',true)->with('disciplinas')->where('id',"$turmaId")->get();
        return view('alunos.atividades_disciplinas', compact('profs','turma','turmaDiscs'));
    }

    public function painelAtividades($discId){
        $alunoId = Auth::user()->id;
        $retornos = AtividadeRetorno::where('aluno_id',"$alunoId")->get();
        $dataAtual = date("Y-m-d");
        $turmaId = Auth::user()->turma_id;
        $disciplina = Disciplina::find($discId);
        $discNome = $disciplina->nome;
        $atividades = Atividade::where('turma_id',"$turmaId")->where('disciplina_id',"$discId")->where("data_publicacao",'<=',"$dataAtual")->where("data_expiracao",'>=',"$dataAtual")->orderBy('id','desc')->paginate(9);
        return view('alunos.atividade_aluno', compact('discNome','atividades','retornos'));
    }

    public function downloadAtividade($id)
    {
        $atividade = Atividade::find($id);
        $atividade->visualizacoes++;
        $atividade->save();
        $disc = Disciplina::find($atividade->disciplina_id);
        $turma = Turma::find($atividade->turma_id);
        $nameFile = $turma->serie."º - Atividade ".$atividade->descricao." - ".$disc->nome;
        if(isset($atividade)){
            $path = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($atividade->arquivo);
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $name = $nameFile.".".$extension;
            return response()->download($path, $name);
        }
        return redirect('/aluno/atividade');
    }

    public function retornoAtividade(Request $request, $id)
    {
        $alunoId = Auth::user()->id;
        $retorno = new AtividadeRetorno();
        $retorno->atividade_id = $id;
        $retorno->aluno_id = $alunoId;
        $retorno->data_retorno = date("Y/m/d");
        $retorno->comentario = $request->input('comentario');
        $retorno->arquivo = $request->file('arquivo')->store('retornosAtividade','public');
        $retorno->save();
        return back()->with('success', 'Retorno da Atividade enviado com Sucesso!');
    }

    public function editarRetornoAtividade(Request $request, $id)
    {
        $retorno = AtividadeRetorno::find($id);
        $retorno->comentario = $request->input('comentario');
        Storage::disk('public')->delete($retorno->arquivo);
        $retorno->arquivo = $request->file('arquivo')->store('retornosAtividade','public');
        $retorno->save();
        return back()->with('success', 'Retorno da Atividade atualizado com Sucesso!');
    }

    public function file(Request $request)
    {
        Excel::import(new \App\Imports\AlunoImport, $request->file('arquivo'));
        return back()->with('success', 'Dados importados do Excel com Sucesso!');
    }

    public function consulta()
    {
        $turmas = Turma::where('ativo',true)->get();
        $alunos = Aluno::where('ativo',true)->orderBy('name')->paginate(10);
        return view('admin.alunos', compact('turmas','alunos'));
    }

    public function store(Request $request)
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

    public function filtro(Request $request)
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
                return redirect('/aluno/consulta');
            }
        }
        $pagina = "geral";
        $turmas = Turma::where('ativo',true)->get();
        return view('admin.alunos', compact('turmas','alunos'));
    }

    public function update(Request $request, $id)
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

    public function destroy($id)
    {
        $aluno = Aluno::find($id);
        if(isset($aluno)){
            $aluno->ativo = false;
            $aluno->save();
        }
        return back();
    }

    public function painelConteudos($ano){
        if($ano==""){
            $ano = date("Y");
        }
        return view('alunos.home_conteudos',compact('ano'));
    }

    public function conteudos($ano, $bim, $tipo){
        $validador = Conteudo::where('tipo', "$tipo")->where('bimestre',"$bim")->where('ano',"$ano")->count();
        if($validador==0){
            return back()->with('mensagem', 'Os conteúdos para essa atividade ainda não estão disponiveis!');
        } else {
            $turmaId = Auth::user()->turma_id;
            $turma = Turma::find($turmaId);
            $ensino = $turma->ensino;
            $serie = $turma->serie;
            if($ensino=="fund"){
                $fundDiscs = Disciplina::where('ativo',true)->where('ensino','fund')->get();
                $medioDiscs = "";
            } else {
                $fundDiscs = "";
                $medioDiscs = Disciplina::where('ativo',true)->where('ensino','medio')->get();
            }
            if($ensino=="fund"){
                $contFunds = Conteudo::orderBy('disciplina_id')->where('tipo', "$tipo")->where('bimestre',"$bim")->where('ensino','fund')->where('ano',"$ano")->where('serie',"$serie")->get();
                $contMedios = "";
            } else {
                $contFunds = "";
                $contMedios = Conteudo::orderBy('disciplina_id')->where('tipo', "$tipo")->where('bimestre',"$bim")->where('ensino','medio')->where('ano',"$ano")->where('serie',"$serie")->get();
            }
            return view('alunos.conteudos',compact('ensino','serie','tipo','bim','fundDiscs','medioDiscs','contFunds','contMedios','ano'));
        }
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
}
