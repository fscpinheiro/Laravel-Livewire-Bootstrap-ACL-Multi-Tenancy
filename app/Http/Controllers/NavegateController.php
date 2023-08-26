<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\User;
use App\Models\Menu;
use Illuminate\Support\Facades\Log;

class NavegateController extends Controller
{
    public function handleRequest(Request $request)
    {
        $route = $request->route()->uri();
        if (in_array($route, ['login', 'home', 'meuperfil', '/'])) {
            return;
        }
        $menu = Menu::where('rota', $route)->first();
        if ($menu) {
            return view($menu->acao);
        }
    }

    public function inicio(){
        return view('page1');
    }
}
