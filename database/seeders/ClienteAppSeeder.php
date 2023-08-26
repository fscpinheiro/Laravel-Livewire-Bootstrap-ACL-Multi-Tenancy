<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Cliente;
use App\Models\App;

class ClienteAppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cliente = Cliente::where('razaosocial', 'Sentric Soluções Inteligentes')->first();
        $apps = App::all();
        foreach ($apps as $app) {
            DB::table('clientes_apps')->insert([
                'cliente_id' => $cliente->id,
                'app_id' => $app->id
            ]);
        }
    }
}
