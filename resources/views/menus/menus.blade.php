@extends('layouts.master')
@section('title', 'Menu do Cliente')
@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css">
<style>
    .nestable-item {
        height: 60px !important;
        padding: 10px 0; 
    }
    .nestable-buttons {
        /* padding-right: 10px; /* ajuste o valor de acordo com suas necessidades */
        position: absolute;
        top: 5px;
        right: 5px;
    }

    .drag-icon {
        margin-right: 15px;
        margin-left: 10px;
        cursor: grab;
    }
    .drag-icon:active {
        cursor: grabbing;
    }

</style>
@endsection
@section('conteudo')
<div class="container-fluid flex-grow-1 container-p-y">
    @livewire($canConsult ? 'l-w-menu' : 'aviso-tipo1')         
</div>
@endsection
@section('vendorscripts')
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js"></script>
<script>   
    //SELECT
    const select2 = $('.select2');
    if (select2.length) {
        select2.each(function () {
            var $this = $(this);
            $this.wrap('<div class="position-relative"></div>').select2({
                placeholder: 'Escolha um item',
                dropdownParent: $this.parent(),
                templateResult: formatState 
            }).on('change', function (e) {
                if (this.id === 'cliente_id_select') {
                    Livewire.emit('updatedClienteId', this.value);
                }
                if(this.id === 'clienteapp_id_select'){
                    Livewire.emit('updatedClienteAppId', this.value);
                }
            });
        });
    }
    //FUNCTION CUSTOM SELECT
    function formatState (state) {
        if (!state.id) {
            return state.text;
        }
        var baseUrl;

        if (state.element.parentElement.id === 'clienteapp_id_select') {
            baseUrl = "/storage/icone_uploads";
        } else {
            baseUrl = "/storage/image_uploads";
        }
        var imageName = $(state.element).data('image');
        if(imageName === null || imageName === ''){
            var text = state.text;
            var matches = text.match(/\b(\w)/g);
            var initials = matches.join('').substr(0, 2).toUpperCase();
            var $state = $(
                '<span><div style="display:inline-block;width:24px;height:24px;background-color:rgba(130, 134, 139, 0.12);color:#82868b;font-size:12px;text-align:center;line-height:24px;border-radius:50%;margin-right:5px;">'+initials+'</div>'+state.text+'</span>'
            );
        }else{
            var $state = $(
                '<span><img src="' + baseUrl + '/' + imageName + '" class="img-flag" width="24px"/> ' + state.text + '</span>'
            );    
        }
        return $state;
    };
    //CUSTOM SELECT
    window.addEventListener('livewire:load', function () {
        Livewire.hook('message.processed', (message, component) => {
            $('.select2').each(function () {
                if ($(this).data('select2')) {
                    $(this).select2('destroy');
                }
                $(this).select2({
                    placeholder: 'Escolha um item',
                    dropdownParent: $(this).parent(),
                    templateResult: formatState 
                });
            });
        });
    });
    //ORDER SORT LIST
    $(function() {
        $('.dd').nestable({
            // Opções e eventos aqui
        });
    });

    function processItem(items, parentId = null) {
        items.forEach(function(item, index) {
            item.posicao = index;
            item.parentId = parentId;
            console.log("======================");
            console.log("item.id: "+item.id);
            console.log("item.posicao: "+index);
            console.log("item.parentId: "+parentId);
            console.log("======================");
            if (item.children) {
                processItem(item.children, item.id);
            }
        });
    }

    $('#saveOmenu').on('click', function() {
        var data = $('.dd').nestable('serialize');
        processItem(data);
        console.log("data:");
        console.log(data);
        Livewire.emit('updateMenuOrder', data);
    });

    //Paste Icon
    document.getElementById('btpasteicone').addEventListener('click', function() {
        navigator.clipboard.readText().then(function(text) {
            document.querySelector('input[wire\\:model\\.defer="itemicon"]').value = text;
            document.getElementById('iconview').innerHTML = text;
            document.querySelector('input[wire\\:model\\.defer="itemicon"]').dispatchEvent(new Event('input'));
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
    
    document.addEventListener('click', function(event) {
        if (event.target.matches('.edit-button')) {
            let id = event.target.getAttribute('data-id');
            console.log('editItem: '+id);
            Livewire.emit('editItem', id);
            

        } else if (event.target.matches('.delete-button')) {
            let id = event.target.getAttribute('data-id');
            console.log('deleteItem: '+id);
            Livewire.emit('deleteItem', id);
        }
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