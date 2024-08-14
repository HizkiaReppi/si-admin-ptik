<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@php
    $baseUrl = config('app.url');

    $baseUrl = explode('://', $baseUrl)[1];

    if (request()->secure()) {
        $baseUrl = 'https://' . $baseUrl;
    } else {
        $baseUrl = 'http://' . $baseUrl;
    }
@endphp

<head>
    <x-meta-data :title="$title" csrfToken="{{ csrf_token() }}" :baseurl="$baseUrl"/>

    <link rel="stylesheet" href="{{ $baseUrl }}/assets/vendor/fonts/boxicons.css">
    <link rel="stylesheet" href="{{ $baseUrl }}/assets/css/demo.css">
    <link rel="stylesheet" href="{{ $baseUrl }}/assets/css/app.css">
    <link rel="stylesheet" href="{{ $baseUrl }}/assets/vendor/css/core.css">
    <link rel="stylesheet" href="{{ $baseUrl }}/assets/vendor/css/theme-default.css">
    <link rel="stylesheet" href="{{ $baseUrl }}/assets/vendor/libs/trix/trix.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css">
    <script src="{{ $baseUrl }}/assets/vendor/libs/trix/trix.umd.min.js"></script>
    <script src="{{ $baseUrl }}/assets/vendor/js/helpers.js"></script>
    <script src="{{ $baseUrl }}/assets/js/config.js"></script>
    <script src="{{ $baseUrl }}/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="https://kit.fontawesome.com/29057e6c17.js" crossorigin="anonymous"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Sidebar -->
            <x-sidebar />

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <x-navbar />
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row">
                            <div class="mb-4 order-0">
                                @if (isset($header))
                                    <header class="card">
                                        <div class="d-flex align-items-end row">
                                            <div class="">
                                                <div class="card-body">
                                                    <h5 class="card-title text-primary">{{ $header }}</h5>
                                                    @if (isset($header_subtitle))
                                                        <p class="mb-0">
                                                            {{ $header_subtitle }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </header>
                                @endif

                                <main class="mt-4">
                                    {{ $slot }}
                                </main>
                            </div>
                        </div>
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    @include('components.footer')

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        @php
            $isToastDisplayed = Session::get('toastDisplayed');
        @endphp

        @if ($isUsernameAndPasswordSame && !$isToastDisplayed)
            <div class="position-fixed z-3" style="bottom:25px;right:30px">
                <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header pb-2">
                        <strong class="me-auto">Admin PTIK</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body border-top pt-1">
                        Demi keamanan akun Anda, silahkan mengganti password Anda dan jangan samakan dengan username
                        Anda.
                        <div class="mt-2 pt-2 border-top">
                            <a href="{{ route('profile.edit') }}#update-password" class="btn btn-primary btn-sm">Ubah
                                Password</a>
                            <button type="button" class="btn btn-secondary btn-sm"
                                data-bs-dismiss="toast">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
            @php
                Session::put('toastDisplayed', true);
            @endphp
        @endif

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <div id="btn-scroll-to-top-wrapper" class="d-none position-fixed" style="z-index: 1000;bottom:30px;right:30px">
            <button id="btn-scroll-to-top" class="btn btn-primary btn-scroll-to-top" style="width: 40px;height: 40px" type="button">
                <i class="fas fa-arrow-up"></i>
            </button>
        </div>
    </div>

    @include('sweetalert::alert')


    <script>
        const btnScrollToTopWrapper = document.querySelector('#btn-scroll-to-top-wrapper');
        const btnScrollToTop = document.querySelector('#btn-scroll-to-top');

        window.addEventListener('scroll', () => {
            if(window.scrollY > 125) {
                btnScrollToTopWrapper.classList.remove('d-none');
            } else {
                btnScrollToTopWrapper.classList.add('d-none');
            }
        })

        btnScrollToTop.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>

    <script src="{{ $baseUrl }}/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ $baseUrl }}/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ $baseUrl }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ $baseUrl }}/assets/vendor/js/menu.js"></script>
    <script src="{{ $baseUrl }}/assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="{{ $baseUrl }}/assets/js/main.js"></script>
    <script src="{{ $baseUrl }}/assets/js/bootstrap.js"></script>
    <script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    @stack('scripts')
    <script async defer src="https://buttons.github.io/buttons.js"></script>

</body>

</html>
