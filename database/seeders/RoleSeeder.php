<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Role;
use App\Models\Cliente;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cliente = Cliente::where('fantasia', 'Sentric')->firstOrFail();
        $cliente_id = $cliente->id;

        Role::create([
            'id' =>  Str::uuid()->toString(),
            'cliente_id'=> $cliente_id,
            'nome'=>'Criador',
            'cor'=>'#7367f0',
            'descricao'=>'Criador do Sistema'
        ]);
        Role::create([
            'id' =>  Str::uuid()->toString(),
            'cliente_id'=> $cliente_id,
            'nome'=>'Administrador',
            'cor'=>'#00cfe8',
            'descricao'=>'Usuário com privilegios administrativos'
        ]);
    }
}
