<div>
    <ul class="nav nav-tabs" role="tablist">
        @foreach ($tabs as $tab)
            <li class="nav-item">
                <a class="nav-link {{ $activeTab === $tab['id'] ? 'active' : '' }}" id="{{ $tab['id'] }}-tab" data-bs-toggle="tab" href="#{{ $tab['id'] }}" aria-controls="{{ $tab['id'] }}" role="tab" aria-selected="{{ $activeTab === $tab['id'] }}" wire:click="$set('activeTab', '{{ $tab['id'] }}')">
                    @if (isset($tab['icon']))
                        <i class="{{ $tab['icon'] }} me-2"></i>
                    @endif
                    {{ $tab['title'] }}
                </a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content">
        @foreach ($tabs as $tab)
            @if ($activeTab === $tab['id'])
                <div class="tab-pane active" id="{{ $tab['id'] }}" aria-labelledby="{{ $tab['id'] }}-tab" role="tabpanel">
                    @if (isset($tab['component']))
                        @livewire($tab['component'], $tab['params'] ?? [])
                    @elseif (isset($tab['view']))
                        @include($tab['view'], $tab['params'] ?? [])
                    @else
                        {!! $tab['content'] !!}
                    @endif
                </div>
            @endif
        @endforeach
    </div>
    
    <script>
    window.addEventListener('livewire:load', function () {
        Livewire.hook('message.processed', (message, component) => {
            // ...
            $('#'+component.get('activeTab')+'-tab').tab('show');
        });
    });
    </script>
</div>
