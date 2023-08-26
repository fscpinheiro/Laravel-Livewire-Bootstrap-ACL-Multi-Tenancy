<?php

namespace App\Http\Livewire;


use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\Models\Cliente;
use App\Models\App;
use App\Models\User;
use App\Models\Permission;

class LWUsuariosHabilidades extends Component
{
    protected $listeners = ['updatedClienteId'=>'updatedClienteId','updatedUsuarioId'=>'updatedUsuarioId','updatedClienteAppId'=>'updatedClienteAppId'];
    public $clientes, $cliente_id, $cliente_nome, $usuarios, $usuario_id, $usuario_nome, $clienteApss, $clienteapp_id, $HabilidadesDisponiveis, $usuarioHabilidades, $permitido, $aplicativo_id;

    public function updatedClienteId($value){
        $this->cliente_id = $value;
        $this->usuarios = User::where('cliente_id', $value)->get();
        $this->clienteApps = Cliente::find($value)->apps;             
    }

    public function updatedClienteAppId($value){
        $this->clienteapp_id = $value;
        if ($this->usuario_id) {
            $this->usuarioHabilidades = User::find($this->usuario_id)->permissions;
            $this->HabilidadesDisponiveis = App::find($value)->permissions()->whereDoesntHave('users', function ($query) {
                $query->where('users.id', $this->usuario_id);
            })->get();
        } else {
            $this->HabilidadesDisponiveis = App::find($value)->permissions;
        }
    }

    public function updatedUsuarioId($value){
        $this->usuario_id = $value;
        $this->usuarioHabilidades = User::find($value)->permissions;
        if ($this->clienteapp_id) {
            $this->HabilidadesDisponiveis = App::find($this->clienteapp_id)->permissions()->whereDoesntHave('users', function ($query) {
                $query->where('users.id', $this->usuario_id);
            })->get();
        }
        foreach ($this->usuarioHabilidades as $uhabilidade) {
            $this->permitido[$uhabilidade->id] = $uhabilidade->pivot->permitido;
        }
    }

    public function mount(){
        $getapp = App::where('modelo', self::class)->first();
        $this->aplicativo_id = $getapp->id;
        $this->clientes = Cliente::all();
        $this->clienteApps = collect();
        $this->usuarios = collect();
        $this->HabilidadesDisponiveis = collect();
        $this->usuarioHabilidades = collect();
    }

    public function render(){
        if ($this->cliente_id) {
            $this->usuarios = User::where('cliente_id', $this->cliente_id)->get();
            $this->clienteApps = Cliente::find($this->cliente_id)->apps;
        }
        return view('livewire.l-w-usuarios-habilidades');
    }

    public function addHabilidade($value){
        if (!check_permission($this->aplicativo_id, 'criar')) {
            $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
            return;
        }
        $user = User::find($this->usuario_id);
        if ($user) {
            $user->permissions()->attach($value, ['permitido' => true]);
            $this->usuarioHabilidades = $user->permissions;
            $this->HabilidadesDisponiveis = App::find($this->clienteapp_id)->permissions()->whereDoesntHave('users', function ($query) {
                $query->where('users.id', $this->usuario_id);
            })->get();
            $this->permitido[$value] = true;
            $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'Habilidade adicionada com sucesso.', 'time' => 4000]);
            save_activity($this->aplicativo_id, now(), request()->ip(), 'criar');
        } else {
            $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'Não foi possível adicionar a habilidade.', 'time' => 4000]);
        }
    }

    public function removeHabilidade($value){
        if (!check_permission($this->aplicativo_id, 'excluir')) {
            $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
            return;
        }
        $user = User::find($this->usuario_id);
        if ($user) {
            $user = User::find($this->usuario_id);
            $user->permissions()->detach($value);
            $this->usuarioHabilidades = $user->permissions;
            $this->HabilidadesDisponiveis = App::find($this->clienteapp_id)->permissions()->whereDoesntHave('users', function ($query) {
                $query->where('users.id', $this->usuario_id);
            })->get();
            save_activity($this->aplicativo_id, now(), request()->ip(), 'excluir');
            $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'Habilidade removida com sucesso.', 'time' => 4000]);
        } else {
            $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'Não foi possível remover a habilidade.', 'time' => 4000]);
        }
        
    }

    public function updatePermitido($permissionId){
        if (!check_permission($this->aplicativo_id, 'editar')) {
            $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
            return;
        }
        $user = User::find($this->usuario_id);
        $updated =  $user->permissions()->updateExistingPivot($permissionId, ['permitido' => $this->permitido[$permissionId]]);
        Log::info($this->permitido[$permissionId]);
        if ($updated) {
            $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'Permissão atualizado com sucesso.', 'time' => 4000]);
            save_activity($this->aplicativo_id, now(), request()->ip(), 'editar');
        } else {
            $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'Não foi possível atualizar esta permissão.', 'time' => 4000]);
        }
    }
}
