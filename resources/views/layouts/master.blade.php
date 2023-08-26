<!DOCTYPE html>

<html
  lang="en"
  class="light-style layout-navbar-fixed layout-menu-fixed layout-menu-collapsed layout-footer-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{Vite::asset('resources/assets/')}}"
  data-template="vertical-menu-template-starter"
>
  <head>
    
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
    <title>@yield('title')</title>
    <meta name="description" content="" />
    <!-- Favicon -->
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
    <link rel="stylesheet" href="{{Vite::asset('resources/assets/vendor/libs/select2/select2.css')}}" />
    
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
      .menu-icon {
        font-size: 1.0rem !important;
        margin-right: 0.8rem !important;
      }
      .swal2-container {
        z-index: 99999 !important;
      }
    </style>
    <!-- Page CSS -->
    @yield('styles')
    <script src="{{Vite::asset('resources/assets/vendor/js/helpers.js')}}"></script>
    <script src="{{Vite::asset('resources/assets/vendor/js/template-customizer.js')}}"></script>
    <script src="{{Vite::asset('resources/assets/js/config.js')}}"></script>    
    @livewireStyles
		@powerGridStyles
  </head>
  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->
        @include('layouts.menu')
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->
          @include('layouts.navbar')
          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            @yield('conteudo')
            
            <!-- Footer -->
            @include('layouts.footer')
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>

      <!-- Drag Target Area To SlideIn Menu On Small Screens -->
      <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{Vite::asset('resources/assets/vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{Vite::asset('resources/assets/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{Vite::asset('resources/assets/vendor/js/bootstrap.js')}}"></script>
    <script src="{{Vite::asset('resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
    <script src="{{Vite::asset('resources/assets/vendor/libs/node-waves/node-waves.js')}}"></script>
    <script src="{{Vite::asset('resources/assets/vendor/libs/hammer/hammer.js')}}"></script>
    <script src="{{Vite::asset('resources/assets/vendor/libs/typeahead-js/typeahead.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    <script src="{{Vite::asset('resources/assets/vendor/js/menu.js')}}"></script>
    <!--<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>--></script>
    
    
    
    <!-- endbuild -->

    <!-- Vendors JS -->
    @yield('vendorscripts')
    <script src="{{Vite::asset('resources/assets/vendor/libs/select2/select2.js')}}"></script>
    <!-- Main JS -->
    <script src="{{Vite::asset('resources/assets/js/main.js')}}"></script>

    <script src="{{ Vite::asset('resources/js/scripts/ui/ui-feather.js') }}"></script>
    <script src="{{ Vite::asset('resources/js/scripts/ui/ui-bootstrap.js') }}"></script>
    <script src="{{ Vite::asset('resources/js/scripts/ui/ui-materialdesign.js') }}"></script>
    <script src="{{ Vite::asset('resources/js/scripts/ui/ui-fontawesome.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    

    <!-- Page JS -->
    @yield('scripts')
   

    @livewireScripts
		@powerGridScripts
    <script>    
      window.addEventListener('load', function() {
        const button = document.querySelector('a.template-customizer-open-btn');
        button.remove();
      });
    </script>
    @livewire('l-w-chat')
    @livewire('l-w-task')
  </body>
</html>
