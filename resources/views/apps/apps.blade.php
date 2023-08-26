@extends('layouts.master')
@section('title', 'Gerenciamento de Apps')
@section('styles')
<style>
    .fiximg{
        object-fit: contain;
    }
    
</style>
@endsection
@section('conteudo')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                    <h5 class="card-title mb-sm-0 me-2">Gestão de Apps</h5>
                    <div class="action-btns">
                        <a class="btn btn-label-primary me-3" href="{{ Route('home')}}" title="Voltar para o painel">
                            <span class="align-middle"> <i class='fa fa-dashboard'></i></span>
                        </a>
                        <button class="btn btn-primary" type="button" title="Novo App" onclick="Livewire.emit('novoApp')"><i class='fa fa-add'></i></button>
                        <button type="button" class="btn btn-info" title="Atualizar tabela" onclick="Livewire.emit('updateAppTable')"><i class='fa fa-refresh'></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mx-auto p-4">
                            @livewire($canConsult ? 'app-table2' : 'aviso-tipo1')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addApp" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                @livewire('l-w-app')
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
     window.addEventListener('abrirModalEdit', (event) => {
        const app = event.detail.app;
        $('#addApp').modal('show');
    });

    window.addEventListener('abrirModalNovo', () => {
        $('#addApp').modal('show');
    });

    window.addEventListener('closeModal', event => {
        $('#addApp').modal('hide');
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