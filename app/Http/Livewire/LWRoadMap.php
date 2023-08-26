<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\App;
use App\Models\RoadMap;

use App\Models\CategoriaRoadMapCollection;
use App\Models\SituacaoRoadMapCollection;

class LWRoadMap extends Component
{

    protected $listeners = [
        'updatedSituacaoFeature'=> 'updatedSituacaoFeature',
        'updatedCategoriaFeature' => 'updatedCategoriaFeature',
        'editFeature' => 'editarFeature', 
        'deleteFeature'=> 'deletarFeature',
        'deleteItemConfirmed' => 'deleteItemConfirmed'
    ];

    public $aplicativo_id, $roadmap_id, $featurename, $featurestatus, $featurecategoria, $featureestimativa, $featureconcluido, $featurenotes;
    public $featureversion = 0;
    public $confirmingDeletion = false;

    public function mount(){
        $getapp = App::where('modelo', self::class)->first();
        $this->aplicativo_id = $getapp->id;
        $this->situacoes = SituacaoRoadMapCollection::codes()->map(function ($item) {
            return ['value' => $item['code'], 'text' => $item['label']];
        })->prepend(['value' => '', 'text' => 'Escolha uma opção']);
        $this->categorias = CategoriaRoadMapCollection::codes()->map(function ($item) {
            return ['value' => $item['code'], 'text' => $item['label']];
        })->prepend(['value' => '', 'text' => 'Escolha uma opção']);
    }

    public function render(){
        return view('livewire.l-w-road-map');
    }

    public function updatedSituacaoFeature($value){
        $this->featurestatus = $value;
    }

    public function updatedCategoriaFeature($value){
        $this->featurecategoria = $value;
    }

    public function incrementversion(){
        $this->featureversion++;
    }

    public function decrementversion(){
        $this->featureversion--;
    }

    public function refreshTable(){
        $this->emit('updateRoadMapTable');
    }

    public function resetInputFields(){
        $this->readmap_id = null;
        $this->featurename = '';
        $this->featurestatus = '';
        $this->featurecategoria = '';
        $this->featureestimativa = '';
        $this->featureconcluido = '';
        $this->featurenotes = '';
        $this->featureversion = '';
    }

    public function saveRoadMap(){
        if ($this->roadmap_id) {
            if (!check_permission($this->aplicativo_id, 'editar')) {
                $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
                return;
            }
            $roadmap = RoadMap::find($this->roadmap_id);
            $roadmap->feature = $this->featurename;
            $roadmap->status = $this->featurestatus;
            $roadmap->category = $this->featurecategoria;
            $roadmap->estimated_completion_date = $this->featureestimativa;
            $roadmap->completed_date = $this->featureconcluido;
            $roadmap->notes = $this->featurenotes;
            $roadmap->version = $this->featureversion;
            if ($roadmap->update()) {
                $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'Feature atualizada com sucesso.', 'time' => 4000]);
                save_activity($this->aplicativo_id, now(), request()->ip(), 'editar');
                $this->resetInputFields();
            } else {
                $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'Erro ao atualizar a feature, atualize a página e tente novamente, e se o erro persistir, abra um chamado!', 'time' => 4000]);
            }
        } else {
            if (!check_permission($this->aplicativo_id, 'criar')) {
                $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
                return;
            }
            $roadmap = new RoadMap();
            $roadmap->id = Str::uuid();
            $roadmap->feature = $this->featurename;
            $roadmap->status = $this->featurestatus;
            $roadmap->category = $this->featurecategoria;
            $roadmap->estimated_completion_date = Carbon::createFromFormat('d-m-Y', $this->featureestimativa)->format('Y-m-d');
            $roadmap->completed_date = Carbon::createFromFormat('d-m-Y', $this->featureconcluido)->format('Y-m-d');

            $roadmap->notes = $this->featurenotes;
            $roadmap->version = $this->featureversion;
            if ($roadmap->save()) {
                $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'Feature criada com sucesso.', 'time' => 4000]);
                save_activity($this->aplicativo_id, now(), request()->ip(), 'criar');
                $this->resetInputFields();
            } else {
                $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'Erro ao criar nova feature, atualize a página e tente novamente, e se o erro persistir, abra um chamado!', 'time' => 6000]);
            }            
        }
        $this->refreshTable();  
    }

    public function editarFeature($data){
        $id = $data['id'];
        $roadmap = RoadMap::find($id);
        $this->roadmap_id = $roadmap['id'];
        $this->featurename = $roadmap['feature'];
        $this->featurestatus = $roadmap['status'];
        $this->featurecategoria = $roadmap['category'];
        $this->featureestimativa = $roadmap['estimated_completion_date'];
        Log::info($this->featureestimativa);
        $this->featureconcluido = $roadmap['completed_date'];
        Log::info($this->featureconcluido);
        $this->featurenotes = $roadmap['notes'];
        $this->featureversion = $roadmap['version'];
        $this->dispatchBrowserEvent('gocadastro', ['roadmap' => $roadmap]);
    }

    public function deletarFeature($id){
        if(!check_permission($this->aplicativo_id, 'excluir')) {
            $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
            return;
        }
        if ($this->confirmingDeletion && $this->confirmingDeletion === $id) {
            RoadMap::destroy($this->confirmingDeletion);
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
            RoadMap::destroy($this->confirmingDeletion);
            save_activity($this->aplicativo_id, now(), request()->ip(), 'excluir');
            $this->confirmingDeletion = false;
            $this->refreshTable(); 
        }
    }

}
