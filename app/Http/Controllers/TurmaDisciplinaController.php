<?php

namespace App\Http\Controllers;

use App\Disciplina;
use App\TurmaDisciplina;
use Illuminate\Http\Request;
use App\Turma;

class TurmaDisciplinaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $turmaDiscs = Turma::with('disciplinas')->get();
        $turmas = Turma::all();
        $discs = Disciplina::all();
        return view('admin.turmas_disciplinas',compact('turmaDiscs','turmas','discs'));
    }

    public function store(Request $request)
    {
        if($request->input('disciplina')=="todasFund"){
            $discFunds = Disciplina::where('ensino','fund')->get();
            foreach($discFunds as $discFund){
                $turmaDisc = new TurmaDisciplina();
                $turmaDisc->turma_id = $request->input('turma');
                $turmaDisc->disciplina_id = $discFund->id;
                $turmaDisc->save();
            }
        } elseif($request->input('disciplina')=="todasMedio"){
            $discs = Disciplina::where('ensino','medio')->get();
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
        return redirect('/turmasDiscs');
    }

    public function destroy($turma_id, $disciplina_id)
    {
        TurmaDisciplina::where('turma_id',"$turma_id")->where('disciplina_id',"$disciplina_id")->delete();
        return redirect('/turmasDiscs');
    }

}
