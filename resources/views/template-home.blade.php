<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield("title")</title>
    <link rel="icon" href="{{ asset("landing/img/favicon.ico") }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset("landing/css/bootstrap.min.css") }}">
    <!-- animate CSS -->
    <link rel="stylesheet" href="{{ asset("landing/css/animate.css") }}">
    <!-- owl carousel CSS -->
    <link rel="stylesheet" href="{{ asset("landing/css/owl.carousel.min.css") }}">
    <!-- themify CSS -->
    <link rel="stylesheet" href="{{ asset("landing/css/themify-icons.css") }}">
    <!-- flaticon CSS -->
    <link rel="stylesheet" href="{{ asset("landing/css/flaticon.css") }}">
    <!-- font awesome CSS -->
    <link rel="stylesheet" href="{{ asset("landing/css/magnific-popup.css") }}">
    <!-- swiper CSS -->
    <link rel="stylesheet" href="{{ asset("landing/css/slick.css") }}">
    <!-- style CSS -->
    <link rel="stylesheet" href="{{ asset("landing/css/style.css") }}">
    <!-- FAQ Accordion CSS -->
    <link rel="stylesheet" href="{{ asset("landing/css/faq-accordion.css") }}">
    <!-- Banner Plain CSS -->
    <link rel="stylesheet" href="{{ asset("landing/css/banner-plain.css") }}">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
    <!--::header part start::-->
    <header class="main_menu home_menu">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <a class="navbar-brand" href="/"> <img src="{{ asset("landing/img/logo.svg") }}"
                                class="w-50" alt="logo"> </a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse main-menu-item justify-content-end"
                            id="navbarSupportedContent">
                            <ul class="navbar-nav align-items-center">
                                <li class="nav-item active">
                                    <a class="nav-link" href="/">Beranda</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('home.cek-tagihan') }}">Cek Tagihan</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/#faq">Bantuan / FAQ</a>
                                </li>
                                @if (Auth::check())
                                    <li class="d-none d-lg-block">
                                        <form action="{{ route('logout') }}" method="post">
                                            @csrf
                                            <button type="submit" class="btn_1">Logout</button>
                                        </form>
                                    </li>
                                    <li class="nav-item d-block d-md-none">
                                        <form action="{{ route('logout') }}" method="post">
                                            @csrf
                                            <button type="submit" class="btn btn-danger rounded ml-3">Logout</button>
                                        </form>
                                    </li>
                                @else
                                    <li class="nav-item d-block d-md-none">
                                        <a class="nav-link btn btn-warning rounded ml-3" href="{{ route("login") }}">Login</a>
                                    </li>
                                    <li class="d-none d-lg-block">
                                        <a class="btn_1" href="{{ route("login") }}">Login</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- Header part end-->

    @yield("body")

    <!-- footer part start-->
    <footer class="footer-area">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="single-footer-widget footer_1">
                        <a href="/"> <img src="{{ asset("landing/img/logo.svg") }}" class="w-25"
                                alt=""> </a>
                        <p>SD RK Namopuli </p>
                        <p>Lakukan pembayaran SPP putra-putri Anda dengan cepat, aman, dan tercatat secara digital.</p>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-md-4">
                    <div class="single-footer-widget footer_2">
                        <h4>Hubungi Kami</h4>
                        <div class="contact_info">
                            <p><span> Alamat :</span> Dusun I Namopuli 	Desa	SUMBUL	KEC. STM HILIR	KAB. DELI SERDANG SUMATERA UTARA</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="copyright_part_text text-center">
                        <div class="row">
                            <div class="col-lg-12">
                                <p class="footer-text m-0">
                                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                                    Copyright &copy;
                                    <script>
                                        document.write(new Date().getFullYear());
                                    </script> All rights reserved | SD RK Namopuli</a>
                                    <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- footer part end-->

    <!-- jquery plugins here-->
    <!-- jquery -->
    <script src="{{ asset("landing/js/jquery-1.12.1.min.js") }}"></script>
    <!-- popper js -->
    <script src="{{ asset("landing/js/popper.min.js") }}"></script>
    <!-- bootstrap js -->
    <script src="{{ asset("landing/js/bootstrap.min.js") }}"></script>
    <!-- easing js -->
    <script src="{{ asset("landing/js/jquery.magnific-popup.js") }}"></script>
    <!-- swiper js -->
    <script src="{{ asset("landing/js/swiper.min.js") }}"></script>
    <!-- swiper js -->
    <script src="{{ asset("landing/js/masonry.pkgd.js") }}"></script>
    <!-- particles js -->
    <script src="{{ asset("landing/js/owl.carousel.min.js") }}"></script>
    <script src="{{ asset("landing/js/jquery.nice-select.min.js") }}"></script>
    <!-- swiper js -->
    <script src="{{ asset("landing/js/slick.min.js") }}"></script>
    <script src="{{ asset("landing/js/jquery.counterup.min.js") }}"></script>
    <script src="{{ asset("landing/js/waypoints.min.js") }}"></script>
    <!-- FAQ Accordion JS -->
    <script src="{{ asset("landing/js/faq-accordion.js") }}"></script>
    <!-- custom js -->
    <script src="{{ asset("landing/js/custom.js") }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>

</html>
