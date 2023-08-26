<div>
    <div class="text-center mb-4">
      <h3 class="address-title mb-2">{{ $modal_title }}</h3>
      <p class="text-muted address-subtitle">{{ $modal_subtitle }}</p>
    </div>
    
    <form id="RoleForm" class="row g-3 {{ $form_show != 1 ? 'd-none' : '' }}" autocomplete="off"  wire:submit.prevent="saveRole">
      <hr>
      <input type="hidden" name="id" wire:model="id">
      <div class="col-12">
        <label class="form-label" for="cliente_id">Cliente</label>
        <select id="cliente_id_select" wire:key="cliente_id_select" data-tipo="img" wire:model.defer="cliente_id" class="select2 form-select" title="Escolha um Cliente para criar uma classe de usuário">
          <option value="">Escolha uma opção</option>
          @foreach ($clientes as $cliente)
            <option value="{{ $cliente->id }}" data-adorno="{{ $cliente->logo }}">{{ $cliente->razaosocial }} </option>
          @endforeach
        </select>
        @error('cliente_id')
          <div class="alert alert-danger mt-3">{{ $message }}</div>
        @enderror
      </div>    
      <div class="col-2">
        <label class="form-label" for="cor">Cor</label>
        <input class="form-control" type="color" value="#FF0000" wire:model.defer="cor" title="Cor deste papel" wire:ignore>
        @error('cor')
          <div class="alert alert-danger mt-3">{{ $message }}</div>
        @enderror
      </div>
      <div class="col-10">
        <label class="form-label" for="nome">Nome</label>
        <input type="search" class="form-control" title="Nome do Papel do usuário" autocomplete="off" wire:model.defer="nome"/>
        @error('nome')
          <div class="alert alert-danger mt-3">{{ $message }}</div>
        @enderror
      </div>
      <div class="col-12">
        <label class="form-label" for="descricao">Descrição</label>
        <textarea class="form-control" wire:model.defer="descricao" rows="3"  title="Descrição da Função"></textarea>
        @error('descricao')
          <div class="alert alert-danger mt-3">{{ $message }}</div>
        @enderror
      </div>
      <div class="col-12 d-flex justify-content-between mt-4">
        <button class="btn btn-label-secondary btn-prev waves-effect"  type="reset" data-bs-dismiss="modal" aria-label="Close" wire:click="resetInputFields">
          <i class="fa-solid fa-stop ti-xs me-1"></i>
          <span class="align-middle d-sm-inline-block d-none"> Cancelar</span>
        </button>
        <button class="btn btn-success btn-next waves-effect waves-light" type="submit">
          <i class="fa-solid fa-cloud-arrow-up ti-xs me-1"></i>
          <span class="align-middle d-sm-inline-block d-none me-sm-1"> Salvar</span>        
        </button>
      </div>
    </form>    
    
    <form id="RoleForm2" class="row g-3 {{ $form_show != 2 ? 'd-none' : '' }}" autocomplete="off"  wire:submit.prevent="deleteRole">
      <div class="col-12">
        <div class="demo-inline-spacing mt-3">
            <div class="list-group">
              <div class="list-group-item  d-flex align-items-center cursor-pointer">
                <div class="badge p-2 rounded" style="display: inline-block; margin-right: 12px; background-color: {{ $cor }};"><i class="ti ti-briefcase ti-sm"></i></div>
                <div class="w-100">
                  <div class="d-flex justify-content-between">
                    <div class="user-info">
                      
                      <h4 class="mb-1">{{ $nome }}</h4>

                      <h6>{{ $cliente_nome }}</h6>
                      <div class="user-status">
                          <small>{{ $descricao }}</small>
                      </div>
                    </div>
                    <div class="add-btn">
                      <button type="button" class="btn btn-outline-primary btn-sm waves-effect waves-light" wire:click="showForm(1)"><i class="ti ti-pencil"  style="font-size: 14px;"></i> Editar</button>                    
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
      </div>        
      <div class="col-12 d-flex justify-content-between mt-4">
        <button class="btn btn-label-secondary btn-prev waves-effect"  type="reset" data-bs-dismiss="modal" aria-label="Close">
          <i class="fa-solid fa-stop ti-xs me-1"></i>
          <span class="align-middle d-sm-inline-block d-none"> Cancelar</span>
        </button>
        <button class="btn btn-danger btn-next waves-effect waves-light" type="submit" data-bs-dismiss="modal" aria-label="Close">
          <i class="fa-regular fa-trash-can ti-xs me-1"></i>          
          <span class="align-middle d-sm-inline-block d-none me-sm-1"> Confirmar Exclusão</span>        
        </button>
      </div>

    </form>  

  </div>