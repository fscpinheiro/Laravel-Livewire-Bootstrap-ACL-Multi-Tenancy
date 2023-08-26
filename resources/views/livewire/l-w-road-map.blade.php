<div>
    <form id="ClienteForm" class="row g-3" autocomplete="off"  wire:submit.prevent="saveRoadMap">
        <input type="hidden" name="id" wire:model.defer="id">
        <div class="col-12">
            <label class="form-label" for="razaosocial">Feature</label>
            <input type="search" class="form-control" title="Nome da Feature" autocomplete="off" wire:model.defer="featurename"/>
            @error('featurename')
              <div class="alert alert-danger mt-3">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label" for="situacao">Categoria</label>
            <select id="categoria_id_select" wire:key="categoria_id_select" wire:model.defer="featurecategoria" class="select2 form-select" data-allow-clear="true" title="Defina a categoria da feature">
              <option value="">Escolha uma opção</option>
              @foreach($categorias as $option)
                <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
              @endforeach
            </select>
            @error('featurecategoria')
              <div class="alert alert-danger mt-3">{{ $message }}</div>
            @enderror
        </div>   
        <div class="col-12 col-md-6">
            <label class="form-label" for="situacao">Situação</label>
            <select id="situacao_id_select" wire:key="situacao_id_select" wire:model.defer="featurestatus" class="select2 form-select" data-allow-clear="true" title="Defina a situação da feature">
              <option value="">Escolha uma opção</option>
              @foreach($situacoes as $option)
                <option value="{{ $option['value'] }}">{{ $option['text'] }}</option>
              @endforeach
            </select>
            @error('featurestatus')
              <div class="alert alert-danger mt-3">{{ $message }}</div>
            @enderror
        </div>  
        <div class="col-md-4 mb-1">
            <label class="form-label">Estimativa Término</label>
            <input type="text" class="form-control flatpickr-date" placeholder="DD-MM-YYYY" wire:model.defer="featureestimativa"/>
        </div>
        <div class="col-md-4 mb-1">
            <label class="form-label">Concluído</label>
            <input type="text" class="form-control flatpickr-date" placeholder="DD-MM-YYYY" wire:model.defer="featureconcluido"/>
        </div> 
        <div class="col-md-4 mb-1">
            <label class="form-label">Versão</label>
            <div class="input-group">
                <button class="btn btn-outline-secondary waves-effect" type="button" wire:click="decrementversion"><i class='fa fa-minus-circle'></i></button>
                <input type="text" class="form-control" placeholder="numero da versão" wire:model.defer="featureversion">
                <button class="btn btn-outline-secondary waves-effect" type="button" wire:click="incrementversion"><i class='fa fa-plus-circle'></i></button>
            </div>
        </div>
        <div class="col-12">
            <label class="form-label">Descrição</label>
            <textarea class="form-control" aria-label="With textarea" wire:model.defer="featurenotes"></textarea>
        </div>
        <div class="col-12 d-flex justify-content-between mt-4">
            <button class="btn btn-label-secondary btn-prev waves-effect"  type="reset" wire:click="resetInputFields">
              <i class="fa-solid fa-stop ti-xs me-1"></i>
              <span class="align-middle d-sm-inline-block d-none"> Cancelar</span>
            </button>
            <button class="btn btn-success btn-next waves-effect waves-light" type="submit">
              <i class="fa-solid fa-cloud-arrow-up ti-xs me-1"></i>
              <span class="align-middle d-sm-inline-block d-none me-sm-1"> Salvar</span>        
            </button>
          </div>
    </form>
</div>
