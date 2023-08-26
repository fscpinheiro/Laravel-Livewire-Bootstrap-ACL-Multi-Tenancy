@extends('layouts.master')
@section('title', 'Dashboard')
@section('styles')

@endsection
@section('conteudo')
<div class="container-fluid flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Painel Desativado</h4>
   
   
</div>
@endsection
@section('vendorscripts')
@endsection
@section('scripts')
    @if (session('error'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: 'error',
                    title: "{{ session('error') }}",
                    showConfirmButton: false,
                    timer: 3500
                });
            });
        </script>
    @endif
@endsection
