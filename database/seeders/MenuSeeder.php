<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Cliente;
use App\Models\App;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cliente = Cliente::where('razaosocial', 'Sentric Soluções Inteligentes')->first();
        
        $superAdminMenu = Menu::create([
            'id' => Str::uuid()->toString(),
            'cliente_id' => $cliente->id,
            'app_id' => null,
            'nome' => 'Super Administrador',
            'rota' => null,
            'icone' => "<i class='bi bi-code-square'></i>",
            'parentId' => null,
            'posicao' => 0,
            'acao' => null
        ]);

        $appClientes = App::where('nome', 'Road Map')->first();
        $clienteMenu = Menu::create([
            'id' => Str::uuid()->toString(),
            'cliente_id' => $cliente->id,
            'app_id' => $appClientes->id,
            'nome' => 'RoadMap',
            'rota' => 'superadmin/roadmap',
            'icone' => null,
            'parentId' => $superAdminMenu->id,
            'posicao' => 0,
            'acao' => 'roadmap.roadmap'
        ]);

        $appClientes = App::where('nome', 'Clientes')->first();
        $clientesMenu = Menu::create([
            'id' => Str::uuid()->toString(),
            'cliente_id' => $cliente->id,
            'app_id' => $appClientes->id,
            'nome' => 'Clientes',
            'rota' => 'superadmin/clientes',
            'icone' => null,
            'parentId' => $superAdminMenu->id,
            'posicao' => 1,
            'acao' => 'clientes.clientes'
        ]);

        $appClientes = App::where('nome', 'Usuários')->first();
        $usuariosMenu = Menu::create([
            'id' => Str::uuid()->toString(),
            'cliente_id' => $cliente->id,
            'app_id' => $appClientes->id,
            'nome' => 'Usuários',
            'rota' => 'superadmin/usuarios',
            'icone' => null,
            'parentId' => $superAdminMenu->id,
            'posicao' => 3,
            'acao' => 'usuarios.usuarios'
        ]);

        $appClientes = App::where('nome', 'Classes de Usuários')->first();
        $classesUsuariosMenu = Menu::create([
            'id' => Str::uuid()->toString(),
            'cliente_id' => $cliente->id,
            'app_id' => $appClientes->id,
            'nome' => 'Classes de Usuários',
            'rota' => 'superadmin/roles',
            'icone' => null,
            'parentId' => $superAdminMenu->id,
            'posicao' => 2,
            'acao' => 'roles.roles'
        ]);

        $appClientes = App::where('nome', 'Aplicativos')->first();
        $appsMenu = Menu::create([
            'id' => Str::uuid()->toString(),
            'cliente_id' => $cliente->id,
            'app_id' => $appClientes->id,
            'nome' => 'Aplicativos',
            'rota' => 'superadmin/apps',
            'icone' => null,
            'parentId' => $superAdminMenu->id,
            'posicao' => 4,
            'acao' => 'apps.apps'
        ]);

        $appClientes = App::where('nome', 'Habilidades')->first();
        $habilidadesMenu = Menu::create([
            'id' => Str::uuid()->toString(),
            'cliente_id' => $cliente->id,
            'app_id' => $appClientes->id,
            'nome' => 'Habilidades',
            'rota' => 'superadmin/permissions',
            'icone' => null,
            'parentId' => $superAdminMenu->id,
            'posicao' => 5,
            'acao' => 'permissions.permissions'
        ]);

        $appClientes = App::where('nome', 'Habilidades de Classes')->first();
        $classesHabilidadesMenu = Menu::create([
            'id' => Str::uuid()->toString(),
            'cliente_id' => $cliente->id,
            'app_id' => $appClientes->id,
            'nome' => 'Habilidades das Classes',
            'rota' => 'superadmin/rolepermission',
            'icone' => null,
            'parentId' => $superAdminMenu->id,
            'posicao' => 7,
            'acao' => 'classesdehabilidades.classesdehabilidades'
        ]);

        $appClientes = App::where('nome', 'Habilidades de Usuários')->first();
        $habilidadesUsuariosMenu = Menu::create([
            'id' => Str::uuid()->toString(),
            'cliente_id' => $cliente->id,
            'app_id' => $appClientes->id,
            'nome' => 'Habilidades dos Usuários',
            'rota' => 'superadmin/habilidadedosusuarios',
            'icone' => null,
            'parentId' => $superAdminMenu->id,
            'posicao' => 6,
            'acao' => 'habilidadesdeusuarios.habilidadesdeusuarios'
        ]);

        $appClientes = App::where('nome', 'Pacote do Cliente')->first();
        $pacotesClientesMenu = Menu::create([
            'id' => Str::uuid()->toString(),
            'cliente_id' => $cliente->id,
            'app_id' => $appClientes->id,
            'nome' => 'Pacotes dos Clientes',            
            'rota' => 'superadmin/pacotedocliente',
            'icone' => null,
            'parentId' => $superAdminMenu->id,
            'posicao' => 8,
            'acao' => 'pacotesdosclientes.pacotesdosclientes'
        ]);

        $appClientes = App::where('nome', 'Menu do Cliente')->first();
        $menuMenu = Menu::create([
            'id' => Str::uuid()->toString(),
            'cliente_id' => $cliente->id,
            'app_id' => $appClientes->id,
            'nome' => 'Menu do Cliente',            
            'rota' => 'superadmin/menu',
            'icone' => null,
            'parentId' => $superAdminMenu->id,
            'posicao' => 9,
            'acao' => 'menus.menus'
        ]);

        $appClientes = App::where('modelo', 'App\Http\Livewire\LWHistorico')->first();
        $menuMenu = Menu::create([
            'id' => Str::uuid()->toString(),
            'cliente_id' => $cliente->id,
            'app_id' => $appClientes->id,
            'nome' => 'Histórico de Acessos e Ações',            
            'rota' => 'superadmin/historico',
            'icone' => null,
            'parentId' => $superAdminMenu->id,
            'posicao' => 10,
            'acao' => 'historicos.historicos'
        ]);

        $ferramentasMenu = Menu::create([
            'id' => Str::uuid()->toString(),
            'cliente_id' => $cliente->id,
            'app_id' => null,
            'nome' => 'Ferramentas',            
            'rota' => null,
            'icone' => "<i class='bi bi-tools'></i>",
            'parentId' => null,
            'posicao' => 1,
            'acao' => null
        ]);

        $colecaoAppsMenu = Menu::create([
            'id' => Str::uuid()->toString(),
            'cliente_id' => $cliente->id,
            'app_id' => $appClientes->id,
            'nome' => 'Coleção de Icones',            
            'rota' => 'ferramentas/icones',
            'icone' => null,
            'parentId' => $ferramentasMenu->id,
            'posicao' => 0,
            'acao' => 'ferramentas.icones'
        ]);

        $ADMMenu = Menu::create([
            'id' => Str::uuid()->toString(),
            'cliente_id' => $cliente->id,
            'app_id' => null,
            'nome' => 'Administrador',            
            'rota' => null,
            'icone' => "<i class='bi bi-brightness-high-fill'></i>",
            'parentId' => null,
            'posicao' => 2,
            'acao' => null
        ]);

        $appadm = App::where('modelo', 'App\Http\Livewire\LWADMRoles')->first();
        $colecaoAppsMenu = Menu::create([
            'id' => Str::uuid()->toString(),
            'cliente_id' => $cliente->id,
            'app_id' => $appadm->id,
            'nome' => 'Classes de Usuários',            
            'rota' => 'administrador/roles',
            'icone' => null,
            'parentId' => $ADMMenu->id,
            'posicao' => 0,
            'acao' => 'administrador.roles'
        ]);

        $appadm = App::where('modelo', 'App\Http\Livewire\Adm\LWAdmUsuario')->first();
        $colecaoAppsMenu = Menu::create([
            'id' => Str::uuid()->toString(),
            'cliente_id' => $cliente->id,
            'app_id' => $appadm->id,
            'nome' => 'Usuários',            
            'rota' => 'administrador/usuários',
            'icone' => null,
            'parentId' => $ADMMenu->id,
            'posicao' => 1,
            'acao' => 'administrador.users'
        ]);

        $appadm = App::where('modelo', 'App\Http\Livewire\LWADMRolespermissions')->first();
        $colecaoAppsMenu = Menu::create([
            'id' => Str::uuid()->toString(),
            'cliente_id' => $cliente->id,
            'app_id' => $appadm->id,
            'nome' => 'Habilidades da Classe do Usuario',            
            'rota' => 'administrador/habilidadesdeclasses',
            'icone' => null,
            'parentId' => $ADMMenu->id,
            'posicao' => 2,
            'acao' => 'administrador.rolespermissions'
        ]);

        $appadm = App::where('modelo', 'App\Http\Livewire\LWADMUserspermissions')->first();
        $colecaoAppsMenu = Menu::create([
            'id' => Str::uuid()->toString(),
            'cliente_id' => $cliente->id,
            'app_id' => $appadm->id,
            'nome' => 'Habilidades do Usuário',            
            'rota' => 'administrador/habilidadesdeusuarios',
            'icone' => null,
            'parentId' => $ADMMenu->id,
            'posicao' => 3,
            'acao' => 'administrador.userspermissions'
        ]);

        $appadm = App::where('modelo', 'App\Http\Livewire\LWADMHistoricos')->first();
        $colecaoAppsMenu = Menu::create([
            'id' => Str::uuid()->toString(),
            'cliente_id' => $cliente->id,
            'app_id' => $appadm->id,
            'nome' => 'Histórico de Acessos e Ações',            
            'rota' => 'administrador/historico',
            'icone' => null,
            'parentId' => $ADMMenu->id,
            'posicao' => 4,
            'acao' => 'administrador.historico'
        ]);

    }
}
