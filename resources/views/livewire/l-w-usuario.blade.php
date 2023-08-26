<div>
    <div class="text-center mb-4">
      <h3 class="address-title mb-2">{{ $modal_title }}</h3>
      <p class="text-muted address-subtitle">{{ $modal_subtitle }}</p>
    </div>
    
    <form id="UserForm" class="row g-3 {{ $form_show != 1 ? 'd-none' : '' }}" autocomplete="off"  wire:submit.prevent="saveUser">
      <hr>
      <input type="hidden" name="id" wire:model="id">
      <div class="col-12">
        <label class="form-label" for="cliente_id">Cliente</label>
        <select id="cliente_id_select" wire:key="cliente_id_select" data-tipo="img" wire:model.defer="cliente_id"  class="select2 form-select" title="Escolha um Cliente para criar um papel">
          <option value="">Escolha uma opção</option>
          @foreach ($clientes as $cliente)
            <option value="{{ $cliente->id }}" data-adorno="{{ $cliente->logo }}">{{ $cliente->razaosocial }} </option>
          @endforeach
        </select>
        @error('cliente_id')
          <div class="alert alert-danger mt-3">{{ $message }}</div>
        @enderror
      </div>    
      <div class="col-12">
        <label class="form-label" for="role_id">Papel</label>
        <select id="role_id_select" wire:key="role_id_select" data-tipo="cor" wire:model.defer="role_id"  class="select2 form-select" title="Escolha um Papel para o usuário">
          <option value="">Escolha uma opção</option>
          @if($roles)
            @foreach ($roles as $role)
              <option value="{{ $role->id }}" data-adorno="{{ isset($role->cor) ? $role->cor : '#f8f9fa' }}">{{ $role->nome }} </option>
            @endforeach
          @endif
        </select>
        @error('role_id')
          <div class="alert alert-danger mt-3">{{ $message }}</div>
        @enderror
      </div>    
      <div class="col-12">
        <label class="form-label" for="perfil">Perfil</label>
        <input type="file" name="perfil" id="perfil" class="d-none" wire:model="perfil">
        <label for="perfil" class="d-block mx-auto w-px-150 h-px-100 border rounded overflow-hidden text-center" id="perfil-preview">
          @if($perfil instanceof \Illuminate\Http\UploadedFile)
            <img src="{{ $perfil->temporaryUrl() }}" alt="Perfil" class="w-150 h-100">
          @elseif(is_string($perfil))
            <img src="{{ asset('storage/perfil_uploads/' . $perfil) }}" alt="Perfil" class="w-150 h-100">
          @else            
            <img src="{{Vite::asset('resources/assets/img/avatars/camera.jpg')}}" alt="Perfil" class="h-100">
            <div class="text-center">
              <span class="font-weight-bold">Selecionar Imagem</span>
            </div>
          @endif
        </label>
        @error('perfil')
          <div class="alert alert-danger mt-3">{{ $message }}</div>
        @enderror
      </div>
      <div class="col-12">
        <label class="form-label" for="nome">Nome</label>
        <input type="search" class="form-control" title="Nome do usuário" autocomplete="off" wire:model.defer="nome"/>
        @error('nome')
          <div class="alert alert-danger mt-3">{{ $message }}</div>
        @enderror
      </div>
      <div class="col-12">
        <label class="form-label" for="email">E-mail</label>
        <input type="search" class="form-control" title="E-mail do Usuário" autocomplete="off" wire:model.defer="email"/>
        @error('email')
          <div class="alert alert-danger mt-3">{{ $message }}</div>
        @enderror
      </div>
      <div class="col-12 col-md-4">
        <label class="form-label" for="situacao">Situação</label>
        <select id="situacao_id_select" wire:key="situacao_id_select" data-tipo="cor" wire:model.defer="situacao" class="select2 form-select" data-allow-clear="true" title="Defina a situação inicial do Usuário">
          <option value="">Escolha uma opção</option>
          @foreach($situacoes as $option)
            <option value="{{ $option['value'] }}"  data-adorno="{{ isset($option['cor']) ? $option['cor'] : '#f8f9fa' }}">{{ $option['text'] }}</option>
          @endforeach
        </select>
        @error('situacao')
          <div class="alert alert-danger mt-3">{{ $message }}</div>
        @enderror
      </div> 
      <div class="col-8 col-md-8">
        <label class="form-label" for="senha">Senha</label>
        <div class="input-group">
          <input type="{{ $passwordInputType }}" class="form-control" title="Senha do Usuário" autocomplete="off" wire:model.defer="senha"/>
          <button class="btn btn-outline-info" type="button"  wire:click="togglePasswordVisibility" title="{{ $passwordInputType === 'password' ? 'Exibir senha' : 'Esconder senha' }}"><i class="bi bi-eye"></i></button>
          <button class="btn btn-outline-danger" type="button" wire:click="generatePassword" title="Gerar Senha"><i class='fa fa-key'></i></button>
        </div>      
        @error('senha')
          <div class="alert alert-danger mt-3">{{ $message }}</div>
        @enderror
      </div>
      <div class="col-12 d-flex justify-content-between mt-4">
        <button class="btn btn-label-secondary btn-prev waves-effect" type="reset" data-bs-dismiss="modal" aria-label="Close" wire:click="resetInputFields">
          <i class="fa-solid fa-stop ti-xs me-1"></i>
          <span class="align-middle d-sm-inline-block d-none"> Cancelar</span>
        </button>
        <button class="btn btn-success btn-next waves-effect waves-light" type="submit">
          <i class="fa-solid fa-cloud-arrow-up ti-xs me-1"></i>
          <span class="align-middle d-sm-inline-block d-none me-sm-1"> Salvar</span>        
        </button>
      </div>
     
    </form>    
    
    <form id="UserForm2" class="row g-3 {{ $form_show != 2 ? 'd-none' : '' }}" autocomplete="off"  wire:submit.prevent="deleteUser">
      <div class="col-12">

        <div class="demo-inline-spacing mt-3">
            <div class="list-group">
              <div class="list-group-item list-group-item-action d-flex align-items-center cursor-pointer">
                @if($perfil_txt)
                <img src="{{ asset('storage/perfil_uploads/' . $perfil_txt) }}" alt="User Image" class="rounded me-3 w-px-100">
                @else
                <img src="{{Vite::asset('resources/assets/img/avatars/camera.jpg')}}" alt="User Image" class="rounded me-3 w-px-100">
                @endif
                <div class="w-100">
                  <div class="d-flex justify-content-between">
                    <div class="user-info">
                      <h6 class="mb-1">{{ $nome_txt }}</h6>
                      <small>{{$email_txt}}</small>
                      <div class="user-status">
                          <span class="badge badge-dot"  style="background-color: {{$color_st_txt}}"></span>
                          <small>{{ $situacao_txt }}</small><br>
                          <small> {{$user_id}}</small>

                      </div>
                    </div>
                    <div class="add-btn">
                      <button type="button" class="btn btn-outline-info btn-sm waves-effect waves-light" wire:click="editarAux('{{$user_id}}')"><i class="ti ti-pencil"  style="font-size: 14px;"></i> Editar</button>                    
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