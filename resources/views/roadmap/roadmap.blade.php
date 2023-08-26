@extends('layouts.master')
@section('title', 'Road Map')
@section('styles')

<style>
    

</style>
@endsection
@section('conteudo')

<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                    <h5 class="card-title mb-sm-0 me-2">RoadMap</h5>
                    <div class="action-btns">
                        <a class="btn btn-label-primary me-3" href="{{ Route('home')}}" title="Voltar para o painel">
                            <span class="align-middle"> <i class='fa fa-dashboard'></i></span>
                        </a>
                    </div>                    
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mx-auto p-4">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="lista-tab" data-bs-toggle="tab" href="#lista" aria-controls="cadastro" role="tab" aria-selected="true" onclick="setActiveTab('lista')"><i class="fa-regular fa-pen-to-square me-2"></i> Lista de Features</a>
                                </li>
                                <li class="nav-item"> 
                                    <a class="nav-link" id="cadastro-tab" data-bs-toggle="tab" href="#cadastro" aria-controls="consulta" role="tab" aria-selected="false" onclick="setActiveTab('cadastro')"><i class="fa-solid fa-magnifying-glass me-2"></i> Cadastro</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="lista" aria-labelledby="lista-tab" role="tabpanel">
                                    @livewire('road-map-table')                            
                                </div>
                                <div class="tab-pane" id="cadastro" aria-labelledby="cadastro-tab" role="tabpanel">
                                    @livewire($canConsult ? 'l-w-road-map' : 'aviso-tipo1')   
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
    function setActiveTab(tab) {
        document.cookie = 'activeTab=' + tab;
    }

    document.addEventListener('DOMContentLoaded', function () {
        let activeTab = getCookie('activeTab');
        if (activeTab) {
            $('#' + activeTab + '-tab').tab('show');
        }
    });

    function getCookie(name) {
        let value = '; ' + document.cookie;
        let parts = value.split('; ' + name + '=');
        if (parts.length === 2) return parts.pop().split(';').shift();
    }

    const select2 = $('.select2');
    if (select2.length) {
        select2.each(function () {
            var $this = $(this);
            $this.wrap('<div class="position-relative"></div>').select2({
                placeholder: 'Escolha um item',
                dropdownParent: $this.parent()
            }).on('change', function (e) {
                if(this.id === 'situacao_id_select'){
                    Livewire.emit('updatedSituacaoFeature', this.value);
                }
                if(this.id === 'categoria_id_select'){
                    Livewire.emit('updatedCategoriaFeature', this.value);
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
                    placeholder: 'Escolha um item',
                    dropdownParent: $(this).parent()
                });
            });
        });

        $('.flatpickr-date').flatpickr({
            dateFormat: "d-m-Y"
        });       

        window.addEventListener('alert', event => {
            Swal.fire({
                icon: event.detail.type,
                title: event.detail.text,
                showConfirmButton: false,
                timer: event.detail.time
            });
        });  
    });  

    window.addEventListener('show-delete-confirmation', event => {
        Swal.fire({
            title: 'Tem certeza?',
            text: "Você não poderá reverter isso!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, exclua!'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emit('deleteItemConfirmed');
            }
        });
    });

    window.addEventListener('gocadastro', (event) => {
        const roadmap = event.detail.roadmap;
        $('#cadastro-tab').tab('show');
        $('.flatpickr-date').flatpickr({
            dateFormat: "d-m-Y"
        });  
    });
    
</script>
@endsection