<?php

namespace App\Http\Controllers;

use App\Outro;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Excel;
use DB;

class OutroController extends Controller
{
    public function __construct()
    {
        
    }
    
    public function index(){
        return view('outros.home_outro');
    }

    public function create()
    {
        return view('auth.outro-register');
    }

    public function store(Request $request)
    {
        $outro = new Outro();
        $outro->name = $request->input('name');
        $outro->email = $request->input('email');
        $outro->password = Hash::make($request->input('password'));
        $outro->save();
        return redirect('/outro/consulta');
    }

    public function file(Request $request)
    {
        Excel::import(new \App\Imports\OutroImport, $request->file('arquivo'));
        return back()->with('success', 'Dados importados do Excel com Sucesso!');
    }

    public function consulta()
    {
        $outros = Outro::orderBy('name')->paginate(10);
        return view('admin.outros', compact('outros'));
    }

    public function filtro(Request $request)
    {
        $nome = $request->input('nome');
        if(isset($nome)){
                $outros = Outro::where('name','like',"%$nome%")->orderBy('name')->paginate(10);
        } else {
                return redirect('/outro/consulta');
        }
        return view('outros.outros', compact('outros'));
    }

    public function update(Request $request, $id)
    {
        $outro = Outro::find($id);
        if(isset($outro)){
            $outro->name =$request->input('name');
            $outro->email =$request->input('email');
            if($request->input('password')!=""){
            $outro->password = Hash::make($request->input('password'));
            }
            $outro->save();
        }
        return redirect('/outro/consulta');
    }

    public function destroy($id)
    {
        $outro = Outro::find($id);
        if(isset($outro)){
            $outro->delete();
        }
        return redirect('/outro/consulta');
    }
}
