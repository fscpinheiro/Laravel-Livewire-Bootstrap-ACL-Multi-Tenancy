<div>
    <div class="col-12">
        <label class="form-label" for="cliente_id">Cliente</label>
        @livewire('comp-selekt', [
            'options' => $clientes,
            'selected' => 1,
            'tipo' => 'img',
            'selectId' => 'cliente_id_select',
            'title' => 'Escolha uma opção',
            'emitEventName' => 'updatedClienteId'
        ])
    </div>
</div>
