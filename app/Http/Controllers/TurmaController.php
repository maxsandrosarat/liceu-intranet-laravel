<?php

namespace App\Http\Controllers;

use App\Turma;
use Illuminate\Http\Request;

class TurmaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    public function index()
    {
        $turmas = Turma::all();
        return view('admin.turmas',compact('turmas'));
    }

    public function store(Request $request)
    {
        $turma = new Turma();
        $turma->serie = $request->input('serie');
        $turma->turma = $request->input('turma');
        $turma->turno = $request->input('turno');
        $turma->ensino = $request->input('ensino');
        $turma->save();
        return redirect('/turmas');
    }

    public function destroy($id)
    {
        $turma = Turma::find($id);
        if(isset($turma)){
            $turma->delete();
        }
        return redirect('/turmas');
    }
}
