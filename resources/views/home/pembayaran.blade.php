@extends("template-home")
@section("title", "Pembayaran SPP - SD RK Namopuli")
@section("body")
    <!-- Hero Section -->
    <section class="banner_part_plain" style="padding: 100px 0 50px 0;">
        <div class="container">
            <div class="row align-items-center justify-content-center text-center">
                <div class="col-lg-8">
                    <div class="banner_text">
                        <div class="banner_text_iner">
                            <h1 class="mb-4">Pembayaran SPP</h1>
                            <p class="lead text-white">
                                Selesaikan Pembayaran SPP dengan Aman dan Mudah
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Payment Section -->
    <section class="p-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row">
                        <!-- Payment Details Card -->
                        <div class="col-lg-8">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4 class="card-title mb-0">
                                        <i class="ti-credit-card mr-2 text-primary"></i>
                                        Detail Pembayaran
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <!-- Student Info -->
                                    <div class="student-info mb-4 p-3" style="background: #f8f9fa; border-radius: 8px;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="text-muted mb-1">Nama Siswa</h6>
                                                <p class="mb-2 font-weight-bold">{{ $tagihan->siswa->nama_siswa }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="text-muted mb-1">NISN</h6>
                                                <p class="mb-2 font-weight-bold">{{ $tagihan->siswa->nisn }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="text-muted mb-1">Kelas</h6>
                                                <p class="mb-2 font-weight-bold">
                                                    {{ $tagihan->siswa->kelas->nama_kelas ?? "-" }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="text-muted mb-1">Periode</h6>
                                                <p class="mb-2 font-weight-bold">
                                                    {{ \Carbon\Carbon::createFromFormat("Y-m", $tagihan->bulan)->locale("id")->isoFormat("MMMM Y") }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Payment Amount -->
                                    <div class="payment-amount mb-4 p-3"
                                        style="background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%); border-radius: 8px;">
                                        <div class="text-center text-white">
                                            <h6 class="mb-1">Total Pembayaran</h6>
                                            <h2 class="mb-0 font-weight-bold">
                                                {{ "Rp " . number_format($tagihan->tarif->nominal, 0, ",", ".") }}
                                            </h2>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="button"
                                            data-tagihan-id="{{ $tagihan->id }}"
                                            data-jumlah="{{ $tagihan->tarif->nominal }}"
                                            id="bayar" class="btn btn-success btn-lg px-5"
                                        >
                                            <i class="ti-check mr-2"></i>
                                            Bayar Sekarang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Summary Card -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="ti-receipt mr-2"></i>
                                        Ringkasan
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="summary-item d-flex justify-content-between mb-2">
                                        <span>SPP
                                            {{ \Carbon\Carbon::createFromFormat("Y-m", $tagihan->bulan)->locale("id")->isoFormat("MMMM Y") }}</span>
                                        <span>{{ "Rp " . number_format($tagihan->tarif->nominal, 0, ",", ".") }}</span>
                                    </div>
                                    <div class="summary-item d-flex justify-content-between mb-2">
                                        <span>Biaya Admin</span>
                                        <span class="text-success">Gratis</span>
                                    </div>
                                    <hr>
                                    <div class="summary-total d-flex justify-content-between">
                                        <strong>Total Bayar</strong>
                                        <strong
                                            class="text-primary">{{ "Rp " . number_format($tagihan->tarif->nominal, 0, ",", ".") }}</strong>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="security-info text-center">
                                        <small class="text-muted">
                                            <i class="ti-shield mr-1"></i>
                                            Pembayaran menggunakan sistem Midtrans
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Payment Method Cards */
        .payment-method-card .card {
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .payment-method-card .card:hover {
            border-color: #ff6b35;
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.15);
        }

        .payment-method-card .form-check-input:checked+.form-check-label .card {
            border-color: #ff6b35;
            background-color: #fff5f0;
        }

        .payment-method-card .form-check-input {
            display: none;
        }

        .payment-method-card .badge {
            font-size: 10px;
            padding: 3px 6px;
        }

        /* Summary Card */
        .summary-item {
            font-size: 14px;
        }

        .summary-total {
            font-size: 16px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .payment-method-card .d-flex {
                flex-direction: column;
                text-align: center;
            }

            .payment-method-card .payment-icon {
                margin-bottom: 10px;
                margin-right: 0 !important;
            }

            .btn-lg {
                width: 100%;
            }
        }

        /* Animation for form submission */
        .payment-loading {
            display: none;
        }

        .payment-loading.show {
            display: block;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>

@endsection
@push('scripts')
    <script type="text/javascript"
      src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            // Set environment untuk sandbox
            if (typeof snap !== 'undefined') {
                snap.environment = 'sandbox';
            }

            $('#bayar').on('click', function () {
                const payButton = $(this);
                const tagihan_id = payButton.data('tagihan-id');
                const jumlah_bayar = payButton.data('jumlah');

                payButton.prop('disabled', true).text('Memproses...');

                $.ajax({
                    url: '{{ route("home.tagihan-spp.bayar") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        tagihan_id: tagihan_id,
                        jumlah_bayar: jumlah_bayar
                    },
                    success: function(response) {
                        if (!response.snap_token) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Gagal mendapatkan token pembayaran. Silakan coba lagi.',
                            });
                            payButton.prop('disabled', false).html('<i class="fa-solid fa-money-bill"></i> Bayar');
                            return;
                        }

                        // Pastikan snap loaded sebelum digunakan
                        if (typeof snap === 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Midtrans Snap belum termuat. Silakan refresh halaman.',
                            });
                            payButton.prop('disabled', false).html('<i class="fa-solid fa-money-bill"></i> Bayar');
                            return;
                        }

                        snap.pay(response.snap_token, {
                            onSuccess: function(result){
                                console.log('Success:', result);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Pembayaran Berhasil!',
                                    text: 'Terima kasih, pembayaran Anda telah kami terima.',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    updateStatus(result.order_id);
                                });
                            },
                            onPending: function(result){
                                console.log('Pending:', result);
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Menunggu Pembayaran',
                                    text: 'Selesaikan pembayaran Anda sesuai instruksi yang diberikan.',
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            onError: function(result){
                                console.log('Error:', result);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Pembayaran Gagal',
                                    text: 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.'
                                });
                                payButton.prop('disabled', false).html('<i class="fa-solid fa-money-bill"></i> Bayar');
                            },
                            onClose: function(){
                                console.log('Popup ditutup oleh pengguna.');
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Dibatalkan',
                                    text: 'Anda menutup jendela pembayaran sebelum selesai.'
                                });
                                payButton.prop('disabled', false).html('<i class="fa-solid fa-money-bill"></i> Bayar');
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Koneksi Gagal',
                            text: 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.'
                        });
                        payButton.prop('disabled', false).html('<i class="fa-solid fa-money-bill"></i> Bayar');
                    }
                });

                function updateStatus($order_id){
                    $.ajax({
                        url: '{{ route("home.tagihan-spp.update-status.pembayaran") }}',
                        method: 'PUT',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'PUT',
                            kd_transaksi : $order_id
                        },
                        success: function(response) {
                            window.location.href = '{{ route("home.cek-tagihan") }}';
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Gagal memperbarui status pembayaran. Silakan hubungi admin.'
                            });
                        }
                    });
                }
            });
        });
    </script>
@endpush
