<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\App;

class AppSeeder extends Seeder
{
    public function run(): void
    {
        App::create([
            'id' => Str::uuid()->toString(),
            'nome' => 'Home',
            'icone' => null,
            'descricao' => 'Pagína inicial',
            'modelo' => '',
        ]);
        App::create([
            'id' => Str::uuid()->toString(),
            'nome' => 'Perfil',
            'icone' => null,
            'descricao' => 'Perfil do Usuário',
            'modelo' => 'App\Http\Livewire\LWPerfil',
        ]);
        App::create([
            'id' => Str::uuid()->toString(),
            'nome' => 'Clientes',
            'icone' => null,
            'descricao' => 'Gestão de Clientes do Sistema.',
            'modelo' => 'App\Http\Livewire\LWCliente',
        ]);
        App::create([
            'id' => Str::uuid()->toString(),
            'nome' => 'Aplicativos',
            'icone' => null,
            'descricao' => 'Gestão de aplicativos disponíveis no sistema.',
            'modelo' => 'App\Http\Livewire\LWApp',
        ]);
        App::create([
            'id' => Str::uuid()->toString(),
            'nome' => 'Usuários',
            'icone' => null,
            'descricao' => 'Gestão dos usuários dos clientes.',
            'modelo' => 'App\Http\Livewire\LWUsuario',
        ]);
        App::create([
            'id' => Str::uuid()->toString(),
            'nome' => 'Classes de Usuários',
            'icone' => null,
            'descricao' => 'Gestão das classes dos usuários dos clientes.',
            'modelo' => 'App\Http\Livewire\LWRole',            
        ]);
        App::create([
            'id' => Str::uuid()->toString(),
            'nome' => 'Habilidades',
            'icone' => null,
            'descricao' => 'Gerenciamento de Habilidades nos aplicativos.',
            'modelo' => 'App\Http\Livewire\LWPermission',
        ]);
        App::create([
            'id' => Str::uuid()->toString(),
            'nome' => 'Habilidades de Classes',
            'icone' => null,
            'descricao' => 'Gerenciamento das Habilidades das Classes de usuários',
            'modelo' => 'App\Http\Livewire\LWClassesHabilidades',
        ]);
        App::create([
            'id' => Str::uuid()->toString(),
            'nome' => 'Habilidades de Usuários',
            'icone' => null,
            'descricao' => 'Gestão das Habilidades dos Usuários nos aplicativos.',
            'modelo' => 'App\Http\Livewire\LWUsuariosHabilidades',
        ]);
        App::create([
            'id' => Str::uuid()->toString(),
            'nome' => 'Pacote do Cliente',
            'icone' => null,
            'descricao' => 'Gestão dos aplicativos que o cliente contratou',
            'modelo' => 'App\Http\Livewire\LWPacotesClientes',
        ]);
        App::create([
            'id' => Str::uuid()->toString(),
            'nome' => 'Menu do Cliente',
            'icone' => null,
            'descricao' => 'Gerenciamento dos itens do menu dos clientes.',
            'modelo' => 'App\Http\Livewire\LWMenu',
        ]);
        App::create([
            'id' => Str::uuid()->toString(),
            'nome' => 'Histórico do Sistema',
            'icone' => null,
            'descricao' => 'Gerenciamento das ações dos usuários.',
            'modelo' => 'App\Http\Livewire\LWHistorico',
        ]);        
        App::create([
            'id' => Str::uuid()->toString(),
            'nome' => 'Coleções de Icones',
            'icone' => null,
            'descricao' => 'Gerenciamento de Ícones disponíveis no sistema.',
            'modelo' => 'App\Http\Livewire\LWIcones',
        ]);   
        
        App::create([
            'id' => Str::uuid()->toString(),
            'nome' => 'Classe de Usuários',
            'icone' => null,
            'descricao' => 'Gerenciamento dos tipos de usuários do sistema',
            'modelo' => 'App\Http\Livewire\LWADMRoles',
        ]);   
        App::create([
            'id' => Str::uuid()->toString(),
            'nome' => 'Usuários',
            'icone' => null,
            'descricao' => 'Gerenciamento dos usuários do sistema',
            'modelo' => 'App\Http\Livewire\Adm\LWAdmUsuario',
        ]);   
        App::create([
            'id' => Str::uuid()->toString(),
            'nome' => 'Histórico do Sistema',
            'icone' => null,
            'descricao' => 'Gerenciamento de acessos e ações dos usuários.',
            'modelo' => 'App\Http\Livewire\LWADMHistoricos',
        ]);    
        App::create([
            'id' => Str::uuid()->toString(),
            'nome' => 'Habilidades das Classes de Usuários',
            'icone' => null,
            'descricao' => 'Gerenciamento das Habilidades das Classes de usuários',
            'modelo' => 'App\Http\Livewire\LWADMRolespermissions',
        ]);
        App::create([
            'id' => Str::uuid()->toString(),
            'nome' => 'Habilidades de Usuários',
            'icone' => null,
            'descricao' => 'Gestão das Habilidades dos Usuários nos aplicativos.',
            'modelo' => 'App\Http\Livewire\LWADMUserspermissions',
        ]);
        App::create([
            'id' => Str::uuid()->toString(),
            'nome' => 'Road Map',
            'icone' => null,
            'descricao' => 'Gerenciamento das Features do sistema.',
            'modelo' => 'App\Http\Livewire\LWRoadMap',
        ]);


    }
}
