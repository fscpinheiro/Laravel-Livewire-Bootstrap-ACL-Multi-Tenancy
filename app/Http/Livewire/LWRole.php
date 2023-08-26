<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cliente;
use App\Models\Role;
use App\Models\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LWRole extends Component
{
    protected $listeners = ['updatedClienteId'=>'updatedClienteId','editRole' => 'editar', 'deleteRole'=> 'deletar', 'novoRole' => 'novoRole'];

    public $aplicativo_id;
    public $clientes, $cliente_id, $cor, $descricao, $nome, $role_id, $cliente_nome;
    public $form_show = '1';
    public $modal_title = "Cadastro de Papeis";
    public $modal_subtitle = "Criação e Atualização de Papeis no Sistema";

    public function refreshTable(){
        $this->emit('updateRoleTable');
    }

    public function mount(){
        $getapp = App::where('modelo', self::class)->first();
        $this->aplicativo_id = $getapp->id;
        $this->clientes = Cliente::all();
    }

    public function render(){
        return view('livewire.l-w-role');
    }

    public function updatedClienteId($value){
        $this->cliente_id = $value;
    }

    public function saveRole(){        
        $this->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'nome' => 'required|min:3|max:255',      
            'cor' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'descricao' => 'nullable'
        ]);

        if ($this->role_id) {
            // Atualizar Role existente
            if (!check_permission($this->aplicativo_id, 'editar')) {
                $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
                return;
            }
            $role = Role::find($this->role_id);            
            $role->nome = $this->nome;
            $role->cor = $this->cor;
            $role->descricao = $this->descricao;
            $role->cliente_id = $this->cliente_id;            
            if ($role->update()) {
                $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'Classe de usuário foi atualizada com sucesso.', 'time' => 5000]);
                $this->dispatchBrowserEvent('closeModal');
                save_activity($this->aplicativo_id, now(), request()->ip(), 'editar');
                $this->resetInputFields();
            } else {
                $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'Erro ao atualizar o papel do usuário, atualize a página e tente novamente, e se o erro persistir, abra um chamado!', 'time' => 5000]);
            }
        } else {
            // Salva uma nova role
            if (!check_permission($this->aplicativo_id, 'criar')) {
                $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
                return;
            }
            $role = new Role();
            try {
                $role->id = Str::uuid();
                $role->nome = $this->nome;
                $role->cor = $this->cor;
                $role->descricao = $this->descricao;
                $role->cliente_id = $this->cliente_id;
                
                if ($role->save()) {
                    $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'Nova Classe salva com sucesso.', 'time' => 5000]);
                    $this->dispatchBrowserEvent('closeModal');
                    save_activity($this->aplicativo_id, now(), request()->ip(), 'criar');
                    $this->resetInputFields();
                } else {
                    $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'Erro ao salvar a classe do usuário, atualize a página e tente novamente, e se o erro persistir, abra um chamado!', 'time' => 5000]);
                }
            } catch (\Exception $e) {
                $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'Erro ao salvar o classe do usuário, atualize a página e tente novamente, e se o erro persistir, abra um chamado!', 'time' => 5000]);
            }
        }
        $this->refreshTable(); 
    }

    public function editar($data){
        $id = $data['id'];
        $role = Role::find($id);
        $this->form_show = '1';
        $this->role_id = $role['id'];
        $this->nome = $role['nome'];
        $this->cliente_id = $role['cliente_id'];
        $this->cor = $role['cor'];
        $this->descricao = $role['descricao'];
        $this->modal_title = "Cadastro de Papeis do Sistema";
        $this->modal_subtitle = "Criação e Atualização de Papeis no Sistema";
        $this->dispatchBrowserEvent('abrirModalEdit', ['role' => $role]);
    }

    public function resetInputFields(){
        $this->role_id = null;
        $this->cliente_id = '';
        $this->cor = '';
        $this->descricao = '';
        $this->nome = '';
    }

    public function deletar($data){
        $id = $data['id'];
        $role = Role::find($id);
        $this->cliente_nome = $role->cliente->fantasia;
        $this->form_show = '2';
        $this->role_id = $role['id'];
        $this->nome = $role['nome'];
        $this->cliente_id = $role['cliente_id'];
        $this->cor = $role['cor'];
        $this->descricao = $role['descricao'];
        $this->modal_title = "Cadastro de Papeis do Sistema";
        $this->modal_subtitle = "Exclusão de Papel no Sistema";
        $this->dispatchBrowserEvent('abrirModalEdit', ['role' => $role]);
    }
    
    public function showForm($formNumber) {
        $this->form_show = $formNumber;
        $this->modal_title = "Cadastro de Papeis do Sistema";
        $this->modal_subtitle = "Criação e Atualização de Papeis no Sistema";
    }

    public function deleteRole(){
        if (!check_permission($this->aplicativo_id, 'excluir')) {
            $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
            return;
        }
        $role = Role::find($this->role_id);
        if($role){
            $role->delete();
            $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'Classe do Usuário foi excluida com sucesso.', 'time' => 5000]);
            save_activity($this->aplicativo_id, now(), request()->ip(), 'excluir');
        }else{
            $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'Não foi possível excuir o papel do sistema, atualize a página e tente novamente, e se o erro persistir, abra um chamado!', 'time' => 5000]);
        }
        $this->refreshTable(); 
    }

    public function novoRole(){
        $this->form_show = '1';
        $this->role_id = null;
        $this->nome = '';
        $this->cliente_id = '';
        $this->cor = '';
        $this->descricao = '';
        $this->modal_title = "Cadastro de Papeis do Sistema";
        $this->modal_subtitle = "Criação e Atualização de Papeis no Sistema";
        $this->dispatchBrowserEvent('abrirModalNovo');
    }
}
