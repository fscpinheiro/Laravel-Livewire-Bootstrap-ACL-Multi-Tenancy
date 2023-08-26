<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\Models\Cliente;
use App\Models\App;
use App\Models\Role;
use App\Models\Permission;

class LWClassesHabilidades extends Component{

    protected $listeners = ['updatedClienteId'=>'updatedClienteId','updatedClasseId'=>'updatedClasseId','updatedClienteAppId'=>'updatedClienteAppId'];
    public $clientes, $cliente_id, $cliente_nome, $classes, $classe_id, $classe_nome, $clienteApss, $clienteapp_id, $HabilidadesDisponiveis, $classeHabilidades;
    public $aplicativo_id;

    public function updatedClienteId($value){
        $this->cliente_id = $value;
        $this->classes = Role::where('cliente_id', $value)->get();
        $this->clienteApps = Cliente::find($value)->apps;             
    }

    public function updatedClienteAppId($value){
        $this->clienteapp_id = $value;
        if ($this->classe_id) {
            $this->classeHabilidades = Role::find($this->classe_id)->permissions;
            $this->HabilidadesDisponiveis = App::find($value)->permissions()->whereDoesntHave('roles', function ($query) {
                $query->where('roles.id', $this->classe_id);
            })->get();
        } else {
            $this->HabilidadesDisponiveis = App::find($value)->permissions;
        }
    }

    public function updatedClasseId($value){
        $this->classe_id = $value;
        $this->classeHabilidades = Role::find($value)->permissions;
        if ($this->clienteapp_id) {
            $this->HabilidadesDisponiveis = App::find($this->clienteapp_id)->permissions()->whereDoesntHave('roles', function ($query) {
                $query->where('roles.id', $this->classe_id);
            })->get();
        }
    }

    public function mount(){
        $getapp = App::where('modelo', self::class)->first();
        $this->aplicativo_id = $getapp->id;

        $this->clienteApps = collect();
        $this->classes = collect();
        $this->HabilidadesDisponiveis = collect();
        $this->classeHabilidades = collect();
        $this->clientes = collect();
        if (!check_permission($this->aplicativo_id, 'consultar')) {
            $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);            
            return;
        }else{
            save_activity($this->aplicativo_id, now(), request()->ip(), 'consultar');
            $this->clientes = Cliente::all();
        }        
    }

    public function render(){
        if ($this->cliente_id) {
            $this->classes = Role::where('cliente_id', $this->cliente_id)->get();
            $this->clienteApps = Cliente::find($this->cliente_id)->apps;
        }
        return view('livewire.l-w-classes-habilidades');
    }

    public function addHabilidade($value){
        if (!check_permission($this->aplicativo_id, 'criar')) {
            $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
            return;
        }
        $role = Role::find($this->classe_id);
        if($role ){
            $role->permissions()->attach($value);
            $this->classeHabilidades = $role->permissions;
            $this->HabilidadesDisponiveis = App::find($this->clienteapp_id)->permissions()->whereDoesntHave('roles', function ($query) {
                $query->where('roles.id', $this->classe_id);
            })->get();
            $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'Habilidade adicionada com sucesso.', 'time' => 4000]);
            save_activity($this->aplicativo_id, now(), request()->ip(), 'criar');
        }else{
            $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'Não foi possível adicionar a habilidade.', 'time' => 4000]);
        }
    }

    public function removeHabilidade($value){
        if (!check_permission($this->aplicativo_id, 'excluir')) {
            $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
            return;
        }
        $role = Role::find($this->classe_id);
        if($role){
            $role->permissions()->detach($value);
            $this->classeHabilidades = $role->permissions;
            $this->HabilidadesDisponiveis = App::find($this->clienteapp_id)->permissions()->whereDoesntHave('roles', function ($query) {
                $query->where('roles.id', $this->classe_id);
            })->get();
            $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'Habilidade removida com sucesso.', 'time' => 4000]);
            save_activity($this->aplicativo_id, now(), request()->ip(), 'excluir');
        }else{
            $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'Não foi possível remover a habilidade.', 'time' => 4000]);
        }
        
    }

}
