@extends('layouts.master')
@section('title', 'Usuários')
@section('styles')
<style>
    .select2-results__option .badge-dot {
        vertical-align: middle;
        margin-top: -5px !important;
        margin-right: 8px !important;
    }
</style>
@endsection
@section('conteudo')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                    <h5 class="card-title mb-sm-0 me-2">Administração de Usuários</h5>
                    <div class="action-btns">
                        <a class="btn btn-label-primary me-3" href="{{ Route('home')}}" title="Voltar para o painel">
                            <span class="align-middle"> <i class='fa fa-dashboard'></i></span>
                        </a>
                        <button class="btn btn-primary" type="button" title="Novo Usuário" onclick="Livewire.emit('novoUser')"><i class='fa fa-add'></i></button>
                        <button type="button" class="btn btn-info" title="Atualizar tabela" onclick="Livewire.emit('updateUserTable')"><i class='fa fa-refresh'></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mx-auto p-4">
                            @livewire($canConsult ? 'a-d-m.adm-user-table' : 'aviso-tipo1')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                @livewire('adm.l-w-adm-usuario')
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
                    placeholder: 'Escolha um valor',
                    dropdownParent: $this.parent(),
                    templateResult: formatState
                }).on('change', function (e) {
                    if (this.id === 'role_id_select') {
                        Livewire.emit('updatedRoleId', this.value);
                    }
                    if(this.id === 'situacao_id_select'){
                        Livewire.emit('updatedSituacao', this.value);
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
                    placeholder: 'Escolha um valor',
                    dropdownParent: $(this).parent(),
                    templateResult: formatState
                });
            });
        });

        $('#addUser').on('shown.bs.modal', function () {
            document.querySelector('#togglePassword').addEventListener('click', function (e) {
                e.preventDefault();
                const passwordInput = document.querySelector('#senha');
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
            });
        });

    });

    window.addEventListener('abrirModalEdit', (event) => {
        const app = event.detail.app;
        $('#addUser').modal('show');
    });

    window.addEventListener('abrirModalNovo', () => {
        $('#addUser').modal('show');
    });

    window.addEventListener('closeModal', event => {
        $('#addUser').modal('hide');
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
        var color = $(state.element).data('color');
        var $state = $('<span>' + state.text + '</span>');
        // Adicione uma bolinha com a cor da função
        if (color) {
            $state.prepend('<span class="badge badge-dot mr-2" style="background-color: ' + color + '"></span>');
        }
        return $state;
    };
</script>
@endsection