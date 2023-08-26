<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Permission;
use App\Models\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LWPermission extends Component
{
    public $aplicativo_id;
    protected $listeners = ['updatedAppId'=>'updatedAppId','editPermission'=>'editar','deletePermission'=>'deletar', 'novoPermission' => 'novoPermission'];
    public $apps, $permission_id, $nome, $descricao, $app_id, $app_nome, $habilidades_group;
    public $form_show = '1';
    public $modal_title = "Cadastro de Permissões dos Apps";
    public $modal_subtitle = "Criação e Atualização de Permissões dos Apps";
    
    public function refreshTable(){
        $this->emit('updatePermissionTable');
    }

    public function mount(){
        $getapp = App::where('modelo', self::class)->first();
        $this->aplicativo_id = $getapp->id;
        $this->apps = App::all();
    }

    public function render(){
        return view('livewire.l-w-permission');
    }

    public function updatedAppId($value){
        $this->app_id = $value;
        $this->habilidades_group = Permission::where('app_id', $value)->get();
    }

    public function savePermission(){    
        $this->validate([
            'app_id' => 'required|exists:apps,id',
            'nome' => 'required|min:3|max:255',      
            'descricao' => 'nullable'
        ]);

        if ($this->permission_id) {
            if (!check_permission($this->aplicativo_id, 'editar')) {
                $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
                return;
            }
            // Atualizar Permission
            $permission = Permission::find($this->permission_id);            
            $permission->nome = $this->nome;
            $permission->descricao = $this->descricao;
            $permission->app_id = $this->app_id;            
            if ($permission->update()) {
                $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'Permissão atualizado com sucesso.', 'time' => 5000]);
                $this->dispatchBrowserEvent('closeModal');
                save_activity($this->aplicativo_id, now(), request()->ip(), 'editar');
                $this->resetInputFields();
            } else {
                $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'Erro ao atualizar a permissão, atualize a página e tente novamente, e se o erro persistir, abra um chamado!', 'time' => 5000]);
            }
        } else {
            // Salva Permission
            if (!check_permission($this->aplicativo_id, 'criar')) {
                $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
                return;
            }
            $permission = new Permission();
            try {
                $permission->id = Str::uuid();
                $permission->nome = $this->nome;
                $permission->descricao = $this->descricao;
                $permission->app_id = $this->app_id;
                
                if ($permission->save()) {
                    $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'Permissão salva com sucesso.', 'time' => 5000]);
                    $this->dispatchBrowserEvent('closeModal');
                    save_activity($this->aplicativo_id, now(), request()->ip(), 'criar');
                    $this->resetInputFields();
                } else {
                    $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'Erro ao salvar a permissão, atualize a página e tente novamente, e se o erro persistir, abra um chamado!', 'time' => 5000]);
                }
            } catch (\Exception $e) {
                $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'Erro ao salvar o papel do usuaário, atualize a página e tente novamente, e se o erro persistir, abra um chamado!', 'time' => 5000]);
            }
        }
        $this->refreshTable(); 
    }

    public function editar($data){
        $id = $data['id'];
        $permission = Permission::find($id);
        $this->form_show = '1';
        $this->permission_id = $permission['id'];
        $this->nome = $permission['nome'];
        //$this->app_id = $permission['app_id'];
        $this->updatedAppId($permission['app_id']);
        $this->descricao = $permission['descricao'];
        $this->modal_title = "Cadastro de Permissões dos Apps";
        $this->modal_subtitle = "Criação e Atualização de Permissões dos Apps";
        $this->dispatchBrowserEvent('abrirModalEdit', ['permission' => $permission]);
    }

    public function deletar($data){
        $id = $data['id'];
        $permission = Permission::find($id);
        $this->app_nome = $permission->app->nome;
        $this->form_show = '2';
        $this->permission_id = $permission['id'];
        $this->nome = $permission['nome'];
        $this->app_id = $permission['app_id'];
        $this->descricao = $permission['descricao'];
        $this->modal_title = "Cadastro de Permissões dos Apps";
        $this->modal_subtitle = "Exclusão de Permissões dos Apps";
        $this->dispatchBrowserEvent('abrirModalEdit', ['permission' => $permission]);
    }
    
    public function showForm($formNumber) {
        $this->form_show = $formNumber;
        $this->modal_title = "Cadastro de Permissões dos Apps";
        $this->modal_subtitle = "Criação e Atualização de Permissões dos Apps";
    }

    public function deletePermission(){
        if (!check_permission($this->aplicativo_id, 'excluir')) {
            $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
            return;
        }
        $permission = Permission::find($this->permission_id);
        if($permission){
            $permission->delete();
            save_activity($this->aplicativo_id, now(), request()->ip(), 'excluir');
            $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'Permissão do App excluido com sucesso.', 'time' => 5000]);
        }else{
            $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'Não foi possível excuir a permissão do app, atualize a página e tente novamente, e se o erro persistir, abra um chamado!', 'time' => 5000]);
        }
        $this->refreshTable(); 
    }

    public function novoPermission(){
        $this->form_show = '1';
        $this->permission_id = null;
        $this->nome = '';
        $this->app_id = '';
        $this->descricao = '';
        $this->modal_title = "Cadastro de Permissões dos Apps";
        $this->modal_subtitle = "Criação e Atualização de Permissões dos Apps";
        $this->dispatchBrowserEvent('abrirModalNovo');
    }

    public function resetInputFields(){
        $this->permission_id = null;
        $this->nome = '';
        $this->app_id = '';
        $this->descricao = '';
    }

}
