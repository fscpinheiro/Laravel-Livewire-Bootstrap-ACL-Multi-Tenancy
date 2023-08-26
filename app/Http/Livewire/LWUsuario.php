<?php

namespace App\Http\Livewire;

use File;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\Cliente;
use App\Models\Role;
use App\Models\App;
use App\Models\UserSituacaoCollection;

class LWUsuario extends Component
{

    use WithFileUploads;

    protected $listeners = [
        'novoUser' => 'novoUser', 
        'editUser'=>'editar',
        'deleteUser'=>'deletar', 
        'updatedClienteId'=>'updatedClienteId', 
        'updatedRoleId'=>'updatedRoleId', 
        'updatedSituacao'=> 'updatedSituacao'
    ];

    public $aplicativo_id;
    public $clientes, $roles, $situacoes, $user_id, $cliente_id, $role_id, $nome, $email, $senha, $perfil, $situacao, $cliente_nome, $role_nome;
    public $situacao_txt, $perfil_txt, $nome_txt, $email_txt, $color_st_txt;
    public $form_show = '1';
    public $modal_title = "Cadastro de Usuários";
    public $modal_subtitle = "Criação e Atualização de Usuários";
    public $passwordInputType = 'password';

    public function refreshTable(){
        $this->emit('updateUserTable');
    }

    public function mount(){
        $getapp = App::where('modelo', self::class)->first();
        $this->aplicativo_id = $getapp->id;
        $this->clientes = Cliente::all();
        $this->situacoes = UserSituacaoCollection::codes()->map(function ($item) {
            return ['value' => $item['code'], 'text' => $item['label'], 'cor' => $item['cor']];
        })->prepend(['value' => '', 'text' => 'Escolha uma opção']);
    }

    public function render(){
        if ($this->cliente_id) {
            $this->roles = Role::where('cliente_id', $this->cliente_id)->get();
        }
        return view('livewire.l-w-usuario');
    }

    public function updatedSituacao($value){
        $this->situacao = $value;
    }

    public function updatedClienteId($value){
        $this->cliente_id = $value;
        $this->roles = Role::where('cliente_id', $value)->get();    
    }

    public function updatedRoleId($value){
        $this->role_id = $value;
    }

    public function saveUser(){
        $rules = [
            'nome' => 'required|min:2|max:255',      
            'email' => 'required|min:2|max:255',
            'situacao' => 'required',
            'perfil' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($this->cliente_id && (is_null($value) || is_string($value))) {
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
            'senha' => [
                function ($attribute, $value, $fail) {
                    if (!isset($value) || $value === '') {
                        return;
                    }
                    if (strlen($value) < 8) {
                        $fail('A senha deve ter pelo menos 8 caracteres.');
                    }
                    if (strlen($value) > 60) {
                        $fail('A senha deve ter no máximo 60 caracteres.');
                    }
                    if (!preg_match('/[A-Z]/', $value)) {
                        $fail('A senha deve conter pelo menos uma letra maiúscula.');
                    }
                    if (!preg_match('/[a-z]/', $value)) {
                        $fail('A senha deve conter pelo menos uma letra minúscula.');
                    }
                    if (!preg_match('/\d/', $value)) {
                        $fail('A senha deve conter pelo menos um número.');
                    }
                    if (!preg_match('/[^A-Za-z0-9]/', $value)) {
                        $fail('A senha deve conter pelo menos um símbolo.');
                    }
                },
            ],
            'cliente_id' => 'required|exists:clientes,id',
            'role_id' => 'required|exists:roles,id'
        ];

        if (!$this->user_id) {
            array_unshift($rules['senha'], 'required');
        }
     
        $this->validate($rules);

        if ($this->user_id) {
            // Atualizar
            if (!check_permission($this->aplicativo_id, 'editar')) {
                $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
                return;
            }
            $user = User::find($this->user_id);
            if ($this->perfil instanceof \Illuminate\Http\UploadedFile) {
                if($user->perfil){
                    Storage::delete('public/perfil_uploads/'.$user->perfil);
                }                
                $imageName = Carbon::now()->timestamp.'.'.$this->perfil->extension();
                $this->perfil->storeAs('public/perfil_uploads', $imageName);
                $user->perfil = $imageName;
            }
            $user->name = $this->nome;
            $user->email = $this->email;
            if (!empty($this->senha)) {
                $user->password = bcrypt($this->senha);
            }
            $user->situacao = $this->situacao;
            $user->cliente_id = $this->cliente_id;
            $user->role_id = $this->role_id;
            
           
            if ($user->update()) {
                $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'Usuário atualizado com sucesso.', 'time' => 5000]);
                $this->dispatchBrowserEvent('closeModal');
                save_activity($this->aplicativo_id, now(), request()->ip(), 'editar');
                $this->resetInputFields();
            } else {
                if (isset($imageName)) {
                    Storage::delete('public/perfil_uploads/'.$imageName);
                }
                $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'Erro ao atualizar o usuário, atualize a página e tente novamente, e se o erro persistir, abra um chamado!', 'time' => 6000]);
            }

        } else {
           // Salvar
           if (!check_permission($this->aplicativo_id, 'criar')) {
                $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
                return;
            }
            $user = new User();
            try {
                $imageName = Carbon::now()->timestamp.'.'.$this->perfil->extension();
                $this->perfil->storeAs('public/perfil_uploads', $imageName);
                $user->id = Str::uuid();
                $user->name = $this->nome;
                $user->email = $this->email;
                $user->password = bcrypt($this->senha);
                $user->situacao = $this->situacao;
                $user->perfil = $imageName;
                $user->cliente_id = $this->cliente_id;
                $user->role_id = $this->role_id;

                if ($user->save()) {
                    $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'Usuário criado com sucesso.', 'time' => 5000]);
                    $this->dispatchBrowserEvent('closeModal');
                    save_activity($this->aplicativo_id, now(), request()->ip(), 'criar');
                    $this->resetInputFields();
                } else {
                    Storage::delete('public/perfil_uploads/'.$imageName);
                    $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'Erro ao salvar o usuário, atualize a página e tente novamente, e se o erro persistir, abra um chamado!', 'time' => 5000]);
                }
            } catch (\Exception $e) {
                // Excluir o arquivo de imagem carregado anteriormente
                Storage::delete('public/perfil_uploads/'.$imageName);
                $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'Erro ao salvar o usuário, atualize a página e tente novamente, e se o erro persistir, abra um chamado!', 'time' => 5000]);
            }
        }         
        $this->refreshTable(); 
    }

    public function novoUser(){
        $this->form_show = '1';
        $this->role_id = null;
        $this->cliente_id = null;
        $this->nome = '';
        $this->email = '';
        $this->senha = '';
        $this->situacao = '';
        $this->perfil = null;
        $this->modal_title =  "Cadastro de Usuários";
        $this->modal_subtitle = "Criação e Atualização de Usuários";
        $this->dispatchBrowserEvent('abrirModalNovo');
    }

    public function showForm($formNumber) {
        $this->form_show = $formNumber;
        $this->modal_title = "Cadastro de Usuários";
        $this->modal_subtitle = "Criação e Atualização de Usuários";
    }

    public function deleteUser(){
        if (!check_permission($this->aplicativo_id, 'excluir')) {
            $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
            return;
        }
        $user = User::find($this->user_id);
        if($user){
            $user->delete();
            $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'Usuário excluido com sucesso', 'time' => 5000]);
            save_activity($this->aplicativo_id, now(), request()->ip(), 'excluir');
            if($user->perfil){
                if(Storage::exists('perfil_uploads/' . $user->perfil)){
                    Storage::delete('perfil_uploads/'.$user->perfil);
                }
            }
        }else{
            $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'Não foi possível excuir o usuário, atualize a página e tente novamente, e se o erro persistir, abra um chamado!', 'time' => 5000]);
        }
        $this->refreshTable(); 
    }

    public function editar($data){
        $id = $data['id'];
        $user = User::find($id);
        $this->form_show = '1';
        $this->user_id = $user['id'];
        $this->nome = $user['name'];
        $this->email = $user['email'];
        $this->password = $user['senha'];
        $this->situacao = $user['situacao'];
        $this->perfil = $user['perfil'];
        $this->cliente_id = $user['cliente_id'];
        $this->role_id = $user['role_id'];
        $this->modal_title = "Cadastro de Usuários";
        $this->modal_subtitle = "Criação e Atualização de Contas";
        $this->dispatchBrowserEvent('abrirModalEdit', ['user' => $user]);
    }

    public function editarAux($id) {
        $data = ['id' => $id];
        $this->editar($data);
    }

    public function deletar($data){
        $id = $data['id'];
        $user = User::find($id);
        $this->form_show = '2';
        $this->user_id = $user['id'];
        $this->nome_txt = $user['name'];
        $this->email_txt = $user['email'];
        $this->perfil_txt = $user['perfil'];
        $situacao = UserSituacaoCollection::codes()->firstWhere('code', $user['situacao']);
        $this->situacao_txt = $situacao['label'];
        $this->color_st_txt = $situacao['cor'];
        $this->modal_title = "Cadastro de Usuários";
        $this->modal_subtitle = "Exclusão de Contas";
        $this->dispatchBrowserEvent('abrirModalEdit', ['user' => $user]);
    }

    public function togglePasswordVisibility(){
        $this->passwordInputType = $this->passwordInputType === 'password' ? 'text' : 'password';
    }

    public function generatePassword(){
        $length = 16;
        $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+-={}[]|\:;"<>,.?/~`';
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        $this->senha = $str;
    }

    public function resetInputFields(){
        $this->form_show = '1';
        $this->modal_title = "Cadastro de Usuários";
        $this->modal_subtitle = "Criação e Atualização de Contas";
        $this->user_id = null;
        $this->nome = '';
        $this->email = '';
        $this->cliente_id = '';
        $this->role_id = '';
        $this->perfil = '';
        $this->situacao = '';
        $this->senha = '';
    }
}
