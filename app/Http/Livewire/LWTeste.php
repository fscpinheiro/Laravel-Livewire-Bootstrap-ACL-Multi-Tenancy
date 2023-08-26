<?php

namespace App\Http\Livewire;

use Livewire\Component;
use File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Role;
use App\Models\App;
use App\Models\SituacaoClienteCollection;

class LWTeste extends Component
{
    protected $listeners = ['updatedClienteId' => 'handleUpdatedClienteId'];

    public function mount(){
        $this->clientes = Cliente::all()->map(function ($cliente) {
            return [
                'value' => $cliente->id,
                'text' => $cliente->razaosocial,
                'adorno' => $cliente->logo
            ];
        })->toArray();
    }

    

    public function handleUpdatedClienteId($value)
    {
        $this->cliente_id = $value;
        $this->emit('updateSelectedValue', $value, 'cliente_id_select');
    }


    public function updatedClienteId($value){
        Log::info('updatedClienteId chamado!');
        $this->cliente_id = $value;
        //$this->roles = Role::where('cliente_id', $value)->get();    
    }

    public function render()
    {
        return view('livewire.l-w-teste');
    }
}
