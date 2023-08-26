<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Carbon\Carbon;
use File;
use Illuminate\Support\Facades\DB;

use App\Models\App;

class LWApp extends Component
{
    use WithFileUploads;

    public $aplicativo_id;
    protected $listeners = ['editApp'=>'editar','deleteApp'=>'deletar','novoApp'=>'novoApp'];
    public $apps, $app_id, $nome, $icone, $descricao,  $modelo, $progress;
    public $form_show = '1';
    public $modal_title = "Cadastro de Apps";
    public $modal_subtitle = "Criação e Atualização de Apps";

    public function refreshTable(){
        $this->emit('updateAppTable');
    }

    public function mount(){
        $getapp = App::where('modelo', self::class)->first();
        $this->aplicativo_id = $getapp->id;
    }

    public function render(){
        return view('livewire.l-w-app');
    }

    public function saveApp(){
        $this->validate([
            'nome' => 'required|min:2|max:255',      
            'descricao' => 'nullable|max:255',
            'modelo' => 'nullable',
            'icone' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($this->app_id && (is_null($value) || is_string($value))) {
                        return;
                    }
                    $validator = Validator::make([$attribute => $value], [
                        $attribute => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    ]);
                    if ($validator->fails()) {
                        $fail($validator->errors()->first($attribute));
                    }
                },
            ],            
        ]);

        if($this->app_id){

            //update
            if (!check_permission($this->aplicativo_id, 'editar')) {
                $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
                return;
            }
            $app = App::find($this->app_id);
            if($this->icone instanceof \Illuminate\Http\UploadedFile){
                if($app->icone){
                    Storage::delete('public/icone_uploads/'.$app->icone);
                }
                $imageName = Carbon::now()->timestamp.'.'.$this->icone->extension();
                $this->icone->storeAs('public/icone_uploads', $imageName);
                $app->icone = $imageName;
            }
            $app->nome = $this->nome;
            $app->descricao = $this->descricao;
            $app->modelo = $this->modelo;
            if($app->update()){
                save_activity($this->aplicativo_id, now(), request()->ip(), 'editar');
                $this->dispatchBrowserEvent('closeModal');
                $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'App atualizado com sucesso.', 'time' => 5000]);
                $this->resetInputFields();

            }else{
                if (isset($imageName)) {
                    Storage::delete('public/icone_uploads/'.$imageName);
                }
                $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'Erro ao atualizar o App, atualize a página e tente novamente, e se o erro persistir, abra um chamado!', 'time' => 5000]);                
            }
        }else {
            //save
            if (!check_permission($this->aplicativo_id, 'criar')) {
                $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
                return;
            }
            $app = new App();
            try {
                if($this->icone instanceof \Illuminate\Http\UploadedFile){
                    //Log::info('have a icon');
                    $imageName = Carbon::now()->timestamp.'.'.$this->icone->extension();
                    $this->icone->storeAs('public/icone_uploads', $imageName);
                    $app->icone = $imageName;
                    //Log::info('imageName: '.$imageName);
                }

                $app->id = Str::uuid();
                $app->nome = $this->nome;
                $app->descricao = $this->descricao;
                $app->modelo = $this->modelo;
                //DB::enableQueryLog();
                if($app->save()){
                    $query = DB::getQueryLog()[0];
                    //Log::info($query['query']);
                    //Log::info($query['bindings']);
                    save_activity($this->aplicativo_id, now(), request()->ip(), 'criar');
                    $this->dispatchBrowserEvent('closeModal');
                    $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'App criado com sucesso', 'time' => 5000]);
                    $this->resetInputFields();
                }else{
                    if($this->icone instanceof \Illuminate\Http\UploadedFile){
                        Storage::delete('public/icone_uploads/'.$imageName);
                    }
                    $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'Erro ao salvar o App, atualize a página e tente novamente, se o erro persistir, abra um chamado!', 'time' => 5000]);
                }
                

            } catch (\Exception $e) {
                //Log::error('Erro ao salvar o aplicativo: ' . $e->getMessage());
                //Log::error($e->getTraceAsString());
                $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'Erro ao salvar o App, atualize a página e tente novamente, se o erro persistir, abra um chamado!', 'time' => 5000]);
            }
        }
        $this->refreshTable(); 
    }

    public function showForm($formNumber) {
        $this->form_show = $formNumber;
        $this->modal_title = "Cadastro de Apps";
        $this->modal_subtitle = "Criação e Atualização de Apps";
    }

    public function deleteApp(){
        if (!check_permission($this->aplicativo_id, 'excluir')) {
            $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
            return;
        }
        $app = App::find($this->app_id);
        if($app){
            $app->delete();
            save_activity($this->aplicativo_id, now(), request()->ip(), 'excluir');
            $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'App excluido com sucesso', 'time' => 4000]);
            if($app->icone){
                if(Storage::exists('icone_uploads/'.$app->icone)){
                    Storage::delete('icone_uploads/'.$app->icone);
                }
            }
        }else{
            $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'Não foi possível excluir este app, atualize a página e tente novamente, se o erro persistir, abra um chamado!', 'time' => 6000]);
        }
        $this->refreshTable();  
    }

    public function updating($icone){
        if ($icone instanceof UploadedFile) {
            $this->progress = (int) ($icone->getSize() * 100 / $icone->getMaxFilesize());
            $this->emit('progressUpdated', $this->progress);
        }
    }

    public function editar($data){
        $id = $data['id'];
        $app = App::find($id);
        $this->form_show = '1';
        $this->app_id = $app['id'];
        $this->nome = $app['nome'];
        $this->descricao = $app['descricao'];
        $this->modelo = $app['modelo'];
        $this->icone = $app['icone'];
        $this->modal_title = "Cadastro de Apps";
        $this->modal_subtitle = "Criação e Atualização de Apps";
        $this->dispatchBrowserEvent('abrirModalEdit', ['app' => $app]);
    }

    public function deletar($data){
        $id = $data['id'];
        $app = App::find($id);
        $this->form_show = '2';
        $this->app_id = $app['id'];
        $this->nome = $app['nome'];
        $this->descricao = $app['descricao'];
        $this->modelo = $app['modelo'];
        $this->icone = $app['icone'];
        $this->modal_title = "Cadastro de Apps";
        $this->modal_subtitle = "Exclusão de App";
        $this->dispatchBrowserEvent('abrirModalEdit', ['app' => $app]);

    }

    public function novoApp(){
        $this->form_show = '1';
        $this->app_id = null;
        $this->nome = '';
        $this->descricao = '';
        $this->modelo = '';
        $this->icone = null;
        $this->modal_title = "Cadastro de Apps";
        $this->modal_subtitle = "Criação e Atualização de Apps";
        $this->dispatchBrowserEvent('abrirModalNovo');
    }

    public function resetInputFields(){
        $this->app_id = null;
        $this->nome = '';
        $this->descricao = '';
        $this->icone = '';
        $this->modelo = '';
    }
}
