<nav class="layout-navbar container-fluid navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="ti ti-menu-2 ti-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center">
            <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
                <i class='bi bi-lightbulb'></i>
            </a>
        </div>
        <div class="navbar-nav align-items-center">
            <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="offcanvas" data-bs-target="#fastchat" aria-controls="fastchat">
                <i class='bi bi-chat'></i>
            </a>
        </div>
        <div class="navbar-nav align-items-center">
            <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="offcanvas" data-bs-target="#fasttask" aria-controls="fasttask">
                <i class='bi bi-calendar-check'></i>
            </a>
        </div>
        
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        @if(Auth::user()->perfil)
                            <img src="{{ asset('storage/perfil_uploads/' .  Auth::user()->perfil) }}" alt class="h-auto rounded-circle" />
                        @else
                            @php
                                $nome = Auth::user()->name;
                                preg_match_all('/\p{Lu}/u', $nome, $matches);
                                $iniciais = implode('', array_slice($matches[0], 0, 2));                                            
                            @endphp
                            <div class="avatar me-2"><span class="avatar-initial rounded-circle bg-label-secondary">{{$iniciais}}</span></div>
                        @endif
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                    <a class="dropdown-item" href="#">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-online">
                                    @if(Auth::user()->perfil)
                                        <img src="{{ asset('storage/perfil_uploads/' .  Auth::user()->perfil) }}" alt class="h-auto rounded-circle" />
                                    @else
                                        @php
                                            $nome = Auth::user()->name;
                                            preg_match_all('/\p{Lu}/u', $nome, $matches);
                                            $iniciais = implode('', array_slice($matches[0], 0, 2));                                            
                                        @endphp
                                        <div class="avatar me-2"><span class="avatar-initial rounded-circle bg-label-secondary">{{$iniciais}}</span></div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <span class="fw-semibold d-block">{{ Auth::user()->name }}</span>
                                <small class="text-muted">{{ optional(Auth::user()->cliente)->fantasia }}</small> / <small class="text-muted">{{ Auth()->user()->role->nome }} </small>
                                
                            </div>
                        </div>
                    </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('meuperfil') }}">
                            <i class="ti ti-user-check me-2 ti-sm"></i>
                            <span class="align-middle">Perfil</span>
                        </a>
                    </li>
                    <li>
                    <a class="dropdown-item" href="#">
                        <i class="ti ti-settings me-2 ti-sm"></i>
                        <span class="align-middle">Configurações</span>
                    </a>
                    </li>
                    <li>
                    <div class="dropdown-divider"></div>
                    </li>
                    <li>
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="ti ti-logout me-2 ti-sm"></i>
                        <span class="align-middle">Sair</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
  </nav>

  