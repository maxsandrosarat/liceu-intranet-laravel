<?php

namespace App\Http\Controllers;

use App\Aluno;
use App\Turma;
use App\Atividade;
use App\AtividadeRetorno;
use App\Disciplina;
use App\Prof;
use App\ProfDisciplina;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AlunoController extends Controller
{
    public function __construct()
    {
        
    }
    
    public function index(){
        return view('alunos.home_aluno');
    }

    public function disciplinas(){
        $profs = ProfDisciplina::all();
        $turmaId = Auth::user()->turma_id;
        $turma = Turma::find($turmaId);
        $turmaDiscs = Turma::with('disciplinas')->where('id',"$turmaId")->get();
        return view('alunos.disciplinas', compact('profs','turma','turmaDiscs'));
    }

    public function painelAtividades($disciplina){
        $alunoId = Auth::user()->id;
        $retornos = AtividadeRetorno::where('aluno_id',"$alunoId")->get();
        $dataAtual = date("Y-m-d");
        $turmaId = Auth::user()->turma_id;
        $atividades = Atividade::where('turma_id',"$turmaId")->where('disciplina_id',"$disciplina")->where("data_publicacao",'<=',"$dataAtual")->where("data_expiracao",'>=',"$dataAtual")->orderBy('id','desc')->paginate(5);
        return view('alunos.atividade_aluno', compact('atividades','retornos'));
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
        $turmas = Turma::all();
        $alunos = Aluno::orderBy('name')->paginate(10);
        return view('admin.alunos', compact('turmas','alunos'));
    }

    public function store(Request $request)
    {
        $aluno = new Aluno();
        $aluno->name = $request->input('name');
        $aluno->email = $request->input('email');
        $aluno->password = Hash::make($request->input('password'));
        $aluno->turma_id = $request->input('turma');
        if($request->file('foto')!=""){
        $path = $request->file('foto')->store('fotos_perfil','public');
        $aluno->foto = $path;
        }
        $aluno->turma_id = $request->input('turma');
        $aluno->save();
        return redirect('/aluno/consulta');
    }

    public function filtro(Request $request)
    {
        $nome = $request->input('nome');
        $turma = $request->input('turma');
        if(isset($nome)){
            if(isset($turma)){
                $alunos = Aluno::where('name','like',"%$nome%")->where('turma_id',"$turma")->orderBy('name')->paginate(50);
            } else {
                $alunos = Aluno::where('name','like',"%$nome%")->orderBy('name')->paginate(50);
            }
        } else {
            if(isset($turma)){
                $alunos = Aluno::where('turma_id',"$turma")->orderBy('name')->paginate(50);
            } else {
                return redirect('/aluno/consulta');
            }
        }
        $pagina = "geral";
        $turmas = Turma::all();
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
        return redirect('/aluno/consulta');
    }

    public function destroy($id)
    {
        $aluno = Aluno::find($id);
        if(isset($aluno)){
            $aluno->delete();
        }
        return redirect('/aluno/consulta');
    }
}
