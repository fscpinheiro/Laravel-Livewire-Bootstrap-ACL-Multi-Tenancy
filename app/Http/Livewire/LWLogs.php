<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;


class LWLogs extends Component
{

    public $logs;

    public function mount(){
        $logPath = storage_path().'/logs/laravel.log';
        $this->logs = array_reverse(file($logPath));
    }

    public function render()
    {
        return view('livewire.l-w-logs');
    }

    function updateLogs() {
        $logPath = storage_path().'/logs/laravel.log';
        $this->logs = array_reverse(file($logPath));
      }
}
