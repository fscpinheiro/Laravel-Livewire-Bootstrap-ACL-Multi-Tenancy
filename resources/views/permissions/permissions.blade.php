@extends('layouts.master')
@section('title', 'Gerencia de Permissões')
@section('styles')
.select2-results__option .badge-dot {
    vertical-align: middle;
    margin-top: -5px !important;
    margin-right: 8px !important;
}
@endsection
@section('conteudo')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                    <h5 class="card-title mb-sm-0 me-2">Gestão Habilidades nos Apps</h5>
                    <div class="action-btns">
                        <a class="btn btn-label-primary me-3" href="{{ Route('home')}}" title="Voltar para o painel">
                            <span class="align-middle"> <i class='fa fa-dashboard'></i></span>
                        </a>
                        <button class="btn btn-primary" type="button" title="Nova Permissão Usuário" onclick="Livewire.emit('novoPermission')"><i class='fa fa-add'></i></button>
                        <button type="button" class="btn btn-info" title="Atualizar tabela" onclick="Livewire.emit('updateRoleTable')"><i class='fa fa-refresh'></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mx-auto p-4">
                            @livewire($canConsult ? 'permission-table2' : 'aviso-tipo1')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addPermission" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    @livewire('l-w-permission')
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
    function initializeSelect2() {
        const select2 = $('.select2');
        if (select2.length) {
            select2.each(function () {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Escolha um opção',
                    dropdownParent: $this.parent(),
                    templateResult: formatState
                }).on('change', function (e) {
                    if (this.id === 'app_id_select') {
                        Livewire.emit('updatedAppId', this.value);
                    }
                });
            });
        }
    }

    window.addEventListener('livewire:load', function () {
        initializeSelect2();
        Livewire.hook('message.processed', (message, component) => {
            $('.select2').each(function () {
                if ($(this).data('select2')) {
                    $(this).select2('destroy');
                }
                $(this).select2({
                    placeholder: 'Escolha uma opção',
                    dropdownParent: $(this).parent(),
                    templateResult: formatState
                });
            });
        });
    });

    window.addEventListener('abrirModalEdit', (event) => {
        const cliente = event.detail.cliente;
        $('#addPermission').modal('show');
    });

   window.addEventListener('abrirModalNovo', () => {
        $('#addPermission').modal('show');
    });

    window.addEventListener('closeModal', event => {
        $('#addPermission').modal('hide');
    });

    window.addEventListener('alert', event => {
        Swal.fire({
            icon: event.detail.type,
            title: event.detail.text,
            showConfirmButton: false,
            timer: event.detail.time
        });
    });  

    function formatState(state) {
        if (!state.id) {
            return state.text;
        }
        
        var tipo = $(state.element).closest('select').data('tipo');
        var adorno = $(state.element).data('adorno');
        var $state = $('<span>' + state.text + '</span>');
        if (tipo === 'cor' && adorno) {
            if (!adorno) {
                adorno = '#fff';
            }
            $state.prepend('<span class="badge badge-dot mr-2" style="background-color: ' + adorno + '"></span>');
        } else if (tipo='img') {
            if (adorno) {
                var baseUrl;
                if (state.element.parentElement.id === 'app_id_select') {
                    baseUrl = "/storage/icone_uploads";
                } else {
                    baseUrl = "/storage/image_uploads";
                }
                $state.prepend('<img src="' + baseUrl + '/' + adorno + '" class="img-flag" width="24px"/> ');
            } else {
                var text = state.text;
                var matches = text.match(/\b(\w)/g);
                var initials = matches.join('').substr(0, 2).toUpperCase();
                $state.prepend('<div style="display:inline-block;width:24px;height:24px;background-color:rgba(130, 134, 139, 0.12);color:#82868b;font-size:12px;text-align:center;line-height:24px;border-radius:50%;margin-right:5px;">'+initials+'</div>');
            }
        }
        return $state;
    }
</script>
@endsection