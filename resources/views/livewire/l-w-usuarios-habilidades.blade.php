<div class="row">
    <div class="col-12 mx-auto p-4">
        <form id="UHForm" class="row g-3" autocomplete="off">
            <div class="col-12">
                <label class="form-label" for="cliente_id">Cliente</label>
                <select id="cliente_id_select" wire:key="cliente_id_select" wire:model.defer="cliente_id" wire:ignore  class="select2 form-select" title="Escolha um Cliente para criar um papel">
                    <option value="">Escolha uma opção</option>
                    @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->razaosocial }} </option>
                    @endforeach
                </select>
                @error('cliente_id')
                    <div class="alert alert-danger mt-3">{{ $message }}</div>
                @enderror
            </div>    
            <div class="col-6">
                <label class="form-label" for="clienteapp_id">Apps do Cliente</label>
                <select id="clienteapp_id_select" wire:key="clienteapp_id_select" wire:model.defer="clienteapp_id" class="select2 form-select" title="Escolha um app para listar as Habilidades do App">
                    <option value="">Escolha uma opção</option>
                    @foreach ($clienteApps as $clienteApp)
                    <option value="{{ $clienteApp->id }}">{{ $clienteApp->nome }} </option>
                    @endforeach
                </select>
                @error('clienteapp_id')
                    <div class="alert alert-danger mt-3">{{ $message }}</div>
                @enderror
            </div> 
            <div class="col-6">
                <label class="form-label" for="usuario_id">Usuários</label>
                <select id="usuario_id_select" wire:key="usuario_id_select" wire:model.defer="usuario_id" class="select2 form-select" title="Escolha um Usuário">
                    <option value="">Escolha uma opção</option>
                    @foreach ($usuarios as $usuario)
                    <option value="{{ $usuario->id }}">{{ $usuario->name }} </option>
                    @endforeach
                </select>
                @error('usuario_id')
                    <div class="alert alert-danger mt-3">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-6">
                <div class="card border h-100">
                    <div class="card-header">
                        <h5 class="card-title">Habilidades Disponíveis</h5>
                        <hr>
                    </div>
                    <div class="card-content">
                        <div class="card-body  card-scroll">
                            <div id="all_habilidades_list" class="list-group">
                                @if ($HabilidadesDisponiveis->isEmpty())
                                    <p>Nenhuma habilidade disponível.</p>
                                @else
                                    @foreach ($HabilidadesDisponiveis as $Habilidade)
                                        <a href="#" class="list-group-item list-group-item-action" id="{{ $Habilidade->id}}" wire:click="addHabilidade('{{ $Habilidade->id }}')">
                                            <div class="d-flex justify-content-start align-items-center mb-1">
                                                <div class="avatar me-3">
                                                    @if($Habilidade->app->icone)
                                                        <img src="{{asset('storage/icone_uploads/' .$Habilidade->app->icone)}}" alt="avatar img" height="40" width="40">
                                                    @else
                                                        @php
                                                            $nome = $Habilidade->app->nome;
                                                            preg_match_all('/\p{Lu}/u', $nome, $matches);
                                                            $hiniciais = implode('', array_slice($matches[0], 0, 2)); 
                                                        @endphp
                                                        <div class="avatar me-2"><span class="avatar-initial rounded-circle bg-label-secondary">{{$hiniciais}}</span></div>
                                                    @endif
                                                </div>
                                                <div class="profile-user-info">
                                                    <h6 class="mb-0">{{ $Habilidade->nome }}</h6>
                                                    <small class="text-muted">{{ $Habilidade->descricao }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card border h-100">
                    <div class="card-header">
                        <h5 class="card-title">Habilidades do Usuário</h5>
                        <hr>
                    </div>
                    <div class="card-content">
                        <div class="card-body  card-scroll">
                            <div id="usuario_habilidades_list" class="list-group">
                                @if ($usuarioHabilidades->isEmpty())
                                    <p>O usuário não possui habilidades diretas ainda.</p>
                                @else
                                    @foreach ($usuarioHabilidades as $uhabilidade)
                                        <a href="#" class="list-group-item list-group-item-action" wire:click="removeHabilidade('{{ $uhabilidade->id }}')" >
                                            <div class="d-flex justify-content-start align-items-center mb-1">
                                                <div class="avatar me-3">
                                                    @if($uhabilidade->app->icone)
                                                        <img src="{{asset('storage/icone_uploads/' .$uhabilidade->app->icone)}}" alt="avatar img" height="40" width="40">
                                                    @else
                                                        @php
                                                            $nome = $uhabilidade->app->nome;
                                                            preg_match_all('/\p{Lu}/u', $nome, $matches);
                                                            $iniciais = implode('', array_slice($matches[0], 0, 2)); 
                                                        @endphp
                                                        <div class="avatar me-2"><span class="avatar-initial rounded-circle bg-label-secondary">{{$iniciais}}</span></div>
                                                    @endif
                                                </div>
                                                <div class="profile-user-info">
                                                    <h6 class="mb-0">{{ $uhabilidade->nome }}</h6>
                                                    <small class="text-muted">{{ $uhabilidade->app->nome }}</small>
                                                </div>
                                                <div class="form-check form-switch ms-auto">
                                                    <input class="form-check-input" type="checkbox" wire:model="permitido.{{ $uhabilidade->id }}" wire:change="updatePermitido('{{ $uhabilidade->id }}')" wire:click.stop data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $this->permitido[$uhabilidade->id] ? 'Negar permissão' : 'Conceder permissão' }}">
                                                </div>
                                                
                                            </div>
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
            

        </form>
    </div>
</div>