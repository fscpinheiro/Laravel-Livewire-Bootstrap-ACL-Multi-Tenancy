<?php

use App\Models\Cliente;
use App\Models\User;
use App\Models\App;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent;
use App\Models\Historico;

if (!function_exists('save_activity')) {
    function save_activity($app_id, $datahora, $ip, $acao)
    {

        $user = Auth::user();
        if($user){
            $agent = new Agent();
            $browser = $agent->browser();
            $platform = $agent->platform();
            $historico = new Historico();
            $historico->cliente_id = $user->cliente_id;
            $historico->user_id = $user->id;
            $historico->app_id = $app_id;
            $historico->datahora = $datahora;
            $historico->ip = $ip;
            $historico->acao = $acao;
            $historico->browser = $browser;
            $historico->so = $platform;
            $historico->save();
        }        
    }
}