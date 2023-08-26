<div>
    <div class="text-center mb-4">
      <h3 class="address-title mb-2">{{ $modal_title }}</h3>
      <p class="text-muted address-subtitle">{{ $modal_subtitle }}</p>
    </div>
    
      <form id="ClienteForm" class="row g-3 {{ $form_show != 1 ? 'd-none' : '' }}" autocomplete="off"  wire:submit.prevent="saveCliente">
        <hr>
        <input type="hidden" name="id" wire:model.defer="id">
        <div class="col-12">
          <label class="form-label" for="logo">Logo</label>
          <input type="file" name="logo" id="logo" class="d-none" wire:model="logo">
          <label for="logo" class="d-block mx-auto w-px-150 h-px-100 border rounded overflow-hidden text-center" id="logo-preview">
            @if($logo instanceof \Illuminate\Http\UploadedFile)
              <img src="{{ $logo->temporaryUrl() }}" alt="Logo Image" class="w-150 h-100">
            @elseif(is_string($logo))
              <img src="{{ asset('storage/image_uploads/' . $logo) }}" alt="Logo Image" class="w-150 h-100">
            @else            
              <img src="{{Vite::asset('resources/assets/img/avatars/camera.jpg')}}" alt="Logo" class="h-100">
              <div class="text-center">
                <span class="font-weight-bold">Selecionar Imagem</span>
              </div>
            @endif
          </label>
          @error('logo')
            <div class="alert alert-danger mt-3">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-12">
          <div class="row">
            <div class="col-md mb-md-0 mb-2">
              <div class="form-check custom-option custom-option-basic">
                <label class="form-check-label custom-option-content" for="pessoafisica">
                  <input name="tipodecliente" class="form-check-input" type="radio" value="PF" id="pessoafisica" checked wire:model.defer="tpcliente" wire:change="atualizaMascaraDocumento"/>
                  <span class="custom-option-header">
                    <span class="h6 mb-0">Pessoa Física</span>
                    <span class="text-muted">CPF</span>
                  </span>
                </label>
              </div>
            </div>
            <div class="col-md">
              <div class="form-check custom-option custom-option-basic">
                <label class="form-check-label custom-option-content" for="pessoajuridica">
                  <input name="tipodecliente" class="form-check-input" type="radio" value="PJ" id="pessoajuridica" wire:model.defer="tpcliente" wire:change="atualizaMascaraDocumento"/>
                  <span class="custom-option-header">
                    <span class="h6 mb-0">Pessoa Jurídica</span>
                    <span class="text-muted">CNPJ</span>
                  </span>
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12">
          <label class="form-label" for="razaosocial">Denominação Social</label>
          <input type="search" class="form-control" title="Informe a denominação social do cliente" autocomplete="off" wire:model.defer="razaosocial"/>
          @error('razaosocial')
            <div class="alert alert-danger mt-3">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-12">
          <label class="form-label" for="fantasia">Nome Fantasia</label>
          <input  type="text"  wire:model.defer="fantasia" class="form-control" placeholder="" title="Informe o nome fantasia do cliente"/>
          @error('fantasia')
            <div class="alert alert-danger mt-3">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-12 col-md-6">
          <label class="form-label" for="situacao">Situação</label>
          
          <select id="situacao_select" wire:key="situacao_select" wire:model.defer="situacao" class="select2 form-select" data-allow-clear="true" title="Defina a situação inicial do cliente">
            <option value="">Escolha uma opção</option>
            @foreach($situacoes as $option)
              <option value="{{ $option['value'] }}" data-color="{{ isset($option['cor']) ? $option['cor'] : 'bg-light' }}">{{ $option['text'] }}</option>
            @endforeach
          </select>
          @error('situacao')
            <div class="alert alert-danger mt-3">{{ $message }}</div>
          @enderror
        </div>      
        <div class="col-12 col-md-6">
          <label class="form-label" for="modalAddressLandmark">Documento</label>
          <input type="text" id="clidoc" wire:model.defer="documento" class="form-control" placeholder="" title="Informe o número CNPJ:000.000.000/0000-00 / CPF:000.000.000-00 do cliente" wire:ignore data-mask="{{ $documentoMascara }}"/>
          @error('documento')
            <div class="alert alert-danger mt-3">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-12 d-flex justify-content-between mt-4">
          <button class="btn btn-label-secondary btn-prev waves-effect" data-bs-dismiss="modal" aria-label="Close" wire:click="resetForm">
            <i class="fa-solid fa-stop ti-xs me-1"></i>
            <span class="align-middle d-sm-inline-block d-none"> Cancelar</span>
          </button>
          <button class="btn btn-success btn-next waves-effect waves-light" type="submit">
            <i class="fa-solid fa-cloud-arrow-up ti-xs me-1"></i>
            <span class="align-middle d-sm-inline-block d-none me-sm-1"> Salvar</span>        
          </button>
        </div>
      </form>
    
      <form id="ClienteForm2" class="row g-3 {{ $form_show != 2 ? 'd-none' : '' }}" autocomplete="off"  wire:submit.prevent="deleteCliente">
        <div class="col-12">
          
          <div class="demo-inline-spacing mt-3">
            <div class="list-group">
              <div class="list-group-item list-group-item-action d-flex align-items-center cursor-pointer">
                @if($logo_txt)
                <img src="{{ asset('storage/image_uploads/' . $logo_txt) }}" alt="User Image" class="rounded me-3 w-px-100">
                @else
                <img src="{{Vite::asset('resources/assets/img/avatars/camera.jpg')}}" alt="User Image" class="rounded me-3 w-px-100">
                @endif
                <div class="w-100">
                  <div class="d-flex justify-content-between">
                    <div class="user-info">
                      <h6 class="mb-1">{{ $fantasia_txt }}</h6>
                      <small>{{$razaosocial_txt}}</small>
                      <div class="user-status">
                          <span class="badge badge-dot {{$color_st_txt}}"></span>
                          <small>{{ $situacao_txt }}</small><br>
                          <small>{{$cliente_id}}</small>
                      </div>
                    </div>
                    <div class="add-btn">
                      <button type="button" class="btn btn-outline-info btn-sm waves-effect waves-light" wire:click="editarAux('{{$cliente_id}}')"><i class="ti ti-pencil"  style="font-size: 14px;"></i> Editar</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>        
        

        <div class="col-12 d-flex justify-content-between mt-4">
          <button class="btn btn-label-secondary btn-prev waves-effect" data-bs-dismiss="modal" aria-label="Close" wire:click="resetForm">
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