

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ Route('home')}}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{Vite::asset('resources/assets/logo/AI_logo_sentric.svg')}}" width="32px" height="32px" />
            </span>
            <span class="app-brand-text demo menu-text fw-bold">Web Gestor </span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
        <!-- Page -->
        <li class="menu-item {{ Route::is('home') ? 'active' : '' }}">
            <a href="{{ url('home')}}" class="menu-link">
                <span class="menu-icon"><i class='fa fa-dashboard'></i></span>
                <div >Painel</div>
            </a>
        </li>
        <li class="menu-item {{ Route::is('meuperfil') ? 'active' : '' }}">
            <a href="{{ url('meuperfil') }}" class="menu-link">
                <span class="menu-icon"><i class='bi bi-file-person'></i></span>
                <div> Perfil </div>
            </a>
        </li>

        @if(isset($MenuItems))
            @foreach ($MenuItems->whereNull('parentId') as $menu)
                
                <li class="menu-item {{ request()->is($menu->rota) || $menu->submenus->contains(function ($submenu) { return request()->is($submenu->rota); }) ? 'active open' : '' }}">

                    <a href="{{ $menu->rota ? url($menu->rota) : '#'  }}" class="menu-link {{ $menu->submenus->count() > 0 ? 'menu-toggle' : '' }}">
                        @if ($menu->icone)
                        <span class="menu-icon">{!! $menu->icone !!}</span>
                        @else
                            <span class="menu-icon"><i class="bi bi-question-circle"></i></span>
                        @endif
                        <div>{{ $menu->nome }}</div>
                    </a>
                    @if ($menu->submenus->count() > 0)
                        <ul class="menu-sub">
                            @foreach ($menu->submenus->sortBy('posicao') as $submenu)
                                <li class="menu-item {{ (request()->is($submenu->rota) || request()->is($submenu->rota.'/*')) ? 'active': '' }}">                                
                                    <a href="{{ $submenu->rota ? url($submenu->rota) : '#' }}" class="menu-link">
                                        @if ($submenu->icone)
                                            <span class="menu-icon">{!! $submenu->icone !!}</span>
                                        @else
                                            <span class="menu-icon"><i class="bi bi-question-circle"></i></span>
                                        @endif
                                        <div>{{ $submenu->nome }}</div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        @else
                <li> sem itens </li>
        @endif
       
        @php

        /*
        <li class="menu-item {{ request()->is('teste/*') ? 'active open': '' }}">
            <a href="#" class="menu-link menu-toggle">
                <i class="menu-icon fa fa-ghost"></i>
                <div >Teste</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ (request()->is('teste/a') || request()->is('teste/a/*')) ? 'active' : '' }}">
                    <a href="{{Route('a')}}" class="menu-link">
                    <div>Teste A</div>
                    </a>
                </li>
                <li class="menu-item {{ (request()->is('teste/b') || request()->is('teste/b/*')) ? 'active' : '' }}">
                    <a href="{{Route('b')}}" class="menu-link">
                    <div>Teste B</div>
                    </a>
                </li>
            </ul>
        </li>
        */
        @endphp
        
    </ul>
</aside>