<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\Models\Cliente;
use App\Models\Role;
use App\Models\App;
use App\Models\Permission;

class LWPacotesClientes extends Component{
    protected $listeners = ['updatedClienteId'=>'updatedClienteId','updatedClasseId'=>'updatedClasseId'];
    public $clientes, $cliente_id, $cliente_nome, $availableApps, $clientApps, $aplicativo_id;

    public function mount(){
        $getapp = App::where('modelo', self::class)->first();
        $this->aplicativo_id = $getapp->id;
        $this->clientes = Cliente::all();
        $this->availableApps = App::all();
        $this->clientApps = collect();
    }

    public function addApp($appId){        
        if (!check_permission($this->aplicativo_id, 'criar')) {
            $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
            return;
        }

        if ($this->cliente_id === null) {
            $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Antes de continuar, informe um cliente para poder gerir.', 'time' => 4000]);
        }else{
            $cliente = Cliente::find($this->cliente_id);
            $cliente->apps()->attach($appId);
            $this->clientApps = Cliente::find($this->cliente_id)->apps;
            $this->availableApps = App::whereDoesntHave('clientes', function ($query) {
                $query->where('clientes.id', $this->cliente_id);
            })->get();   
            save_activity($this->aplicativo_id, now(), request()->ip(), 'criar');
            $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'Aplicativo adicionado com sucesso.', 'time' => 4000]);  
        }        
    }

    public function removeApp($appId){
        if (!check_permission($this->aplicativo_id, 'excluir')) {
            $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
            return;
        }
        if($this->cliente_id == null){
            $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'É necessário um cliente para poder gerir seus apps.', 'time' => 4000]);
        }else{
            $cliente = Cliente::find($this->cliente_id);
            if ($cliente) {
                $cliente->apps()->detach($appId);
                $this->clientApps = Cliente::find($this->cliente_id)->apps;
                $this->availableApps = App::whereDoesntHave('clientes', function ($query) {
                    $query->where('clientes.id', $this->cliente_id);
                })->get();
                $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'Aplicativo removido com sucesso.', 'time' => 4000]);  
                save_activity($this->aplicativo_id, now(), request()->ip(), 'excluir');
            }
        }       
    }

    public function render(){
        return view('livewire.l-w-pacotes-clientes');
    }

    public function updatedClienteId($value){
        $this->cliente_id = $value;
        $this->clientApps = Cliente::find($value)->apps;
        $this->availableApps = App::whereDoesntHave('clientes', function ($query) {
            $query->where('clientes.id', $this->cliente_id);
        })->get();  
    }
    
}
