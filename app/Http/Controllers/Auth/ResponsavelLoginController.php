<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResponsavelLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:responsavel');
    }

    public function login(Request $request){
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        
        $ativo = false;
        $resps = DB::table('responsaveis')->select(DB::raw("ativo"))->where('email', "$request->email")->get();
        foreach($resps as $resp){
            $ativo = $resp->ativo;
        }

        if($ativo==true){
            $credentials = [
                'email' => $request->email,
                'password' => $request->password
            ];

            $authOK = Auth::guard('responsavel')->attempt($credentials, $request->remember);

            if($authOK) {
                return redirect()->intended(route('responsavel.dashboard'));
            }

            return redirect()->back()->withInput($request->only('email','remember'))->with('mensagem', 'Os dados informados estão incorretos, verifique e tente novamente!');
        } else{
            return back()->with('mensagem', 'Usuário não encontrado ou inativo!');
        }
    }

    public function index(){
        return view('auth.responsavel-login');
    }
}
