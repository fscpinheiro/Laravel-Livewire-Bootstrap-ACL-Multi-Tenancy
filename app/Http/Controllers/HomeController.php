<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\App;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $homeApp = App::where('nome', 'Home')->first();
        save_activity($homeApp->id, now(), request()->ip(), 'abrir');
        return view('home');
    }

    public function perfil(){
        $perfilApp = App::where('nome', 'Perfil')->first();
        save_activity($perfilApp->id, now(), request()->ip(), 'abrir');
        return view('perfil.meuperfil');
    }
}
