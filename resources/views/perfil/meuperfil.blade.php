@extends('layouts.master')
@section('title', 'Meu Perfil')
@section('styles')

@endsection
@section('conteudo')
<div class="container-fluid flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Meu Perfil</h4>
    <hr>
    <div class="row">
        @livewire('l-w-teste')
    </div>
   
</div>
@endsection
@section('vendorscripts')    
@endsection
@section('scripts')

@endsection