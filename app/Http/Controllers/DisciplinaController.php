<?php

namespace App\Http\Controllers;

use App\Disciplina;
use App\ProfDisciplina;
use App\TurmaDisciplina;
use Illuminate\Http\Request;

class DisciplinaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    public function index()
    {
        $discs = Disciplina::where('ativo',true)->get();
        return view('admin.disciplinas',compact('discs'));
    }

    public function store(Request $request)
    {
        $disc = new Disciplina();
        $disc->nome = $request->input('nome');
        $disc->ensino = $request->input('ensino');
        $disc->save();
        return redirect('/disciplinas');
    }

    public function destroy($id)
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

}
