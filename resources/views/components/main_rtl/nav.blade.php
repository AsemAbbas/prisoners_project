<header class="navbar-light navbar-sticky header-static mb-4 p-2">
    <!-- Logo Nav START -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <!-- Logo START -->
            <div class="text-center mt-2">
                <a class="navbar-brand p-0 m-0 d-block" href="{{route('main.index')}}">
                    <img class="navbar-brand-item light-mode-item"
                         style="width: 115px!important; height: 100%!important;"
                         src="{{asset('assets/images/logo.webp')}}" alt="logo">
                    <img class="navbar-brand-item dark-mode-item"
                         style="width: 115px!important; height: 100%!important;"
                         src="{{asset('assets/images/light-logo.webp')}}" alt="light_logo">
                </a>
                {{--                <div style="position: relative; background-image: linear-gradient(to right, #D52B1E, #D52B1E 25%, #FFFFFF 25%, #FFFFFF 50%, #00A651 50%, #00A651 75%, #000000 75%);" class="py-1 text-center rounded"></div>--}}
            </div>

            <!-- Logo END -->

            <!-- Responsive navbar toggler -->
            <button class="navbar-toggler me-auto" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="">القائمة</span>
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Main navbar START -->
            <div class="collapse navbar-collapse" id="navbarCollapse" style="direction: rtl">
                <!-- Nav Search START -->

                <!-- Nav Search END -->
                <ul class="navbar-nav navbar-nav-scroll">
                    <li class="nav-item"><a style="font-weight: bold;font-size: 17px" class="nav-link"
                                            href="{{route('main.index')}}">الرئيسية</a>
                    </li>
                    <li class="nav-item"><a style="font-weight: bold;font-size: 17px" class="nav-link"
                                            href="/#Search">الاستعلام</a>
                    </li>
                    <li class="nav-item"><a style="font-weight: bold;font-size: 17px" class="nav-link"
                                            href="/#Statistics">احصائيات</a></li>

                    <li class="nav-item"><a style="font-weight: bold;font-size: 17px" class="nav-link"
                                            href="/#News">أخبار</a>
                    </li>
                    <li class="nav-item"><a style="font-weight: bold;font-size: 17px" class="nav-link"
                                            href="/#footer">من نحن</a></li>
                    <li class="nav-item">
                        <div class="nav-link">
                            <a style="background-color:#022d4f" target="_blank" href="https://t.me/FajrHurriya"
                               class="p-2 text-white rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-telegram text-white" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.287 5.906q-1.168.486-4.666 2.01-.567.225-.595.442c-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.294q.39.01.868-.32 3.269-2.206 3.374-2.23c.05-.012.12-.026.166.016s.042.12.037.141c-.03.129-1.227 1.241-1.846 1.817-.193.18-.33.307-.358.336a8 8 0 0 1-.188.186c-.38.366-.664.64.015 1.088.327.216.589.393.85.571.284.194.568.387.936.629q.14.092.27.187c.331.236.63.448.997.414.214-.02.435-.22.547-.82.265-1.417.786-4.486.906-5.751a1.4 1.4 0 0 0-.013-.315.34.34 0 0 0-.114-.217.53.53 0 0 0-.31-.093c-.3.005-.763.166-2.984 1.09"/>
                                </svg>
                                تابعنا على تيليجرام
                            </a>
                        </div>
                    </li>

                </ul>

                <div class="nav my-1 mt-lg-0 px-4 flex-nowrap align-items-center ms-auto">
                    <div class="nav-item w-100">
                        <div class="rounded position-relative">
                            <a role="button" data-bs-toggle="modal"
                               data-bs-target="#searchModal"
                               class="btn bg-transparent border-0 px-2 py-0 mt-5 top-50 end-0 translate-middle-y">
                                <i class="bi bi-search fs-3"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Main navbar END -->
            <!-- Dark mode options START -->
            <div
                style="width: 70px;height: 70px;background-image:url('{{asset('main/images/palestine_flag.GIF')}}');background-size: 100px;background-position: center;background-repeat: no-repeat;z-index: 2">
            </div>
            <div class="nav-item dropdown ms-3">
                <!-- Switch button -->
                <button class="modeswitch" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown"
                        data-bs-display="static">
                    <svg class="theme-icon-active">
                        <use href="#"></use>
                    </svg>
                </button>
                <!-- Dropdown items -->
                <ul class="dropdown-menu min-w-auto dropdown-menu-start" aria-labelledby="bd-theme">
                    <li class="mb-1">
                        <button type="button" class="dropdown-item d-flex align-items-center"
                                data-bs-theme-value="light">
                            <svg width="16" height="16" fill="currentColor"
                                 class="bi bi-brightness-high-fill fa-fw mode-switch me-1 mx-1" viewBox="0 0 16 16">
                                <path
                                    d="M12 8a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
                                <use href="#"></use>
                            </svg>
                            ساطع
                        </button>
                    </li>
                    <li class="mb-1">
                        <button type="button" class="dropdown-item d-flex align-items-center"
                                data-bs-theme-value="dark">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                 class="bi bi-moon-stars-fill fa-fw mode-switch me-1 mx-1" viewBox="0 0 16 16">
                                <path
                                    d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"/>
                                <path
                                    d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"/>
                                <use href="#"></use>
                            </svg>
                            داكن
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center active"
                                data-bs-theme-value="auto">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                 class="bi bi-circle-half fa-fw mode-switch me-1 mx-1" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"/>
                                <use href="#"></use>
                            </svg>
                            تلقائي
                        </button>
                    </li>
                </ul>
            </div>
            <!-- Dark mode options END -->
        </div>
    </nav>
    <!-- Logo Nav END -->
</header>
<script>
    // Wait for the DOM to be ready
    document.addEventListener("DOMContentLoaded", function () {
        // Get all the navbar links
        var navLinks = document.querySelectorAll(".navbar-nav a.nav-link");

        // Add click event listener to each link
        navLinks.forEach(function (link) {
            link.addEventListener("click", function () {
                // Close the navbar by toggling the collapse class
                var navbarCollapse = document.getElementById("navbarCollapse");
                if (navbarCollapse.classList.contains("show")) {
                    navbarCollapse.classList.remove("show");
                }
            });
        });
    });
</script>
