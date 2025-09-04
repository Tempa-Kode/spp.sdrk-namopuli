<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    {{-- <link rel="apple-touch-icon" sizes="76x76" href="{{ asset("assets/img/apple-icon.png") }}"> --}}
    <link rel="icon" type="image/png" href="{{ asset("landing/img/favicon.ico") }}">
    <title>
        @yield("title", "Dashboard") - SPP SD RK NAMOPULI
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset("assets/css/soft-ui-dashboard.css?v=1.1.0") }}" rel="stylesheet" />
    <link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.3.3/fh-4.0.3/r-3.0.6/sc-2.4.3/datatables.min.css"
        rel="stylesheet" integrity="sha384-q8xN1tfITXcxdT1l6HMZLc9T7nnCiPJDj/IGsPLlKw47I5bO9PupoYYRIicLIi+B"
        crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Nepcha Analytics (nepcha.com) -->
    <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
    <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>

<body class="g-sidenav-show  bg-gray-100">
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 "
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href="">
                <img src="{{ asset("landing/img/logo.svg") }}" class="navbar-brand-img h-100" alt="main_logo">
                <span class="ms-1 font-weight-bold">SD RK Namopuli</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="collapse navbar-collapse  w-auto vh-100" id="sidenav-collapse-main">
           @switch(Auth::user()->role)
                @case('wali')
                    @include('components.menu-wali')
                    @break
                @default
                    @include('components.menu-stackholder')
           @endswitch
        </div>
    </aside>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
            navbar-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="/">Halaman</a>
                        </li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">@yield("page", "Dashboard")
                        </li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">@yield("page", "Dashboard")</h6>
                </nav>

            </div>
        </nav>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                @yield("body")
            </div>
        </div>
    </main>
    <!--   Core JS Files   -->
    <script src="{{ asset("assets/js/core/popper.min.js") }}"></script>
    <script src="{{ asset("assets/js/core/bootstrap.min.js") }}"></script>
    <script src="{{ asset("assets/js/plugins/perfect-scrollbar.min.js") }}"></script>
    <script src="{{ asset("assets/js/plugins/smooth-scrollbar.min.js") }}"></script>
    <script src="{{ asset("assets/js/plugins/chartjs.min.js") }}"></script>
    <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.3.3/fh-4.0.3/r-3.0.6/sc-2.4.3/datatables.min.js"
        integrity="sha384-LtEKbZknqXqdIrquDzxmcL32nBNaUhz7ounatXGWZGHfz/oZogHg1EidEwTlvUsP" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.datatable').DataTable();
        });
    </script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset("assets/js/soft-ui-dashboard.min.js?v=1.1.0") }}"></script>
    @stack('scripts')
</body>

</html>
