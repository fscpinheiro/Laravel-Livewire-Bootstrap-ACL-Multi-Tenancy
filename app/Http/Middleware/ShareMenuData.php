<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Menu;

class ShareMenuData
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && !in_array($request->route()->getName(), ['login','welcome'])) {
            $cliente_id = $request->user()->cliente_id;
            $MenuItems = Menu::where('cliente_id', $cliente_id)->orderBy('posicao')->get();
            view()->share('MenuItems', $MenuItems);
        }
        return $next($request);
    }
}
