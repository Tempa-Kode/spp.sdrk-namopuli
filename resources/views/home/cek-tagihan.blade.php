@extends("template-home")
@section("title", "Cek Tagihan SPP - SD RK Namopuli")
@section("body")

    <!-- Hero Section -->
    <section class="banner_part_plain" style="padding: 100px 0 50px 0;">
        <div class="container">
            <div class="row align-items-center justify-content-center text-center">
                <div class="col-lg-8">
                    <div class="banner_text">
                        <div class="banner_text_iner">
                            <h1 class="mb-4">Cek Tagihan SPP</h1>
                            <p class="lead text-white">Masukkan NISN atau Nama Lengkap siswa untuk melihat detail tagihan SPP
                                yang
                                perlu dibayarkan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Section -->
    <section class="feature_part section_padding">
        <div class="container">
            @if (session('error'))
                <div class="alert alert-danger text-center">
                    <i class="ti-close mr-2"></i>{{ session('error') }}
                </div>
            @endif
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="search-card">
                        <div class="card">
                            <div class="card-header text-center">
                                <h3 class="mb-0">
                                    <i class="ti-search mr-2 text-primary"></i>
                                    Cari Data Siswa
                                </h3>
                                <p class="text-muted mt-2">Masukkan NISN siswa</p>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('home.cek-tagihan.nisn') }}" method="get">
                                    @method('GET')
                                    @csrf
                                    <div class="form-group">
                                        <label for="nisn" class="form-label">NISN</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Contoh: 1234567890"
                                                name="nisn" required>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-success" type="submit">
                                                    <i class="ti-search mr-1"></i>Cari
                                                </button>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">
                                            <i class="ti-info-alt mr-1"></i>
                                            NISN dapat ditemukan pada kartu pelajar atau rapor siswa
                                        </small>
                                    </div>
                                </form>

                                <!-- Loading State -->
                                <div id="loading" class="text-center" style="display: none;">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Mencari...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Sedang mencari data siswa...</p>
                                </div>

                                <!-- Error State -->
                                <div id="error" class="alert alert-danger" style="display: none;">
                                    <i class="ti-close mr-2"></i>
                                    <span id="error-message">Data siswa tidak ditemukan. Pastikan NISN atau nama yang
                                        dimasukkan sudah benar.</span>
                                </div>

                                <!-- Results -->
                                <div id="results" style="display: none;">
                                    <hr>
                                    <h5 class="mb-3">
                                        <i class="ti-user mr-2 text-success"></i>
                                        Data Siswa Ditemukan
                                    </h5>
                                    <div id="student-info" class="mb-4">
                                        <!-- Student info will be populated here -->
                                    </div>

                                    <h5 class="mb-3">
                                        <i class="ti-receipt mr-2 text-warning"></i>
                                        Tagihan SPP
                                    </h5>
                                    <div id="tagihan-list">
                                        <!-- Tagihan list will be populated here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Info Section -->
    <section class="testimonial_part bg-light">
        <div class="container">

            <div class="row justify-content-center mt-5">
                <div class="col-lg-8 text-center">
                    <h4 class="mb-3">Butuh Bantuan?</h4>
                    <p class="text-muted mb-4">Jika Anda mengalami kesulitan dalam mencari data atau melakukan pembayaran,
                        jangan ragu untuk menghubungi kami.</p>
                    <div class="help-buttons">
                        <a href="{{ route("home.panduan") }}" class="btn_1">
                            <i class="ti-book mr-2"></i>Lihat Panduan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Custom Styles for Cek Tagihan Page */
        .search-card .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .search-card .card-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-bottom: 2px solid #ff6b35;
            border-radius: 15px 15px 0 0;
            padding: 30px;
        }

        .search-card .card-body {
            padding: 30px;
        }

        .form-control {
            padding: 12px 15px;
            font-size: 16px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #ff6b35;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
            outline: none;
        }

        .form-control-lg {
            padding: 15px 20px;
            font-size: 16px;
            border-radius: 10px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .form-control-lg:focus {
            border-color: #ff6b35;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }

        .btn-lg {
            padding: 15px 25px;
            font-size: 16px;
            border-radius: 10px;
            font-weight: 600;
        }

        .btn {
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn:focus {
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
        }

        .info-item {
            padding: 20px;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .info-item:hover {
            transform: translateY(-5px);
        }

        .help-buttons .btn {
            margin: 5px;
        }

        /* Responsive */
        @media (max-width: 768px) {

            .search-card .card-header,
            .search-card .card-body {
                padding: 20px;
            }

            .help-buttons .btn {
                display: block;
                width: 100%;
                margin: 10px 0;
            }

            /* Fix mobile input group layout */
            .input-group {
                flex-direction: column;
                gap: 10px;
            }

            .input-group .form-control {
                border-radius: 8px !important;
                margin-bottom: 0;
                border: 1px solid #ddd;
                padding: 12px 15px;
                font-size: 16px;
                width: 100%;
            }

            .input-group .form-control:focus {
                border-color: #ff6b35;
                box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
            }

            .input-group-append {
                width: 100%;
                margin-left: 0;
            }

            .input-group-append .btn {
                width: 100%;
                border-radius: 8px !important;
                padding: 12px 15px;
                font-size: 16px;
                font-weight: 600;
            }

            /* Mobile specific adjustments */
            .form-group {
                margin-bottom: 1.5rem;
            }

            .form-label {
                font-weight: 600;
                margin-bottom: 8px;
                color: #333;
            }
        }

        @media (max-width: 576px) {

            .search-card .card-header,
            .search-card .card-body {
                padding: 15px;
            }

            .input-group .form-control,
            .input-group-append .btn {
                font-size: 14px;
                padding: 10px 12px;
            }
        }

        /* Student Info Styles */
        .student-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .tagihan-item {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            border-left: 4px solid #ff6b35;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .tagihan-item.lunas {
            border-left-color: #28a745;
        }

        .tagihan-item.belum-bayar {
            border-left-color: #dc3545;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-lunas {
            background: #d4edda;
            color: #155724;
        }

        .status-belum-bayar {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
@endsection
