@extends('layouts.master')
@section('title', 'Gerencia de Clientes')
@section('styles')
<style>
    tr.custom-bg,
    tr.custom-bg > td {
        background-color: #ffe415; /* cinza mais claro que o bg-secondary do bootstrap */
        color: #4d4d4d; /* cinza mais escuro para o texto */
    }
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
                    <h5 class="card-title mb-sm-0 me-2">Gestão de Clientes </h5>
                    <div class="action-btns">
                      <a class="btn btn-label-primary me-3" href="{{ Route('home')}}" title="Voltar para o painel">
                        <span class="align-middle"> <i class='fa fa-dashboard'></i></span>
                      </a>
                      <button class="btn btn-primary" type="button" title="Novo Cliente" onclick="Livewire.emit('novoCliente')"><i class='fa fa-add'></i></button>
                      <button type="button" class="btn btn-info" title="Atualizar tabela" onclick="Livewire.emit('updateClienteTable')"><i class='fa fa-refresh'></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mx-auto p-4">
                            @livewire($canConsult ? 'cliente-table3' : 'aviso-tipo1')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addCliente" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    @livewire('l-w-cliente')                    
                </div>               
            </div>
        </div>
    </div>    
</div>
@endsection
@section('vendorscripts')    
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>    
    function initializeSelect2() {
        const select2 = $('.select2');
        if (select2.length) {
            select2.each(function () {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Escolha uma opção',
                    dropdownParent: $this.parent(),
                    templateResult: formatState 
                }).on('change', function (e) {
                    if (this.id === 'situacao_select') {
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
                    placeholder: 'Escolha uma opção',
                    dropdownParent: $(this).parent(),
                    templateResult: formatState
                });
            });
        });
    });   

    window.addEventListener('abrirModalEdit', (event) => {
        const cliente = event.detail.cliente;
        $('#addCliente').modal('show');
    });

   window.addEventListener('abrirModalNovo', () => {
        $('#addCliente').modal('show');
    });
        
    $(document).ready(function() {       
        $("#clidoc").on("keyup", function() {
            var tipoPessoa = $("input[name='tipodecliente']:checked").val();
            if (tipoPessoa === 'PF') {
                $("#clidoc").mask("000.000.000-00");
            } else if (tipoPessoa === 'PJ') {
                $("#clidoc").mask("000.000.000/0000-00");
            }
        });

        document.querySelectorAll('input[name="tipodecliente"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                document.querySelector('#documento').value = '';
            });
        });        
    });

    window.addEventListener('closeModal', event => {
        $('#addCliente').modal('hide');
        const dados = event.detail.dados;
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
        if (color) {
            $state.prepend('<span class="badge badge-dot mr-2 ' + color + '"></span>');
        }
        return $state;
    }
   
</script>
@endsection