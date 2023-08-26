<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Str;
use App\Models\Cliente;
use Database\Factories\ClienteFactory;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $razao_social = 'Sentric Soluções Inteligentes';

        Cliente::create([
            'id' => Str::uuid()->toString(),
            'razaosocial' => $razao_social,
            'fantasia' => 'Sentric',
            'slugname' => Str::slug($razao_social),
            'documento' => '21.123.735/0001-82',
            'situacao' => '1', //1-ATIVO 0-CANCELADO 2-SUSPENSO
            'logo' => '',
            'tpcliente' => 'PJ',
            
        ]);

        \App\Models\Cliente::factory()->count(2)->create();
    }
}
