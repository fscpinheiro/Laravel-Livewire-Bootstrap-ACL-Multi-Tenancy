<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LWNavTabs extends Component
{
    public $tabs = [];
    public $activeTab;

    public function render(){
        return view('livewire.l-w-nav-tabs');
    }

    public function mount($tabs){
        $this->tabs = $tabs;
        $this->activeTab = session('activeTab', $tabs[0]['id']);
        Log::info('mount: '.$this->activeTab);
    }

    public function setActiveTab($tab){
        $this->activeTab = $tab;
        Log::info('setActiveTab: '.$this->activeTab);
        session(['activeTab' => $tab]);
    }
}
