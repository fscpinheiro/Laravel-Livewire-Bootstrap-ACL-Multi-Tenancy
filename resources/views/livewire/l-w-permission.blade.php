<div>
    <div class="text-center mb-4">
      <h3 class="address-title mb-2">{{ $modal_title }}</h3>
      <p class="text-muted address-subtitle">{{ $modal_subtitle }}</p>
    </div>

    <form id="PermissionForm" class="row g-3  {{ $form_show != 1 ? 'd-none' : '' }}" autocomplete="off"  wire:submit.prevent="savePermission">
      <hr>
      <input type="hidden" name="id" wire:model="id">
      <div class="col-12">
        <label class="form-label" for="app_id">App</label>
        <select id="app_id_select" wire:key="app_id_select" data-tipo="img" wire:model.defer="app_id" wire:ignore  class="select2 form-select" title="Escolha um App para criar uma permissão">
          <option value="">Escolha uma opção</option>
          @foreach ($apps as $app)
            <option value="{{ $app->id }}"  data-adorno="{{ $app->icone }}">{{ $app->nome }} </option>
          @endforeach
        </select>
        @error('app_id')
          <div class="alert alert-danger mt-3">{{ $message }}</div>
        @enderror
      </div>     
      <div class="col-12">
        @if($habilidades_group)
          @foreach ($habilidades_group as $hg)
          <span class="badge bg-primary m-2">{{ $hg->nome }}</span>
          @endforeach
        @endif
      </div>
      <div class="col-12">
        <label class="form-label" for="nome">Nome</label>
        <input type="search" class="form-control" title="Nome da Permissão" autocomplete="off" wire:model.defer="nome"/>
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

    <form id="PermissionForm2" class="row g-3  {{ $form_show != 2 ? 'd-none' : '' }}" autocomplete="off"  wire:submit.prevent="deletePermission">
      <div class="col-12">
        <div class="demo-inline-spacing mt-3">
            <div class="list-group">
              <div class="list-group-item  d-flex align-items-center cursor-pointer">
                <div class="badge p-2 rounded" style="display: inline-block; margin-right: 12px; background-color: #666;"><i class="ti ti-briefcase ti-sm"></i></div>
                <div class="w-100">
                  <div class="d-flex justify-content-between">
                    <div class="user-info">
                      
                      <h4 class="mb-1">{{ $nome }}</h4>

                      <h6>{{ $app_nome }}</h6>
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