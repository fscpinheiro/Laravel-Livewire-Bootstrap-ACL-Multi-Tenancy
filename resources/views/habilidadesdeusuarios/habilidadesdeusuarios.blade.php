@extends('layouts.master')
@section('title', 'Gestão das Habilidades dos Usuários')
@section('styles')
<style>
    .card-scroll {
        overflow-y: auto;
        height: 400px; /* ajuste a altura de acordo com suas necessidades */
    }
    .card-scroll::-webkit-scrollbar-track{
        background-color: lightgray;
    }
    .card-scroll:hover::-webkit-scrollbar{
    display: block;
    }
    .card-scroll::-webkit-scrollbar{
        width: 3px;
        background-color: #F5F5F5;
        display:none;
    }

    .card-scroll::-webkit-scrollbar-thumb{
        background-color: #000000;
    }
    .form-check-input:checked, .form-switch .form-check-input:checked {
        background-color: rgb(7, 255, 32) !important;
        border-color: green !important;
    }
    .form-check-input:not(:checked) {
        background-color: red !important;
        border-color: red !important;
    }
    .form-switch .form-check-input:focus:not(:checked) {
        background-image: url("data:image/svg+xml,%3Csvg width='18' height='18' viewBox='0 0 18 18' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='9' cy='9' r='6' fill='%23fff'/%3E%3C/svg%3E%0A") !important;
    }
    
    
</style>
@endsection
@section('conteudo')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                    <h5 class="card-title mb-sm-0 me-2">Gestão das Habilidades dos Usuários</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mx-auto p-4">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="cadastro-tab" data-bs-toggle="tab" href="#cadastro" aria-controls="cadastro" role="tab" aria-selected="true"><i class="fa-regular fa-pen-to-square me-2"></i> Cadastro</a>
                                </li>
                                <li class="nav-item"> 
                                    <a class="nav-link" id="consulta-tab" data-bs-toggle="tab" href="#consulta" aria-controls="consulta" role="tab" aria-selected="false"><i class="fa-solid fa-magnifying-glass me-2"></i> Consulta</a>
                                </li>
                                <li class="nav-item"> 
                                    <a class="nav-link" id="dados-tab" data-bs-toggle="tab" href="#dados" aria-controls="dados" role="tab" aria-selected="false"><i class="fa-solid fa-chart-area me-2"></i> Dados</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="cadastro" aria-labelledby="cadastro-tab" role="tabpanel">
                                    @livewire($canConsult ? 'l-w-usuarios-habilidades' : 'aviso-tipo1')                                        
                                </div>
                                <div class="tab-pane" id="consulta" aria-labelledby="consulta-tab" role="tabpanel">
                                    <p>
                                        Dragée jujubes caramels tootsie roll gummies gummies icing bonbon. Candy jujubes cake cotton candy.
                                        Jelly-o lollipop oat cake marshmallow fruitcake candy canes toffee. Jelly oat cake pudding jelly beans
                                        brownie lemon drops ice cream halvah muffin. Brownie candy tiramisu macaroon tootsie roll danish.
                                    </p>
                                    <p>
                                        Croissant pie cheesecake sweet roll. Gummi bears cotton candy tart jelly-o caramels apple pie jelly
                                        danish marshmallow. Icing caramels lollipop topping. Bear claw powder sesame snaps.
                                    </p>
                                </div>
                                <div class="tab-pane" id="dados" aria-labelledby="dados-tab" role="tabpanel">
                                    <p>
                                        Gingerbread cake cheesecake lollipop topping bonbon chocolate sesame snaps. Dessert macaroon bonbon
                                        carrot cake biscuit. Lollipop lemon drops cake gingerbread liquorice. Sweet gummies dragée. Donut bear
                                        claw pie halvah oat cake cotton candy sweet roll. Cotton candy sweet roll donut ice cream.
                                    </p>
                                    <p>
                                        Halvah bonbon topping halvah ice cream cake candy. Wafer gummi bears chocolate cake topping powder.
                                        Sweet marzipan cheesecake jelly-o powder wafer lemon drops lollipop cotton candy.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('vendorscripts')
@endsection
@section('scripts')
<script>
    const select2 = $('.select2');
    if (select2.length) {
        select2.each(function () {
            var $this = $(this);
            $this.wrap('<div class="position-relative"></div>').select2({
                placeholder: 'Escolha uma opção',
                dropdownParent: $this.parent()
            }).on('change', function (e) {
                if (this.id === 'cliente_id_select') {
                    Livewire.emit('updatedClienteId', this.value);
                }
                if (this.id === 'usuario_id_select') {
                    Livewire.emit('updatedUsuarioId', this.value);
                }
                if (this.id === 'clienteapp_id_select'){
                    Livewire.emit('updatedClienteAppId', this.value);
                }
            });
        });
    }

    window.addEventListener('livewire:load', function () {
        Livewire.hook('message.processed', (message, component) => {
            $('.select2').each(function () {
                if ($(this).data('select2')) {
                    $(this).select2('destroy');
                }
                $(this).select2({
                    placeholder: 'Escolha uma opção',
                    dropdownParent: $(this).parent()
                });
            });
        });
    });

    window.addEventListener('alert', event => {
        Swal.fire({
            icon: event.detail.type,
            title: event.detail.text,
            showConfirmButton: false,
            timer: event.detail.time
        });
    });
</script>
@endsection