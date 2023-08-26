<div class="row">
    <div class="col-12 mx-auto p-4">
        <form id="PCForm" class="row g-3" autocomplete="off"  wire:submit.prevent="saveRole">
            <input type="hidden" name="id" wire:model="id">
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
                <div class="card border h-100">
                    <div class="card-header">
                        <h5 class="card-title">Aplicativos Disponíveis</h5>
                        <hr>
                    </div>
                    <div class="card-content">
                        <div class="card-body  card-scroll">
                            <div id="all_apps_list" class="list-group">
                                @if ($availableApps->isEmpty())
                                    <p>Não há mais aplicativos para associar.</p>
                                @else
                                    @foreach ($availableApps as $app)
                                        <a href="#" class="list-group-item list-group-item-action" id="{{ $app->id}}" wire:click="addApp('{{ $app->id }}')">
                                            <div class="d-flex justify-content-start align-items-center mb-1">
                                                <div class="avatar me-3">
                                                    @if($app->icone)
                                                        <img src="{{asset('storage/icone_uploads/' .$app->icone)}}" alt="avatar img" height="40" width="40">
                                                    @else
                                                        @php
                                                            $nome = $app->nome;
                                                            preg_match_all('/\p{Lu}/u', $nome, $matches);
                                                            $hiniciais = implode('', array_slice($matches[0], 0, 2)); 
                                                        @endphp
                                                        <div class="avatar me-2"><span class="avatar-initial rounded-circle bg-label-secondary">{{$hiniciais}}</span></div>
                                                    @endif
                                                </div>
                                                <div class="profile-user-info">
                                                    <h6 class="mb-0">{{ $app->nome }}</h6>
                                                    @php
                                                        $menus = App\Models\Menu::where('app_id', $app->id)->get();
                                                        $parentMenuNames = [];
                                                        foreach ($menus as $menu) {
                                                            $parentMenu = App\Models\Menu::find($menu->parentId);
                                                            if ($parentMenu) {
                                                                $parentMenuNames[] = $parentMenu->nome;
                                                            }
                                                        }
                                                    @endphp
                                                    @if (!empty($parentMenuNames))
                                                        <small class="text-muted"></small>{{ implode(', ', $parentMenuNames) }}</small>
                                                    @endif
                                                    <br></small><small class="text-muted">{{ $app->descricao }}</small>
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
                        <h5 class="card-title">Aplicativos do Cliente</h5>
                        <hr>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-scroll">
                            <div id="client_apps_list" class="list-group">
                                @if ($clientApps->isEmpty())
                                    <p>Nenhum aplicativo associado ainda.</p>
                                @else
                                    @foreach ($clientApps as $capp)
                                        <a href="#" class="list-group-item list-group-item-action" wire:click="removeApp('{{ $capp->id }}')">
                                            <div class="d-flex justify-content-start align-items-center mb-1">
                                                <div class="avatar me-3">
                                                    @if($capp->icone)
                                                        <img src="{{asset('storage/icone_uploads/' .$capp->icone)}}" alt="avatar img" height="40" width="40">
                                                    @else
                                                        @php
                                                            $nome = $capp->nome;
                                                            preg_match_all('/\p{Lu}/u', $nome, $matches);
                                                            $hiniciais = implode('', array_slice($matches[0], 0, 2)); 
                                                        @endphp
                                                        <div class="avatar me-2"><span class="avatar-initial rounded-circle bg-label-secondary">{{$hiniciais}}</span></div>
                                                    @endif
                                                </div>
                                                <div class="profile-user-info">
                                                    <h6 class="mb-0">{{ $capp->nome }}</h6>
                                                    @php
                                                        $menus = App\Models\Menu::where('app_id', $capp->id)->get();
                                                        $parentMenuNames = [];
                                                        foreach ($menus as $menu) {
                                                            $parentMenu = App\Models\Menu::find($menu->parentId);
                                                            if ($parentMenu) {
                                                                $parentMenuNames[] = $parentMenu->nome;
                                                            }
                                                        }
                                                    @endphp
                                                    @if (!empty($parentMenuNames))
                                                        <small class="text-muted"></small>{{ implode(', ', $parentMenuNames) }}</small>
                                                    @endif
                                                    <br></small><small class="text-muted">{{ $capp->descricao }}</small>
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