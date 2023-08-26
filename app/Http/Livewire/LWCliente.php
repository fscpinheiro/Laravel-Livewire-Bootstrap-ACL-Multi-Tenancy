<?php

namespace App\Http\Livewire;

use File;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\Cliente;
use App\Models\App;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\SituacaoClienteCollection;


class LWCliente extends Component
{
    protected $listeners = ['editClient' => 'editar', 'deleteClient'=> 'deletar','novoCliente' => 'novoCliente','updatedSituacao'=>'updatedSituacao'];
    protected $debug = true;
    
    use WithFileUploads;
    
    public $aplicativo_id;
    public $cliente_id, $razaosocial, $fantasia, $situacoes, $documento, $situacao, $logo, $tpcliente, $progress, $documentoMascara;
    public $razaosocial_txt, $fantasia_txt, $situacao_txt, $color_st_txt, $logo_txt;
    public $form_show = '1';
    public $modal_title = "Cadastro de Cliente";
    public $modal_subtitle = "Criação e Atualização de Contas";

    public function refreshTable(){
        $this->emit('updateClienteTable');
    }
         
    public function updatedSituacao($value){
        $this->situacao = $value;   
    }

    public function mount(){
        $getapp = App::where('modelo', self::class)->first();
        $this->aplicativo_id = $getapp->id;
        $this->documentoMascara = '000.000.000-00';
        $this->situacoes = SituacaoClienteCollection::codes()->map(function ($item) {
            Log::info($item['cor']);
            return ['value' => $item['code'], 'text' => $item['label'], 'cor' => $item['cor']];
        })->prepend(['value' => '', 'text' => 'Escolha uma opção']);
    }

    public function render(){
        return view('livewire.l-w-cliente');
    }

    public function saveCliente(){
        $this->validate([
            'razaosocial' => 'required|min:2|max:255',      
            'fantasia' => 'required|min:2|max:255',
            'documento' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($this->tpcliente == 'PF') {
                        if (!preg_match('/^[0-9]{3}\.?[0-9]{3}\.?[0-9]{3}\-?[0-9]{2}$/', $value)) {
                            $fail('O CPF informado não é válido.');
                        }
                    } else {
                        if (!preg_match('/^\d{3}\.\d{3}\.\d{3}\/\d{4}\-\d{2}$/', $value)) {
                            $fail('O CNPJ informado não é válido.');
                        }
                    }
                }
            ],
            'situacao' => 'required',
            'logo' => [
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
            'tpcliente' => 'required|in:PF,PJ',
        ]);

        if ($this->cliente_id) {
            if (!check_permission($this->aplicativo_id, 'editar')) {
                $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
                return;
            }
            $cliente = Cliente::find($this->cliente_id);
            if ($this->logo instanceof \Illuminate\Http\UploadedFile) {
                if($cliente->logo){
                    Storage::delete('public/image_uploads/'.$cliente->logo);
                }                
                $imageName = Carbon::now()->timestamp.'.'.$this->logo->extension();
                $this->logo->storeAs('public/image_uploads', $imageName);
                $cliente->logo = $imageName;
            }

            $cliente->razaosocial = $this->razaosocial;
            $cliente->fantasia = $this->fantasia;
            $cliente->documento = $this->documento;
            $cliente->situacao = $this->situacao;
            $cliente->tpcliente = $this->tpcliente;
            $cliente->slugname = Str::slug($this->fantasia);
            if ($cliente->update()) {
                $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'Cliente atualizado com sucesso.', 'time' => 4000]);
                $this->dispatchBrowserEvent('closeModal');
                save_activity($this->aplicativo_id, now(), request()->ip(), 'editar');
                $this->resetForm();
            } else {
                if (isset($imageName)) {
                    Storage::delete('public/image_uploads/'.$imageName);
                }
                $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'Erro ao atualizar o cliente, atualize a página e tente novamente, e se o erro persistir, abra um chamado!', 'time' => 4000]);
            }
        } else {
            if (!check_permission($this->aplicativo_id, 'criar')) {
                $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
                return;
            }
            $cliente = new Cliente();
            try {
                if ($this->logo instanceof \Illuminate\Http\UploadedFile) {
                    $imageName = Carbon::now()->timestamp.'.'.$this->logo->extension();
                    $this->logo->storeAs('public/image_uploads', $imageName);
                    $cliente->logo = $imageName;
                }
                
                $cliente->id = Str::uuid();
                $cliente->razaosocial = $this->razaosocial;
                $cliente->fantasia = $this->fantasia;
                $cliente->documento = $this->documento;
                $cliente->situacao = $this->situacao;
                $cliente->tpcliente = $this->tpcliente;
                $cliente->slugname = Str::slug($this->fantasia);      
                if ($cliente->save()) {
                    $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'Cliente salvo com sucesso.', 'time' => 4000]);
                    $this->dispatchBrowserEvent('closeModal');
                    save_activity($this->aplicativo_id, now(), request()->ip(), 'criar');
                    $this->resetForm();
                } else {
                    if ($this->logo instanceof \Illuminate\Http\UploadedFile) {
                        Storage::delete('public/image_uploads/'.$imageName);
                    }
                    $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'Erro ao salvar o cliente, atualize a página e tente novamente, e se o erro persistir, abra um chamado!', 'time' => 6000]);
                }
            } catch (\Exception $e) {
                // Excluir o arquivo de imagem carregado anteriormente
                if ($this->logo instanceof \Illuminate\Http\UploadedFile) {
                    Storage::delete('public/image_uploads/'.$imageName);
                }
                $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'Erro ao salvar o cliente, atualize a página e tente novamente, e se o erro persistir, abra um chamado!', 'time' => 6000]);
            }
        }
        $this->refreshTable();  
    }

    public function deleteCliente(){
        if (!check_permission($this->aplicativo_id, 'excluir')) {
            $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
            return;
        }
        $cliente = Cliente::find($this->cliente_id);
        if($cliente){
            $cliente->delete();
            save_activity($this->aplicativo_id, now(), request()->ip(), 'excluir');
            $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'Cliente excluido com sucesso.', 'time' => 5000]);
            if($cliente->logo){
                if(Storage::exists('image_uploads/' . $cliente->logo)){
                    Storage::delete('image_uploads/'.$cliente->logo);
                }
            }
        }else{
            $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'Não foi possível excuir o cliente, atualize a página e tente novamente, e se o erro persistir, abra um chamado!', 'time' => 8000]);
        }
        $this->refreshTable();   
        $this->resetForm();       
    }
    
    public function updating($logo){
        if ($logo instanceof UploadedFile) {
            $this->progress = (int) ($logo->getSize() * 100 / $logo->getMaxFilesize());
            $this->emit('progressUpdated', $this->progress);
        }
    }    
        
    public function atualizaMascaraDocumento(){
        $tipoPessoa = $this->tpcliente;
        if ($tipoPessoa === 'PF') {
            $this->documentoMascara = '000.000.000-00';
        } else if ($tipoPessoa === 'PJ') {
            $this->documentoMascara = '000.000.000/0000-00';
        }
    }
  
    public function editar($data){        
        $id = $data['id'];
        $cliente = Cliente::find($id);
        $this->form_show = '1';
        $this->cliente_id = $cliente['id'];
        $this->razaosocial = $cliente['razaosocial'];
        $this->fantasia = $cliente['fantasia'];
        $this->documento = $cliente['documento'];
        $this->situacao = $cliente['situacao'];
        $situacao_label = SituacaoClienteCollection::codes()->firstWhere('code', $cliente['situacao']);
        $this->tpcliente = $cliente['tpcliente'];
        $this->logo = $cliente['logo'];
        $this->modal_title = "Cadastro de Cliente";
        $this->modal_subtitle = "Criação e Atualização de Contas";
        $this->dispatchBrowserEvent('abrirModalEdit', ['cliente' => $cliente]);
    }
    
    public function editarAux($id) {
        $data = ['id' => $id];
        $this->editar($data);
    }

    public function deletar($data){
        $id = $data['id'];
        $cliente = Cliente::find($id);
        $this->form_show = '2';
        $this->cliente_id = $cliente['id'];
        $this->razaosocial_txt = $cliente['razaosocial'];
        $this->fantasia_txt = $cliente['fantasia'];        
        $this->logo_txt = $cliente['logo'];
        $situacao = SituacaoClienteCollection::codes()->firstWhere('code', $cliente['situacao']);
        $this->situacao_txt = $situacao['label'];
        $this->color_st_txt = $situacao['cor'];
        $this->modal_title = "Cadastro de Cliente";
        $this->modal_subtitle = "Exclusão de Contas";
        $this->dispatchBrowserEvent('abrirModalEdit', ['cliente' => $cliente]);
    }
    
    public function showForm($formNumber) {
        $this->form_show = $formNumber;
        $this->modal_title = "Cadastro de Cliente";
        $this->modal_subtitle = "Criação e Atualização de Contas";
    }

    public function novoCliente(){
        $this->resetForm();
        $this->dispatchBrowserEvent('abrirModalNovo');
    }

    public function resetForm(){
        $this->form_show = '1';
        $this->modal_title = "Cadastro de Cliente";
        $this->modal_subtitle = "Criação e Atualização de Contas";
        $this->cliente_id = null;
        $this->razaosocial = '';
        $this->fantasia = '';
        $this->documento = '';
        $this->situacao = '';
        $this->tpcliente = 'PF';
        $this->logo = null;
        $this->documentoMascara = '000.000.000-00';
    }

}
