<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title')</title>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="#">

    <!-- Dark mode -->
    <script>
        const storedTheme = localStorage.getItem('theme')

        const getPreferredTheme = () => {
            if (storedTheme) {
                return storedTheme
            }
            return window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'light'
        }

        const setTheme = function (theme) {
            if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.setAttribute('data-bs-theme', 'dark')
            } else {
                document.documentElement.setAttribute('data-bs-theme', theme)
            }
        }

        setTheme(getPreferredTheme())

        window.addEventListener('DOMContentLoaded', () => {
            var el = document.querySelector('.theme-icon-active');
            if (el != 'undefined' && el != null) {
                const showActiveTheme = theme => {
                    const activeThemeIcon = document.querySelector('.theme-icon-active use')
                    const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`)
                    const svgOfActiveBtn = btnToActive.querySelector('.mode-switch use').getAttribute('href')

                    document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
                        element.classList.remove('active')
                    })

                    btnToActive.classList.add('active')
                    activeThemeIcon.setAttribute('href', svgOfActiveBtn)
                }

                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                    if (storedTheme !== 'light' || storedTheme !== 'dark') {
                        setTheme(getPreferredTheme())
                    }
                })

                showActiveTheme(getPreferredTheme())

                document.querySelectorAll('[data-bs-theme-value]')
                    .forEach(toggle => {
                        toggle.addEventListener('click', () => {
                            const theme = toggle.getAttribute('data-bs-theme-value')
                            localStorage.setItem('theme', theme)
                            setTheme(theme)
                            showActiveTheme(theme)
                        })
                    })

            }
        })

    </script>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('assets/images/logo.png')}}">

    <!-- Google Font -->
    {{--    <link rel="preconnect" href="https://fonts.googleapis.com">--}}
    {{--    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>--}}
    {{--    <link href="https://fonts.googleapis.com/css2?family=Changa:wght@700&display=swap" rel="stylesheet">--}}

    <!-- Plugins CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset('main/vendor/font-awesome/css/all.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('main/vendor/bootstrap-icons/bootstrap-icons.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('main/vendor/tiny-slider/tiny-slider.css')}}">

    <!-- Theme CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset('main/css/style-rtl.css')}}">

    <style>
        @font-face {
            font-family: 'Changa';
            src: url('{{ asset('main/font/Changa-VariableFont_wght.ttf') }}'),
            url('{{ asset('main/font/Changa-VariableFont_wght.ttf') }}');
            /* Add additional font formats if available */
            font-weight: 400; /* Adjust the font weight as needed */
            font-style: normal; /* Adjust the font style as needed */
        }

        body {
            font-family: 'Changa', sans-serif !important;
        }

        /* Width */
        ::-webkit-scrollbar {
            width: 13px;
            border-radius: 20px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #dbdbdb;
            border-radius: 5px;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background-color: #117b5d;
            border-radius: 5px;
        }

        ::selection {
            background-color: #117b5d;
            color: white; /* This changes the text color */
        }


    </style>

    @yield('style')
    <livewire:styles/>
</head>
<body>

<!-- =======================
Header START -->
@include('components.main_rtl.nav')

<!-- =======================
Header END -->

<!-- **************** MAIN CONTENT START **************** -->
<main>

    <!-- =======================
    Main hero START -->
    <section class="pt-0 card-grid">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    {{ $slot }}
                    <livewire:main.search-news/>
                </div>
            </div> <!-- Row END -->
        </div>
    </section>
    <!-- =======================
    Main hero END -->
</main>
<!-- **************** MAIN CONTENT END **************** -->

<!-- =======================
Footer START -->
@include('components.main_rtl.footer')

<!-- =======================
Footer END -->
<!-- Back to top -->
<div class="back-top"><i class="bi bi-arrow-up-short"></i></div>

<!-- =======================
JS libraries, plugins and custom scripts -->
<livewire:scripts/>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="{{asset('main/vendor/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>

<!-- Vendors -->
<script src="{{asset('main/vendor/tiny-slider/tiny-slider-rtl.js')}}"></script>

<!-- Template Functions -->
<script src="{{asset('main/js/functions.js')}}"></script>
@yield('script')
</body>
</html>