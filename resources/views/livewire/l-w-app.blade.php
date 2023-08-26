<div>
    <div class="text-center mb-4">
      <h3 class="address-title mb-2">{{ $modal_title }}</h3>
      <p class="text-muted address-subtitle">{{ $modal_subtitle }}</p>
    </div>
    @if ($form_show == 1)
    <form id="AppForm" class="row g-3" autocomplete="off"  wire:submit.prevent="saveApp">
      <hr>
      <input type="hidden" name="id" wire:model="id">
      <div class="col-12">
        <label class="form-label" for="icone">Icone</label>
        <input type="file" name="icone" id="icone" class="d-none" wire:model="icone">
        <label for="icone" class="d-block mx-auto w-px-150 h-px-100 border rounded overflow-hidden text-center" id="icone-preview">
           
          @if($icone instanceof \Illuminate\Http\UploadedFile)

            <img src="{{ $icone->temporaryUrl() }}" alt="Icone App" class="w-100 h-100" style="object-fit: contain !important;">
          @elseif(is_string($icone))
            <img src="{{ asset('storage/icone_uploads/' . $icone) }}" alt="Icone App" class="w-100 h-100" style="object-fit: contain !important;">
            <p>Teste</p>
          @else
            <img src="{{Vite::asset('resources/assets/img/avatars/camera.jpg')}}" alt="Icone" class="h-100" style="object-fit: contain !important;">
            <div class="text-center">
              <span class="font-weight-bold">Selecionar Imagem</span>
            </div>
          @endif
        </label>
        @error('icone')
          <div class="alert alert-danger mt-3">{{ $message }}</div>
        @enderror
      </div>
      <div class="col-12">
        <label class="form-label" for="nome">Nome</label>
        <input type="search" class="form-control" title="Nome do Papel do usuário" autocomplete="off" wire:model.defer="nome"/>
        @error('nome')
          <div class="alert alert-danger mt-3">{{ $message }}</div>
        @enderror
      </div>
      <div class="col-12">
        <label class="form-label" for="modelo">Modelo</label>
        <input type="search" class="form-control" title="URL do Modelo Base do App" autocomplete="off" wire:model.defer="modelo"/>
        @error('modelo')
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
    @elseif ($form_show == 2)
    <form id="AppForm2" class="row g-3" autocomplete="off"  wire:submit.prevent="deleteApp">
      <div class="col-12">
        <input type="hidden" name="id" wire:model="id">
        <div class="demo-inline-spacing mt-3">
            <div class="list-group">
              <div class="list-group-item list-group-item-action d-flex align-items-center cursor-pointer">
                @if($icone)
                <img src="{{ asset('storage/icone_uploads/' . $icone) }}" alt="App Image" class="rounded me-3 w-px-100 fiximg">
                @else
                <img src="{{Vite::asset('resources/assets/img/avatars/camera.jpg')}}" alt="App Image" class="rounded me-3 w-px-100 fiximg">
                @endif
                <div class="w-100">
                  <div class="d-flex justify-content-between">
                    <div class="user-info">
                      <h6 class="mb-1">{{ $nome }}</h6>
                      <small>{{$nome}}</small>
                      <div class="user-status">
                          <small>{{ $descricao }}</small>
                      </div>
                    </div>
                    <div class="add-btn">
                      <button type="button" class="btn btn-outline-info btn-sm waves-effect waves-light" wire:click="showForm(1)"><i class="ti ti-pencil"  style="font-size: 14px;"></i> Editar</button>                    
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
    @endif
</div>