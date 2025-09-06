@extends("template-home")
@section("title", "Panduan Pembayaran SPP - SD RK Namopuli")
@section("body")

    <!-- Hero Section -->
    <section class="banner_part_plain" style="padding: 100px 0 50px 0;">
         <
        <div class="container">
            <div class="row align-items-center justify-content-center text-center">
                <div class="col-lg-8">
                    <div class="banner_text">
                        <div class="banner_text_iner">
                            <h1 class="mb-4">4 Langkah Mudah Membayar SPP</h1>
                            <p class="lead text-white">Ikuti panduan sederhana di bawah ini untuk menyelesaikan pembayaran SPP
                                putra-putri Anda dengan mudah dan aman.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Steps Section -->
    <section class="feature_part section_padding">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <h2 class="mb-4">Proses Pembayaran Yang Mudah</h2>
                    <p class="text-muted">Hanya butuh beberapa langkah untuk menyelesaikan pembayaran SPP secara online</p>
                </div>
            </div>

            <!-- Step 1 -->
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="step-content">
                        <div class="step-number">
                            <span class="number">1</span>
                        </div>
                        <h3 class="step-title">Masukkan Data Kode Tagihan</h3>
                        <p class="step-description">
                            Cari data putra-putri Anda dengan memasukkan <strong>Kode Tagihan</strong>.
                        </p>
                        <div class="step-tips">
                            <small class="text-muted">
                                <i class="ti-info-alt mr-1"></i>
                                Tips: Kode Tagihan dapat dilihat pada dashboard orang tua (setelah login)
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="step-illustration">
                        <div class="mockup-card">
                            <div class="card-header bg-primary text-white">
                                <i class="ti-search mr-2"></i>Cari Data Siswa
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Kode Tagihan</label>
                                    <input type="text" class="form-control"
                                        placeholder="Masukkan Kode Tagihan">
                                </div>
                                <button class="btn btn-primary">
                                    <i class="ti-search mr-1"></i>Cari
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="row align-items-center mb-5 flex-row-reverse">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="step-content text-lg-right">
                        <div class="step-number">
                            <span class="number">2</span>
                        </div>
                        <h3 class="step-title">Verifikasi Tagihan</h3>
                        <p class="step-description">
                            Sistem akan menampilkan detail <strong>nama siswa</strong> dan <strong>jumlah tagihan
                                SPP</strong>
                            yang perlu dibayarkan. Periksa kembali data siswa dan pastikan semua informasi sudah sesuai
                            sebelum melanjutkan.
                        </p>
                        <div class="step-tips">
                            <small class="text-muted">
                                <i class="ti-check mr-1"></i>
                                Pastikan nama dan kelas siswa sudah benar
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="step-illustration">
                        <div class="mockup-card">
                            <div class="card-header bg-success text-white">
                                <i class="ti-receipt mr-2"></i>Detail Tagihan
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Nama:</strong></td>
                                        <td>Ahmad Wijaya</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Bulan:</strong></td>
                                        <td>September 2025</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td><strong>Total:</strong></td>
                                        <td><strong>Rp 85.000</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="step-content">
                        <div class="step-number">
                            <span class="number">3</span>
                        </div>
                        <h3 class="step-title">Pilih Metode Pembayaran</h3>
                        <p class="step-description">
                            Kami menyediakan berbagai pilihan pembayaran yang aman dan mudah. Pilih metode yang paling
                            nyaman untuk Anda:
                        </p>
                        <div class="payment-methods">
                            <div class="payment-option">
                                <i class="ti-credit-card text-primary"></i>
                                <span><strong>Virtual Account</strong> - Bank Transfer (BCA, Mandiri, BNI, BRI)</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="step-illustration">
                        <div class="mockup-card">
                            <div class="card-header bg-warning text-white">
                                <i class="ti-wallet mr-2"></i>Pilih Pembayaran
                            </div>
                            <div class="card-body">
                                <div class="payment-grid">
                                    <div class="payment-item active d-flex justify-content-center align-items-center">
                                        <img src="{{ asset("landing/img/bri.png") }}" class="w-50 d-block m-auto" alt="bri">
                                    </div>
                                    <div class="payment-item d-flex justify-content-center align-items-center">
                                        <img src="{{ asset("landing/img/bni.png") }}" class="w-50 d-block m-auto" alt="bni">
                                    </div>
                                    <div class="payment-item d-flex justify-content-center align-items-center">
                                        <img src="{{ asset("landing/img/mandiri.png") }}" class="w-50 d-block m-auto" alt="qris">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="row align-items-center mb-5 flex-row-reverse">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="step-content text-lg-right">
                        <div class="step-number">
                            <span class="number">4</span>
                        </div>
                        <h3 class="step-title">Selesaikan & Terima Bukti</h3>
                        <p class="step-description">
                            Lakukan pembayaran sesuai instruksi yang diberikan. Setelah pembayaran berhasil, Anda akan
                            mendapatkan <strong>bukti pembayaran digital</strong> secara
                            otomatis
                            yang dapat diunduh kapan saja.
                        </p>
                        <div class="step-tips">
                            <small class="text-muted">
                                <i class="ti-download mr-1"></i>
                                Bukti pembayaran dapat diunduh dihalaman dashboard (setelah login) ketika pembayaran berhasil dalam format PDF
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="step-illustration">
                        <div class="mockup-card">
                            <div class="card-header bg-success text-white">
                                <i class="ti-check mr-2"></i>Pembayaran Berhasil
                            </div>
                            <div class="card-body text-center">
                                <div class="success-icon mb-3">
                                    <i class="ti-check-box text-success" style="font-size: 48px;"></i>
                                </div>
                                <h5 class="text-success mb-2">Pembayaran Berhasil!</h5>
                                <p class="text-muted">Bukti pembayaran telah dikirim</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Custom Styles for Panduan Page */
        .step-content {
            padding: 20px 0;
        }

        .step-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            border-radius: 50%;
            margin-bottom: 20px;
        }

        .step-number .number {
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        .step-title {
            color: #333;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .step-description {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .step-tips {
            padding: 10px 15px;
            background: #f8f9fa;
            border-left: 3px solid #ff6b35;
            border-radius: 4px;
        }

        .step-illustration {
            padding: 20px;
        }

        .mockup-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 400px;
            margin: 0 auto;
        }

        .mockup-card .card-header {
            padding: 15px 20px;
            font-weight: 600;
        }

        .mockup-card .card-body {
            padding: 20px;
        }

        .payment-methods {
            margin-top: 20px;
        }

        .payment-option {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 10px 0;
        }

        .payment-option i {
            font-size: 20px;
            margin-right: 15px;
            width: 25px;
        }

        .payment-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .payment-item {
            text-align: center;
            padding: 15px 10px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-item:hover,
        .payment-item.active {
            border-color: #ff6b35;
            background: #fff5f0;
        }

        .payment-item i {
            font-size: 24px;
            color: #666;
            margin-bottom: 5px;
            display: block;
        }

        .payment-item.active i {
            color: #ff6b35;
        }

        .payment-item span {
            font-size: 12px;
            color: #666;
        }

        .success-icon {
            padding: 20px;
        }

        .cta-buttons .btn {
            margin: 5px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .step-content {
                text-align: center !important;
            }

            .payment-grid {
                grid-template-columns: 1fr;
            }

            .cta-buttons .btn {
                display: block;
                width: 100%;
                margin: 10px 0;
            }

            .flex-row-reverse {
                flex-direction: column !important;
            }
        }
    </style>

@endsection
