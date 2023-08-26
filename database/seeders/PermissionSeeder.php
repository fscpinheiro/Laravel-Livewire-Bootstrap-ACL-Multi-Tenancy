<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Str;
use App\Models\App;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apps = App::all();
        $permissions = ['abrir', 'criar', 'consultar', 'editar', 'excluir'];

        foreach ($apps as $app) {
            foreach ($permissions as $permission) {
                Permission::create([
                    'id' => Str::uuid()->toString(),
                    'app_id' => $app->id,
                    'nome' => $permission,
                    'descricao' => ""
                ]);
            }
        }
    }
}
