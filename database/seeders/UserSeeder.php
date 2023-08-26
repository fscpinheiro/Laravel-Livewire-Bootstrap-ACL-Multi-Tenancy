<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\User;
use App\Models\Cliente;
use App\Models\Role;
use Illuminate\Support\Str;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cliente = Cliente::where('documento', '21.123.735/0001-82')->first();
        $cliente_id = $cliente->id;
        $role = Role::where('nome','Criador')->first();
        $role_id = $role->id;

        User::create([
                'id' => Str::uuid()->toString(),
                'name' =>  'Francisco Samir',
                'email' => 'sentric.teste2023@gmail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('TP@#link941'),
                'remember_token' => Str::random(10),
                'cliente_id' =>  $cliente_id,
                'role_id'=> $role_id,
                'situacao' => 1
            ]);

        User::create([
            'id' => Str::uuid()->toString(),
            'name' => 'Renato Florêncio',
            'email' => 'renato@sentric.com',
            'email_verified_at' => now(),
            'password' => bcrypt('S3ntr1c-Soluco3s-1nt3l1g3nt3s'),
            'remember_token' => Str::random(10),
            'cliente_id' =>  $cliente_id,
            'role_id'=> $role_id,
            'situacao' => 1
        ]);
    }
}
