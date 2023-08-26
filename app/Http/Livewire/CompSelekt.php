<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;

class CompSelekt extends Component
{
    protected $listeners = ['updateSelectedValue' => 'updatedSelected'];

    public $options,$selected, $tipo, $selectId, $title, $emitEventName;

    public function mount($options, $selected, $tipo, $selectId, $title, $emitEventName){
        $this->options = $options;
        $this->selected = $selected;
        $this->tipo = $tipo;
        $this->selectId = $selectId;
        $this->title = $title;
        $this->emitEventName = $emitEventName;
    }

    public function updatedSelected($value){
        Log::info('UpdatedSelected: ');
        Log::info('emitEventName: '.$this->emitEventName);
        Log::info('Valor: '.$value);
         $this->selected = $value;
        //if ($this->emitEventName) {
        //    $this->emitUp($this->emitEventName, $value);
        //}
    }

    public function emitSelectedEvent($value){
        Log::info('EmitSelectedEvent: ');
        Log::info('emitEventName: '.$this->emitEventName);
        Log::info('Valor: '.$value);
        if ($this->emitEventName) {
            $this->emitUp($this->emitEventName, $value);
        }
    }

    public function render()
    {
        return view('livewire.comp-selekt');
    }
}
