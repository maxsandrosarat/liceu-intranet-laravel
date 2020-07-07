<?php

namespace App\Http\Controllers;

use App\Aluno;
use App\Ocorrencia;
use App\Recado;
use App\Responsavel;
use App\ResponsavelAluno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ResponsavelController extends Controller
{
    public function index(){
        $respId = Auth::user()->id;
        $alunos = ResponsavelAluno::where('responsavel_id',"$respId")->get();
        $alunosIds = array();
        foreach($alunos as $aluno){
            $alunosIds[] = $aluno->aluno_id;
        }
        $ocorrencias = Ocorrencia::whereIn('aluno_id', $alunosIds)->where('aprovado',true)->where('responsavel_ciente',false)->count();
        return view('responsavel.home_responsavel', compact('ocorrencias'));
    }

    //ADMIN
    public function consulta()
    {
        $resps = Responsavel::with('alunos')->orderBy('name')->paginate(10);
        $alunos = Aluno::orderBy('name')->get();
        return view('admin.responsavel', compact('resps','alunos'));
    }

    //ADMIN
    public function novo(Request $request)
    {
        $resp = new Responsavel();
        $resp->name = $request->input('name');
        $resp->email = $request->input('email');
        $resp->password = Hash::make($request->input('password'));
        $resp->save();
        return back();
    }

    //ADMIN
    public function filtro(Request $request)
    {
        $nome = $request->input('nome');
        if(isset($nome)){
            $resps = Responsavel::where('name','like',"%$nome%")->orderBy('name')->get();
        } else {
            return back();
        }
        return view('admin.responsavel', compact('resps'));
    }

    //ADMIN
    public function editar(Request $request, $id)
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

    //ADMIN
    public function apagar($id)
    {
        $resp = Responsavel::find($id);
        if(isset($resp)){
            $resp->delete();
        }
        return back();
    }

    //ADMIN
    public function vincular(Request $request, $id)
    {
        $respAluno = new ResponsavelAluno();
        $respAluno->responsavel_id = $id;
        $respAluno->aluno_id = $request->input('aluno');
        $respAluno->save();
        
        return back();
    }

    public function desvincular($resp_id, $aluno_id)
    {
        ResponsavelAluno::where('responsavel_id',"$resp_id")->where('aluno_id',"$aluno_id")->delete();
        return back();
    }

    public function ocorrencias(){
        $respId = Auth::user()->id;
        $alunos = ResponsavelAluno::where('responsavel_id',"$respId")->get();
        $alunosIds = array();
        foreach($alunos as $aluno){
            $alunosIds[] = $aluno->aluno_id;
        }
        $ocorrencias = Ocorrencia::whereIn('aluno_id', $alunosIds)->where('aprovado',true)->orderBy('created_at', 'desc')->paginate(10);
        return view('responsavel.ocorrencias', compact('ocorrencias'));
    }

    public function cienteOcorrencia($id){
        $ocorrencia = Ocorrencia::find($id);
        $ocorrencia->responsavel_ciente = true;
        $ocorrencia->save();
        return back();
    }

    public function recados(){
        $respId = Auth::user()->id;
        $responsaveis = ResponsavelAluno::with(['aluno', 'aluno.turma'])->where('responsavel_id',$respId)->first();
        $alunos = $responsaveis->aluno->pluck('id');
        $turmas = $responsaveis->aluno->pluck('turma_id');

        $recados = Recado::orWhereIn('aluno_id', $alunos)->orWhereIn('turma_id', $turmas)->orWhere('geral',true)->orderBy('created_at', 'desc')->paginate(10);

        return view('responsavel.recados', compact('recados'));
    }
}
