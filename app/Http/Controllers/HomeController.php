<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\HomeEvent;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        event (new HomeEvent("Hello"));
        return view('home_usuario');
    }
}
