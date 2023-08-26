<!DOCTYPE html>

<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{Vite::asset('resources/assets/')}}"
  data-template="horizontal-menu-template"
>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
    <title>Login | Web Gestor</title>
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="{{Vite::asset('resources/assets/logo/favicon.ico')}}" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet"/>
     <!-- Icons -->
     <link rel="stylesheet" href="{{Vite::asset('resources/assets/vendor/fonts/fontawesome.css')}}" />
     <link rel="stylesheet" href="{{Vite::asset('resources/assets/vendor/fonts/tabler-icons.css')}}" />
     <link rel="stylesheet" href="{{Vite::asset('resources/assets/vendor/fonts/flag-icons.css')}}" />
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{Vite::asset('resources/assets/vendor/css/rtl/core.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{Vite::asset('resources/assets/vendor/css/rtl/theme-default.css')}}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{Vite::asset('resources/assets/css/demo.css')}}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{Vite::asset('resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />
    <link rel="stylesheet" href="{{Vite::asset('resources/assets/vendor/libs/node-waves/node-waves.css')}}" />
    <link rel="stylesheet" href="{{Vite::asset('resources/assets/vendor/libs/typeahead-js/typeahead.css')}}" />
    <link rel="stylesheet" href="{{Vite::asset('resources/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{Vite::asset('resources/assets/vendor/css/pages/page-auth.css')}}" />
    <!-- Helpers -->
    <script src="{{Vite::asset('resources/assets/vendor/js/helpers.js')}}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="{{Vite::asset('resources/assets/vendor/js/template-customizer.js')}}"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{Vite::asset('resources/assets/js/config.js')}}"></script>
  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
          <!-- Login -->
          <div class="card">
            <div class="card-body">
                <!-- Logo -->
                <div class="app-brand justify-content-center mb-4 mt-2">
                    <a href="{{ URL('/') }}" class="app-brand-link gap-2">
                    <span class="app-brand-logo demo">
                        <img src="{{Vite::asset('resources/assets/logo/AI_logo_sentric.svg')}}" width="32px" height="32px" />
                    </span>
                    <span class="app-brand-text demo text-body fw-bold ms-1">Web Gestor</span>
                    </a>
                </div>
                <!-- /Logo -->
                <h4 class="mb-1 pt-2">Bem vindo! 👋</h4>
                <p class="mb-4">Faça a autenticação para acessar o sistema</p>

                <form id="formAuthentication" class="mb-3" method="POST" action="{{ URL('/login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email"  placeholder="E-mail" value="{{ old('email') }}"  autofocus/>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3 form-password-toggle">
                        <div class="d-flex justify-content-between">
                            <label class="form-label" for="password">Senha</label>
                            @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">
                            <small>Esqueceu a senha?</small>
                            </a>
                            @endif
                        </div>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password"  />
                            <span class="input-group-text cursor-pointer" id="toggle-password"><i class="ti ti-eye-off"></i></span>

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-primary d-grid w-100" type="submit">Entrar</button>
                    </div>
                </form>
            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{Vite::asset('resources/assets/vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{Vite::asset('resources/assets/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{Vite::asset('resources/assets/vendor/js/bootstrap.js')}}"></script>
    <script src="{{Vite::asset('resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
    <script src="{{Vite::asset('resources/assets/vendor/libs/node-waves/node-waves.js')}}"></script>

    <script src="{{Vite::asset('resources/assets/vendor/libs/hammer/hammer.js')}}"></script>
    <script src="{{Vite::asset('resources/assets/vendor/libs/i18n/i18n.js')}}"></script>
    <script src="{{Vite::asset('resources/assets/vendor/libs/typeahead-js/typeahead.js')}}"></script>

    <script src="{{Vite::asset('resources/assets/vendor/js/menu.js')}}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="../../assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="../../assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="../../assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>

    <!-- Main JS -->
    <script src="{{Vite::asset('resources/assets/vendor/js/menu.js')}}"></script>

    <!-- Page JS -->
    <script src="{{Vite::asset('resources/assets/js/pages-auth.js')}}"></script>
    <script>
        var togglePassword = document.querySelector('#toggle-password');
        var password = document.querySelector('#password');
      
        togglePassword.addEventListener('click', function () {
          if (password.type === 'password') {
            password.type = 'text';
            togglePassword.innerHTML = '<i class="ti ti-eye"></i>';
          } else {
            password.type = 'password';
            togglePassword.innerHTML = '<i class="ti ti-eye-off"></i>';
          }
        });
      </script>
  </body>
</html>