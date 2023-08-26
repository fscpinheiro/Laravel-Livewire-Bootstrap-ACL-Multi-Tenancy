<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;
use App\Models\Permission;
use App\Models\Cliente;
use App\Models\Role;
use App\Models\App;

use Illuminate\Support\Facades\Log;

class CUP
{
    //Check User Permission - Abrir Apps
    public function handle(Request $request, Closure $next, $app_id): Response
    {
        $mensagem_erro = 'Você não tem permissão para abrir este aplicativo.';
        $user = Auth::user();
        //DB::enableQueryLog();
        //Log::info('App_id: '.$app_id);
        $permission = Permission::where('app_id', $app_id)
            ->where('nome', 'abrir')
            ->first();
        //$query = DB::getQueryLog()[0];
        //Log::info($query['query']);
        //Log::info($query['bindings']);
        $canConsult = check_permission($app_id, 'consultar');

        if (!$permission) {
        //    Log::info('redirecionando');
            return redirect()->route('home')->with('error', $mensagem_erro);
        }

        $userPermission = $user->permissions()
            ->where('app_id', $app_id)
            ->where('nome', 'abrir')
            ->first();

        if ($userPermission) {
            if ($userPermission->pivot->permitido) {
                save_activity($app_id,now(),request()->ip(), 'abrir');
                view()->share('canConsult',$canConsult);
                return $next($request);
            } else {
                return redirect()->route('home')->with('error', $mensagem_erro);
            }
        } elseif ($user->role->permissions()
            ->where('app_id', $app_id)
            ->where('nome', 'abrir')
            ->exists()) {
            save_activity($app_id,now(),request()->ip(), 'abrir');    
            view()->share('canConsult',$canConsult);
            return $next($request);
        }
        return redirect()->route('home')->with('error', $mensagem_erro);
    }
}
