<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        $authOK = Auth::guard('responsavel')->attempt($credentials, $request->remember);

        if($authOK) {
            return redirect()->intended(route('responsavel.dashboard'));
        }

        return redirect()->back()->withInput($request->only('email','remember'));
    }

    public function index(){
        return view('auth.responsavel-login');
    }
}
