<div>
    <style>
        .select2-results__option .badge-dot {
            vertical-align: middle;
            margin-top: -5px !important;
            margin-right: 8px !important;
        }
    </style>

    <select id="{{ $selectId }}" wire:key="{{ $selectId }}" data-tipo="{{ $tipo }}" wire:model.defer="selected" class="select2 form-select" title="{{ $title }}">
        <option value="">Escolha uma opção</option>
        @foreach ($options as $option)
            <option value="{{ $option['value'] }}" data-adorno="{{ isset($option['adorno']) ? $option['adorno'] : null }}">{{ $option['text'] }}</option>
        @endforeach
    </select>

    <script>
        function initializeSelect2() {
            console.log('initializeSelect2 called');
            const select2 = $('.select2');
            if (select2.length) {
                select2.each(function () {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Escolha um opção',
                        dropdownParent: $this.parent(),
                        templateResult: formatState
                    }).on('change', function (e) {
                        @this.call('emitSelectedEvent', e.target.value);
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
                        placeholder: 'Escolha um item',
                        dropdownParent: $(this).parent(),
                        templateResult: formatState
                    });
                });
            });
        });

        function formatState(state) {
            if (!state.id) {
                return state.text;
            }
            var tipo = $(state.element).closest('select').data('tipo');
            var adorno = $(state.element).data('adorno');
            var $state = $('<span>' + state.text + '</span>');
            if (tipo === 'cor') {
                if (!adorno) {
                    adorno = '#ffffff'; // valor padrão para cor
                }
                $state.prepend('<span class="badge badge-dot mr-2" style="background-color: ' + adorno + '"></span>');
            } else if (tipo === 'img') {
                if (adorno) {
                    var baseUrl;
                    if (state.element.parentElement.id === 'clienteapp_id_select') {
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
    
</div>