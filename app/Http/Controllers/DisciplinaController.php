<?php

namespace App\Http\Controllers;

use App\Disciplina;
use Illuminate\Http\Request;

class DisciplinaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    
    public function index()
    {
        $discs = Disciplina::all();
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
            $disc->delete();
        }
        return back();
    }
}
