<div>
    <div class="card">
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Filtros
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-8 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="cliente_id">Cliente</label>
                                    <select id="cliente_id_select" wire:key="cliente_id_select" wire:model.defer="cliente_id"  class="select2 form-select" title="Escolha um Cliente para criar um papel">
                                        <option value="">Escolha uma opção</option>
                                        @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->id }}"  data-image="{{ $cliente->logo }}">{{ $cliente->razaosocial }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="mb-1">
                                    <label class="form-label">Periodo</label>
                                    <input type="text" id="date-range" class="form-select" placeholder="Selecione um período" wire:model.defer="rangedate">
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-between mt-4">
                                <button class="btn btn-label-secondary btn-prev waves-effect" wire:click="removefilters">
                                  <i class="fa-solid fa-stop ti-xs me-1"></i>
                                  <span class="align-middle d-sm-inline-block d-none"> Limpar</span>
                                </button>
                                <button class="btn btn-info btn-next waves-effect waves-light" wire:click="applyfilters">
                                  <i class='fa fa-search'></i> 
                                  <span class="align-middle d-sm-inline-block d-none me-sm-1">  Aplicar</span>        
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
    
</div>
