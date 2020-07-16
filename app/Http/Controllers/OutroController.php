<?php

namespace App\Http\Controllers;

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
        $this->middleware('auth:outro');
    }
    
    public function index(){
        return view('outros.home_outro');
    }
}
