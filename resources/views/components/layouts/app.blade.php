<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Language" content="ar">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="icon" type="image/x-icon" href="{{asset('assets/images/logo.png')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('plugins-rtl/bootstrap/bootstrap.rtl.min.css')}}">
    @vite(['resources/rtl/scss/light/assets/main.scss', 'resources/rtl/scss/dark/assets/main.scss'])
    <link rel="stylesheet" type="text/css" href="{{asset('plugins-rtl/waves/waves.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('plugins-rtl/highlight/styles/monokai-sublime.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    @vite([
        'resources/rtl/scss/light/plugins/perfect-scrollbar/perfect-scrollbar.scss',
        'resources/rtl/scss/light/assets/custom.scss',
        'resources/rtl/scss/dark/assets/custom.scss',
    ])
    @vite([
        'resources/rtl/scss/layouts/vertical-light-menu/light/structure.scss',
        'resources/rtl/scss/layouts/vertical-light-menu/dark/structure.scss',
    ])
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <style>
        @font-face {
            font-family: 'Changa';
            src: url('{{ asset('assets/font/Changa-VariableFont_wght.ttf') }}'),
            url('{{ asset('assets/font/Changa-VariableFont_wght.ttf') }}');
            /* Add additional font formats if available */
            font-weight: bold; /* Adjust the font weight as needed */
            font-style: normal; /* Adjust the font style as needed */
        }

        body {
            font-family: 'Changa', sans-serif !important;
        }

        /* Width */
        ::-webkit-scrollbar {
            width: 10px; /* You can adjust this to change the width of the scrollbar */
            border-radius: 20px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #f1f1f1; /* Change this to set the track color */
            border-radius: 8px; /* Adjust this value to set the rounded corner radius */

        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #4361ee; /* Change this to set the scrollbar handle color */
            border-radius: 8px; /* Adjust this value to set the rounded corner radius */
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #4361ee; /* Change this to set the handle color on hover */
        }
    </style>

    @yield('style')
    <!-- END GLOBAL MANDATORY STYLES -->
    <livewire:styles/>
</head>
<body>

<!--  BEGIN NAVBAR  -->
@auth
    @include('components.rtl.navbar.style-vertical-menu')
@endauth
<!--  END NAVBAR  -->


<!--  BEGIN MAIN CONTAINER  -->
<div class="main-container " id="container">

    <!--  BEGIN LOADER  -->
    @auth
        @include('components.rtl.layout-overlay')
    @endauth


    <!--  END LOADER  -->

    <!--  BEGIN SIDEBAR  -->
    @auth
        @include('components.rtl.menu.vertical-menu')
    @endauth
    <!--  END SIDEBAR  -->

    <!--  BEGIN CONTENT AREA  -->
    <div id="content" class="main-content"
         style="@guest margin:0!important; @endguest">
        <div style="min-height: 800px">
            <div class="middle-content p-0">
                {{ $slot }}
            </div>
        </div>
        <!--  BEGIN FOOTER  -->
        @include('components.rtl.layout-footer')

        <!--  END FOOTER  -->
    </div>
    <!--  END CONTENT AREA  -->

</div>
<livewire:scripts/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{asset('plugins-rtl/bootstrap/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('plugins-rtl/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
<script src="{{asset('plugins-rtl/mousetrap/mousetrap.min.js')}}"></script>
<script src="{{asset('plugins-rtl/waves/waves.min.js')}}"></script>
<script src="{{asset('plugins-rtl/highlight/highlight.pack.js')}}"></script>
@vite(['resources/rtl/layouts/vertical-light-menu/app.js'])
<!-- END GLOBAL MANDATORY STYLES -->
@yield('script')
</body>
</html>
