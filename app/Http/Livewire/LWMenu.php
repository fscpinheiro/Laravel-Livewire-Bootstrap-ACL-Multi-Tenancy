<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Models\Cliente;
use App\Models\App;
use App\Models\Menu;

class LWMenu extends Component
{
    protected $listeners = [
        'updatedClienteId'=>'updatedClienteId', 'updatedClienteAppId'=>'updatedClienteAppId', 'updateMenuOrder'=>'updateMenuOrder', 'editItem' => 'editItem', 'deleteItem' => 'deleteItem', 'deleteItemConfirmed' => 'deleteItemConfirmed'
    ];

    public $clientes, $cliente_id, $clienteapps, $clienteapp_id, $itemname, $itemicon, $menu_id, $rotaapp, $itemsmenu, $posicao, $parentId, $acaoitem;
    public $aplicativo_id;
    public $confirmingDeletion = false;

    public function mount(){
        $getapp = App::where('modelo', self::class)->first();
        $this->aplicativo_id = $getapp->id;
        $this->itemsmenu = collect();
        $this->clientes = Cliente::all();
        $this->clienteapps = collect();
    }

    public function updatedClienteId($value){
        $this->cliente_id = $value;        
        if (!check_permission($this->aplicativo_id, 'consultar')) {
            $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte do sistema.', 'time' => 5000]);
            $this->clienteapps = Cliente::find($value)->apps()->get();
            $this->itemsmenu = collect();
            return;
        }else{
            $this->clienteapps = Cliente::find($value)->apps()->get();
            $this->itemsmenu = $this->getMenuItems($value);
            save_activity($this->aplicativo_id, now(), request()->ip(), 'consultar');
        }        
    }

    public function updatedClienteAppId($value){
        $this->clienteapp_id = $value;
    }

    public function render(){
        if ($this->cliente_id) {
            if (!check_permission($this->aplicativo_id, 'consultar')) {
                $this->clienteapps = collect();    
                $this->itemsmenu = collect();    
            }else{  
                $this->clienteapps = Cliente::find($this->cliente_id)->apps()->get();
                $this->itemsmenu = $this->getMenuItems($this->cliente_id);
                save_activity($this->aplicativo_id, now(), request()->ip(), 'consultar');
            }
        }
        return view('livewire.l-w-menu');
    }

    public function copyappname(){
        $this->itemname = App::find($this->clienteapp_id)->nome;
    }

    public function resetInputFields(){
        $this->itemname = '';
        $this->itemicon = '';
        $this->rotaapp = '';
        $this->acaoitem = '';
        $this->clienteapp_id = null;
        $this->menu_id = null;
    }

    public function saveItem(){
        $rules = [
            'itemname' => 'required|min:2|max:255',  
            'cliente_id' => 'required|exists:clientes,id',
            'itemicon' => 'sometimes|max:255',
            'rotaapp' => 'sometimes|max:255',
            'clienteapp_id' => 'nullable|exists:apps,id',
            'acaoitem' => 'sometimes|max:255',
        ];

        DB::enableQueryLog();

        $this->validate($rules);

        if($this->menu_id){
            if(!check_permission($this->aplicativo_id, 'editar')) {
                $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
                return;
            }

            $menu = Menu::find($this->menu_id);
            $menu->app_id = $this->clienteapp_id;
            $menu->nome = $this->itemname;
            $menu->icone = $this->itemicon;
            $menu->rota = $this->rotaapp;
            $menu->acao = $this->acaoitem;



            if ($menu->save()) {
                save_activity($this->aplicativo_id, now(), request()->ip(), 'editar');
                $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'Item atualizado com sucesso no menu.', 'time' => 4000]);
            } else {
                $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'Não foi possível atualizar este item agora, tente novamente, se o erro persistir, abra um chamado!', 'time' => 6000]);
            }

            $query = DB::getQueryLog()[0];
            Log::info($query['query']);
            Log::info($query['bindings']);

        }else{
            if(!check_permission($this->aplicativo_id, 'criar')) {
                $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
                return;
            }
            $menu = new Menu();
            $menu->id = Str::uuid();
            $menu->cliente_id = $this->cliente_id;
            $menu->app_id = $this->clienteapp_id;
            $menu->nome = $this->itemname;
            $menu->icone = $this->itemicon;
            $menu->rota = $this->rotaapp;    
            $menu->acao = $this->acaoitem;
            if ($menu->save()) {
                save_activity($this->aplicativo_id, now(), request()->ip(), 'criar');
                $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'Item adicionado com sucesso no menu.', 'time' => 4000]);
            } else {
                Log::info($menu->getErrors());
                $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'Não foi possível adicionar este item agora, tente novamente, se o erro persistir, abra um chamado!', 'time' =>8000]);
            }        
            $query = DB::getQueryLog()[0];
            Log::info($query['query']);
            Log::info($query['bindings']);
        }
        $this->itemsmenu = $this->getMenuItems($this->cliente_id);
        $this->resetInputFields();
    }

    public function updateMenuOrder($data){
        if(!check_permission($this->aplicativo_id, 'editar')) {
            $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
            return;
        }
        Log::info('Update Menu Order');
        if ($this->hasChanges($data)) {
            Log::info('Has Changes in Data');
            $success = $this->processItem($data);
            if ($success) {
                Log::info('Success processItem ok');
                save_activity($this->aplicativo_id, now(), request()->ip(), 'editar');
                $this->dispatchBrowserEvent('alert', ['type' => 'success', 'text' => 'Ordem dos itens do menu atualizada com sucesso.', 'time' => 4000]);
            } else {
                Log::info("Success processItem failed");
                $this->dispatchBrowserEvent('alert', ['type' => 'error', 'text' => 'Não foi possível atualizar a ordem dos itens do menu agora, tente novamente, se o erro persistir, abra um chamado!', 'time' =>8000]);
            }
        } else {
            Log::info('No changes in Data');
            $this->dispatchBrowserEvent('alert', ['type' => 'info', 'text' => 'Nenhuma alteração foi feita nos itens do menu.', 'time' => 4000]);
        }
        Log::info("fineshed and show menu");
        $this->itemsmenu = $this->getMenuItems($this->cliente_id);
    }

    private function processItem($items) {
        $success = true;
        foreach ($items as $item) {            
            $values = [
                'posicao' => $item['posicao'] ?? null,
                'parentId' => $item['parentId'] ?? null
            ];
            DB::enableQueryLog();
            $affected = Menu::where('id', $item['id'])->update($values);
            $query = DB::getQueryLog()[0];
            //Log::info($query['query']);
            //Log::info($query['bindings']);
            if (!$affected) {
                $success = false;
            }
            if (isset($item['children'])) {
                if (!$this->processItem($item['children'])) {
                    $success = false;
                }
            }            
        }
        return $success;
    }
   
    private function hasChanges($items) {
        foreach ($items as $item) {
            $menu = Menu::find($item['id']);
            if ($menu->posicao != $item['posicao'] || $menu->parentId != $item['parentId']) {
                return true;
            }
            if (isset($item['children'])) {
                if ($this->hasChanges($item['children'])) {
                    return true;
                }
            }
        }
        return false;
    }

    public function generateMenuHtml($items) {
        if(!check_permission($this->aplicativo_id, 'consultar')) {
            $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
            return;
        }
        $html = '<ol class="dd-list">';
        foreach ($items as $item) {
            $html .= '<li class="dd-item" data-id="' . $item->id . '" data-parent-id="' . $item->parentId . '">';
            $html .= '<div class="dd-handle nestable-item">';
            $html .= '<div class="d-flex justify-content-between align-items-center">';
            $html .= '<div class="d-flex align-items-center">';
            $html .= '<span class="text-muted drag-icon">&#x2630;</span>';
            if ($item->icone) {
                $html .= '<div style="margin-right: 20px;">' . $item->icone . '</div>';
            }
            $html .= '<div>' . $item->nome;
            if ($item->rota) {
                $html .= '<br><span class="text-muted small">' . $item->rota . '</span>';
            } else {
                $html .= '<br><span class="text-muted small">Sem Rota</span>';
            }
            $html .= '</div></div></div></div>';
            if (isset($item->children)) {
                $html .= $this->generateMenuHtml($item->children);
            }
            $html .= '<div class="nestable-buttons">';
            $html .= '<button class="btn btn-outline-secondary btn-sm edit-button" data-id="' . $item->id . '">Editar</button>';
            $html .= '<button class="btn btn-outline-danger btn-sm delete-button" data-id="' . $item->id . '">Excluir</button>';
            $html .= '</div>';

            $html .= '</li>';
        }
        $html .= '</ol>';
        save_activity($this->aplicativo_id, now(), request()->ip(), 'consultar');
        return $html;
    }

    private function getMenuItems($cliente_id, $parent_id = null) {
        $items = Menu::where('cliente_id', $cliente_id)
            ->where('parentId', $parent_id)
            ->orderBy('posicao')
            ->get();
        foreach ($items as $item) {
            $item->children = $this->getMenuItems($cliente_id, $item->id);
        }
        return $items;
    }

    public function editItem($id) {
        $item = Menu::find($id);
        $this->menu_id = $item->id;
        $this->clienteapp_id = $item->app_id;
        $this->itemname = $item->nome;
        $this->itemicon = $item->icone;
        $this->rotaapp = $item->rota;
        $this->acaoitem = $item->acao;
    }

    public function deleteItem($id) {
        if(!check_permission($this->aplicativo_id, 'excluir')) {
            $this->dispatchBrowserEvent('alert', ['type' => 'warning', 'text' => 'Você não tem permissão para executar esta ação, entre em contato com o suporte ou administrador do sistema.', 'time' => 8000]);
            return;
        }
        if ($this->confirmingDeletion && $this->confirmingDeletion === $id) {
            $this->deleteChildren($id);
            Menu::destroy($id);
            save_activity($this->aplicativo_id, now(), request()->ip(), 'excluir');
            $this->confirmingDeletion = false;    
            $this->itemsmenu = $this->getMenuItems($this->cliente_id);
        } else {
            $this->confirmingDeletion = $id;
            $this->dispatchBrowserEvent('show-delete-confirmation');
        }
    }

    public function deleteItemConfirmed() {
        if ($this->confirmingDeletion) {
            DB::enableQueryLog();
            $this->deleteChildren($this->confirmingDeletion);
            Menu::destroy($this->confirmingDeletion);
            $query = DB::getQueryLog()[0];
            //Log::info($query['query']);
            //Log::info($query['bindings']);
            save_activity($this->aplicativo_id, now(), request()->ip(), 'excluir');
            $this->confirmingDeletion = false;
            $this->itemsmenu = $this->getMenuItems($this->cliente_id);
        }
    }

    private function deleteChildren($id) {
        DB::enableQueryLog();
        $children = Menu::where('parentId', $id)->get();
        $query = DB::getQueryLog()[0];
        //Log::info($query['query']);
        //Log::info($query['bindings']);
        foreach ($children as $child) {
            $this->deleteChildren($child->id);
            Menu::destroy($child->id);
            save_activity($this->aplicativo_id, now(), request()->ip(), 'excluir');
            $query = DB::getQueryLog()[0];
            //Log::info($query['query']);
            //Log::info($query['bindings']);
        }
    }
}
