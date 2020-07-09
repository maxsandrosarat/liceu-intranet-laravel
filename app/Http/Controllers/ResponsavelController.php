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
    public function __construct()
    {
        $this->middleware('auth:responsavel');
    }
    
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
        $alunos = ResponsavelAluno::where('responsavel_id',"$respId")->get();
        $alunosIds = array();
        $turmasIds = array();
        foreach($alunos as $aluno){
            $alunosIds[] = $aluno->aluno_id;
            $alunoVer = Aluno::find($aluno->aluno_id);
            $turmasIds[] = $alunoVer->turma_id;
        }
        $recados = Recado::orWhereIn('aluno_id', $alunosIds)->orWhereIn('turma_id', $turmasIds)->orWhere('geral',true)->orderBy('created_at', 'desc')->paginate(10);
        return view('responsavel.recados', compact('recados'));
    }
}