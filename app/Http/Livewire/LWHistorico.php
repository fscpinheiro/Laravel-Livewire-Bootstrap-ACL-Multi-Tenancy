<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Models\Cliente;
use App\Models\App;
use App\Models\Historico;

class LWHistorico extends Component
{
    protected $listeners = [
        'updatedClienteId'=>'updatedClienteId', 'deleteHistorico'=> 'deletar','deleteItemConfirmed' => 'deleteItemConfirmed'
    ];

    public $clientes, $cliente_id, $aplicativo_id, $historico_id, $rangedate, $ini_rangedate, $end_rangedate;
    public $confirmingDeletion = false;

    public function mount(){
        $getapp = App::where('modelo', self::class)->first();
        $this->aplicativo_id = $getapp->id;
        $this->clientes = Cliente::all();
    }

    public function refreshTable(){
        $this->emit('updateHTable');
    }

    public function render(){
        return view('livewire.l-w-historico');
    }

    public function updatedClienteId($value){
        $this->cliente_id = $value;        
    }

    public function deletar($id){
        if(!check_permission($this->aplicativo_id, 'excluir')) {
            $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
            return;
        }
        if ($this->confirmingDeletion && $this->confirmingDeletion === $id) {
            Menu::destroy($id);
            save_activity($this->aplicativo_id, now(), request()->ip(), 'excluir');
            $this->confirmingDeletion = false;    
            $this->refreshTable();  
        } else {
            $this->confirmingDeletion = $id;
            $this->dispatchBrowserEvent('show-delete-confirmation');
        }    
    }

    public function deleteItemConfirmed() {
        if ($this->confirmingDeletion) {
            Historico::destroy($this->confirmingDeletion);
            save_activity($this->aplicativo_id, now(), request()->ip(), 'excluir');
            $this->confirmingDeletion = false;
            $this->refreshTable();            
        }
    }

    public function applyfilters(){      
        if (strpos($this->rangedate, ' to ') !== false) {
            $dates = explode(' to ', $this->rangedate);
            $ini_rangedate = $dates[0];
            $end_rangedate = $dates[1];
        } else {
            $ini_rangedate = $this->rangedate;
            $end_rangedate = $this->rangedate;
        }

        $this->ini_rangedate = $ini_rangedate.' 00:00:00';
        $this->end_rangedate = $end_rangedate.' 23:59:59';
        $this->emit('updateCharts', [
            'cliente_id' => $this->cliente_id,
            'ini_rangedate' => $this->ini_rangedate,
            'end_rangedate' => $this->end_rangedate
        ]);
    }

    public function removefilters(){
        $this->cliente_id = "";
        $this->rangedate = "";
        $this->ini_rangedate = "";
        $this->end_rangedate = "";
        $this->emit('updateCharts', [
            'cliente_id' => $this->cliente_id,
            'ini_rangedate' => $this->ini_rangedate,
            'end_rangedate' => $this->end_rangedate
        ]);
    }


}
