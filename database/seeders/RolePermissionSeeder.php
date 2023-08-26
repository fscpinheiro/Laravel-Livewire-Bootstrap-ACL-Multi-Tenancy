<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Str;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::where('nome', 'criador')->first();
        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            DB::table('roles_permissions')->insert([
                'role_id' => $role->id,
                'permission_id' => $permission->id
            ]);
        }
    }
}
