<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OutroLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:outro');
    }

    public function login(Request $request){
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        
        $ativo = false;
        $outros = DB::table('outros')->select(DB::raw("ativo"))->where('email', "$request->email")->get();
        foreach($outros as $outro){
            $ativo = $outro->ativo;
        }

        if($ativo==true){
            $credentials = [
                'email' => $request->email,
                'password' => $request->password
            ];

            $authOK = Auth::guard('outro')->attempt($credentials, $request->remember);

            if($authOK) {
                return redirect()->intended(route('outro.dashboard'));
            }

            return redirect()->back()->withInput($request->only('email','remember'))->with('mensagem', 'Os dados informados estão incorretos, verifique e tente novamente!');
        } else {
            return back()->with('mensagem', 'Usuário não encontrado ou inativo!');
        }
    }

    public function index(){
        return view('auth.outro-login');
    }
}
