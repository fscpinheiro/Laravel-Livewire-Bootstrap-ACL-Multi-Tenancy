<div>
    <div class="row mb-3">
        <div class="col-12">
            <div class="card border">
                <div class="card-content">
                    <div class="card-body">
                        <div class="col-12">
                            <label class="form-label" for="cliente_id">Cliente</label>
                            <select id="cliente_id_select" wire:key="cliente_id_select" wire:model.defer="cliente_id"  class="select2 form-select" title="Escolha um Cliente para criar um papel">
                                <option value="">Escolha uma opção</option>
                                @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}"  data-image="{{ $cliente->logo }}">{{ $cliente->razaosocial }}</option>
                                @endforeach
                            </select>
                        </div>    
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="card border h-100">
                <div class="card-header">
                    <h5 class="card-title">Cadastro de Itens</h5>
                    <hr>
                </div>
                <div class="card-content">
                    <div class="card-body ">
                        <form id="UserForm" class="row g-3" autocomplete="off"  wire:submit.prevent="saveItem">
                            <input type="hidden" name="id" wire:model="id">
                            <input type="hidden" name="cliente_id" wire:model="cliente_id"> 
                            <div class="col-12">
                                <label class="form-label" for="clienteapp_id">Aplicativos do Cliente</label>
                                <select id="clienteapp_id_select" wire:key="clienteapp_id_select" wire:model.defer="clienteapp_id"  class="select2 form-select" title="Escolha um app">
                                    <option value="">Escolha uma opção</option>
                                    @foreach ($clienteapps as $app)
                                    <option value="{{ $app->id }}" data-image="{{ $app->icone }}"> {{ $app->nome }} </option>
                                    @endforeach
                                </select>
                                @error('clienteapp_id')
                                    <div class="alert alert-danger mt-3">{{ $message }}</div>
                                @enderror                            
                            </div>   
                            <div class="col-12">
                                <label class="form-label" for="itemname">Nome do Item</label>
                                <div class="input-group">
                                    <input type="search" class="form-control" title="Nome do Item" autocomplete="off" wire:model.defer="itemname"/>
                                    <button class="btn btn-outline-info" id="btcopyname" type="button" wire:click="copyappname"><i class='fa fa-copy'></i></button>
                                </div>
                                @error('itemname')
                                <div class="alert alert-danger mt-3">{{ $message }}</div>
                                @enderror
                            </div>          
                            <div class="col-12">
                                <label class="form-label" for="itemicon">Icone do Item</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="iconview"><i class='bi bi-magic'></i></span>
                                    <input type="search" class="form-control" title="Icone do Item" autocomplete="off" wire:model.defer="itemicon"/>
                                    <button class="btn btn-outline-info" id="btpasteicone" type="button" onclick=""><i class='fa fa-paste'></i></button>
                                    <button class="btn btn-outline-info" id="button-addon2" type="button" onclick="window.open('http://localhost:8000/ferramentas/icones')">Icone</button>
                                </div>
                                @error('itemicon')
                                <div class="alert alert-danger mt-3">{{ $message }}</div>
                                @enderror
                            </div>   
                            <div class="col-12">
                                <label class="form-label" for="rotaapp">Rota</label>
                                <input type="search" class="form-control" title="Rota" autocomplete="off" wire:model.defer="rotaapp"/>
                                @error('rotaapp')
                                <div class="alert alert-danger mt-3">{{ $message }}</div>
                                @enderror
                            </div> 
                            <div class="col-12">
                                <label class="form-label" for="acaoitem">Ação Item</label>
                                <input type="search" class="form-control" title="Ação do Item" autocomplete="off" wire:model.defer="acaoitem"/>
                                @error('acaoitem')
                                <div class="alert alert-danger mt-3">{{ $message }}</div>
                                @enderror
                            </div> 
                            <div class="col-12 d-flex justify-content-between mt-4">
                                <button class="btn btn-label-secondary btn-prev waves-effect"  type="reset" aria-label="Close">
                                <i class="fa-solid fa-stop ti-xs me-1"></i>
                                <span class="align-middle d-sm-inline-block d-none"> Cancelar</span>
                                </button>
                                <button class="btn btn-success btn-next waves-effect waves-light" type="submit">
                                <i class="fa-solid fa-cloud-arrow-up ti-xs me-1"></i>
                                <span class="align-middle d-sm-inline-block d-none me-sm-1"> Salvar Item</span>        
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card border h-100">
                <div class="card-header">
                    <h5 class="card-title">Organização do Menu</h5>
                    <hr>
                </div>
                <div class="card-content">
                    <div class="card-body">     
                        <div class="dd">
                        @php
                            echo $this->generateMenuHtml($itemsmenu);
                        @endphp
                        </div>
                        <div class="col-12 d-flex justify-content-between mt-4">
                            <button class="btn btn-label-secondary btn-prev waves-effect"  type="reset" aria-label="Close">
                            <i class="fa-solid fa-stop ti-xs me-1"></i>
                            <span class="align-middle d-sm-inline-block d-none"> Cancelar</span>
                            </button>
                            <button id="saveOmenu" class="btn btn-success btn-next waves-effect waves-light" type="submit">
                            <i class="fa-solid fa-cloud-arrow-up ti-xs me-1"></i>
                            <span class="align-middle d-sm-inline-block d-none me-sm-1"> Salvar Item</span>        
                            </button>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>