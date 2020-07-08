<?php

namespace App\Http\Controllers\Auth;

use App\Aluno;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AlunoLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:aluno');
    }

    public function login(Request $request){
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        
        $ativo = false;
        $alunos = DB::table('alunos')->select(DB::raw("ativo"))->where('email', "$request->email")->get();
        foreach($alunos as $aluno){
            $ativo = $aluno->ativo;
        }

        if($ativo==true){
            $credentials = [
                'email' => $request->email,
                'password' => $request->password
            ];

            $authOK = Auth::guard('aluno')->attempt($credentials, $request->remember);
                if($authOK) {

                    return redirect()->intended(route('aluno.dashboard'));

                }

            return redirect()->back()->withInput($request->only('email','remember'))->with('mensagem', 'Os dados informados estão incorretos, verifique e tente novamente!');
        } else {
            return back()->with('mensagem', 'Usuário não encontrado ou inativo!');
        }
    }

    public function index(){
        return view('auth.aluno-login');
    }
}
