<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Favicon icon-->
    <link rel="shortcut icon" type="image/png" href="/img/logo-spm-dark.png" />

    <!-- Core Css -->
    <link rel="stylesheet" href="/assets/css/styles.css" />
    <link rel="stylesheet" href="/assets/vendor/icons-webfont-3.34.0/dist/tabler-icons.min.css" />
    <link rel="stylesheet" href="/styles/custom.css" />

    {{-- SweetAlert2 --}}
    <link rel="stylesheet" href="/assets/vendor/node_modules/sweetalert2/dist/sweetalert2.min.css" />

    @livewireStyles

    <title>ITDel SPM - @yield('title')</title>
    <!-- Owl Carousel  -->
    <link rel="stylesheet" href="/assets/libs/owl.carousel/dist/assets/owl.carousel.min.css" />

    @yield('others-css')
</head>

<body>
    <!-- Preloader -->
    <div class="preloader">
        <img src="/img/logo-spm-dark-text.png" alt="loader" class="lds-ripple img-fluid"
            style="height: 72px; width: 214px;" />
    </div>
    <div id="main-wrapper">
        <!-- Sidebar Start -->
        <aside class="left-sidebar with-vertical" style="z-index: 1040;">
            <div>
                <!-- ---------------------------------- -->
                <!-- Start Vertical Layout Sidebar -->
                <!-- ---------------------------------- -->
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="{{ route('app.beranda') }}" class="text-nowrap logo-img">
                        <img src="/img/logo-spm-dark-text.png" class="dark-logo" style="height: 45px;" alt="Logo-Dark" />
                        <img src="/img/logo-spm-dark-text.png" class="light-logo" style="height: 45px;" alt="Logo-light" />
                    </a>
                    <a href="javascript:void(0)"
                        class="sidebartoggler ms-auto text-decoration-none fs-5 d-block d-xl-none">
                        <i class="ti ti-x"></i>
                    </a>
                </div>

                @include('components.nav')



                <div class="fixed-profile p-3 mx-4 mb-2 bg-secondary-subtle rounded mt-3">
                    <div class="text-center">
                        <button onclick="backToTop()" class="btn btn-sm btn-outline-primary">
                            <i class="ti ti-arrow-up"></i>
                            Back to Top
                        </button>
                    </div>
                </div>

                <!-- ---------------------------------- -->
                <!-- Start Vertical Layout Sidebar -->
                <!-- ---------------------------------- -->
            </div>
        </aside>
        <!--  Sidebar End -->
        <div class="page-wrapper">
            <!--  Header Start -->
            <header class="topbar">
                <div class="with-vertical"><!-- ---------------------------------- -->
                    <!-- Start Vertical Layout Header -->
                    <!-- ---------------------------------- -->
                    <nav class="navbar navbar-expand-lg p-0">
                        <ul class="navbar-nav">
                            <li class="nav-item nav-icon-hover-bg rounded-circle ms-n2">
                                <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
                                    <i class="ti ti-menu-2"></i>
                                </a>
                            </li>

                        </ul>

                        <ul class="navbar-nav quick-links d-none d-lg-flex align-items-center">
                        </ul>

                        <div class="d-block d-lg-none py-4">
                            <a href="{{ route('app.beranda') }}" class="text-nowrap logo-img">
                                <img src="/img/logo-spm-dark-text.png" class="dark-logo" style="height: 36px;"
                                    alt="Logo-Dark" />
                                <img src="/img/logo-spm-dark-text.png" class="light-logo" style="height: 36px;"
                                    alt="Logo-light" />
                            </a>
                        </div>
                        <a class="navbar-toggler nav-icon-hover-bg rounded-circle p-0 mx-0 border-0"
                            href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="ti ti-dots fs-7"></i>
                        </a>
                        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                            <div class="d-flex align-items-center justify-content-between">
                                <a href="javascript:void(0)"
                                    class="nav-link nav-icon-hover-bg rounded-circle mx-0 ms-n1 d-flex d-lg-none align-items-center justify-content-center"
                                    type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilenavbar"
                                    aria-controls="offcanvasWithBothOptions">
                                    <i class="ti ti-align-justified fs-7"></i>
                                </a>
                                <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">
                                    <li class="nav-item nav-icon-hover-bg rounded-circle">
                                        <a class="nav-link moon dark-layout" href="javascript:void(0)">
                                            <i class="ti ti-moon moon"></i>
                                        </a>
                                        <a class="nav-link sun light-layout" href="javascript:void(0)">
                                            <i class="ti ti-sun sun"></i>
                                        </a>
                                    </li>

                                    <!-- ------------------------------- -->
                                    <!-- start profile Dropdown -->
                                    <!-- ------------------------------- -->
                                    <li class="nav-item dropdown">
                                        <a class="nav-link pe-0" href="javascript:void(0)" id="drop1"
                                            aria-expanded="false">
                                            <div class="d-flex align-items-center">
                                                <div class="user-profile-img">
                                                    <img src="{{ $auth->photo ?? '/assets/images/profile/' . ($auth->gender == 'F' ? 'F' : 'M') . '.jpg' }}"
                                                        class="rounded-circle" width="35" height="35"
                                                        alt="modernize-img" />
                                                </div>
                                            </div>
                                        </a>
                                        <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                                            aria-labelledby="drop1">
                                            <div class="profile-dropdown position-relative" data-simplebar>
                                                <div class="py-3 px-7 pb-0">
                                                    <h5 class="mb-0 fs-5 fw-semibold">User Profile</h5>
                                                </div>
                                                <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                                                    <img src="{{ $auth->photo ?? '/assets/images/profile/' . ($auth->gender == 'F' ? 'F' : 'M') . '.jpg' }}"
                                                        class="rounded-circle" width="80" height="80"
                                                        alt="modernize-img" />
                                                    <div class="ms-3">
                                                        <h5 class="mb-1 fs-3">{{ request()->auth->name }}</h5>
                                                        <span class="mb-1 d-block">{{ request()->auth->alias }}</span>
                                                        <p class="mb-0 d-flex align-items-center gap-1">
                                                            <i class="ti ti-tag fs-4"></i>
                                                            {{ request()->auth->username }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="message-body">
                                                    <a href="{{ route('app.profile') }}"
                                                        class="py-8 px-7 mt-8 d-flex align-items-center no-hover">
                                                        <span
                                                            class="d-flex align-items-center justify-content-center text-bg-light rounded-1 p-6">
                                                            <i class="ti ti-settings" style="font-size: 18px;"></i>
                                                        </span>
                                                        <div class="w-100 ps-2">
                                                            <h6 class="fs-3 mb-1 fw-semibold lh-base no-hover">
                                                                Pengaturan Akun
                                                            </h6>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="d-grid py-4 px-7 pt-8">
                                                    <button onclick="onLogout()" class="btn btn-outline-primary">
                                                        Log Out
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <!-- ------------------------------- -->
                                    <!-- end profile Dropdown -->
                                    <!-- ------------------------------- -->
                                </ul>
                            </div>
                        </div>
                    </nav>
                    <!-- ---------------------------------- -->
                    <!-- End Vertical Layout Header -->
                    <!-- ---------------------------------- -->

                    <!-- ------------------------------- -->
                    <!-- apps Dropdown in Small screen -->
                    <!-- ------------------------------- -->
                    <!--  Mobilenavbar -->
                    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="mobilenavbar"
                        aria-labelledby="offcanvasWithBothOptionsLabel">
                        <nav class="sidebar-nav scroll-sidebar">
                            <div class="offcanvas-header justify-content-between">
                                <img src="/img/logo-dark.png" alt="modernize-img" class="img-fluid"
                                    style="height: 36px;" />
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                    aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body h-n80" data-simplebar="" data-simplebar>
                                <ul id="sidebarnav">

                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </header>
            <!--  Header End -->

            <div class="body-wrapper">
                <div class="container">
                    @yield('content')
                </div>
            </div>


            <script>
                function handleColorTheme(e) {
                    document.documentElement.setAttribute("data-color-theme", e);
                }
            </script>

        </div>

    </div>
    <div class="dark-transparent sidebartoggler"></div>

    {{-- Scripts --}}
    <script src="/assets/js/vendor.min.js"></script>
    <!-- Import Js Files -->
    <script src="/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/libs/simplebar/dist/simplebar.min.js"></script>
    <script src="/assets/js/theme/app.init.js"></script>
    <script src="/assets/js/theme/theme.js"></script>
    <script src="/assets/js/theme/app.min.js"></script>
    <script src="/assets/js/theme/sidebarmenu.js"></script>

    {{-- SweetAlert2 --}}
    <script src="/assets/vendor/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>

    @livewireScripts
    <script src="/scripts/custom.js"></script>
    @yield('others-js')

    <script>
        document.addEventListener("livewire:initialized", () => {
            hidePreloader();

            Livewire.on("reloadPage", (data) => {
                window.location.reload();
            });

             Livewire.on('showSuccessAlert', (data) => {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil Melakukan Tindakan',
                    text: data.message,
                    confirmButtonColor: '#556ee6',
                });
            });
        });
        

        function backToTop() {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        }
    </script>
</body>

</html>
