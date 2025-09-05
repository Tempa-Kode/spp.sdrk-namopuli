@extends('template')
@section('title', 'Tagihan SPP')
@section('page', 'Tagihan SPP')
@section('body')
    <div class="col-12">
        @if (session('success'))
            <div class="alert alert-success text-white">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger text-white">
                {{ session('error') }}
            </div>
        @endif
        <div class="card mb-4 mt-2">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Tagihan SPP</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <input type="hidden" name="tagihan_id" value="{{ $tagihan->id }}">
                    <label for="kode_tagihan" class="col-sm-3 col-form-label">Kode Tagihan</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="kode_tagihan" readonly value="{{ $tagihan->kode_tagihan }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="nama_siswa" class="col-sm-3 col-form-label">Nama Siswa</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="nama_siswa" readonly value="{{ $tagihan->siswa->nama_siswa }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="jumlah_bayar" class="col-sm-3 col-form-label">Nominal Tagihan</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="jumlah_bayar" readonly value="{{ $tagihan->tarif->nominal }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="kelas" class="col-sm-3 col-form-label">Kelas</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="kelas" readonly value="Kelas {{ $tagihan->siswa->kelas->tingkat_kelas ?? '-' }}">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="status" class="col-sm-3 col-form-label">Status</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="status" readonly value="{{ $tagihan->status == 'belum_bayar' ? 'Belum Bayar' : 'Lunas' }}">
                    </div>
                </div>
                <div class="d-flex gap-2">
                @if($tagihan->status == 'belum_bayar')
                    <div class="mb-3">
                        <button type="button" class="btn btn-success" id="bayar"
                            data-tagihan-id="{{ $tagihan->id }}"
                            data-jumlah="{{ $tagihan->tarif->nominal }}"
                        >
                            <i class="fa-solid fa-money-bill"></i> Bayar
                        </button>
                        @if (isset($tagihan->transaksi) && isset($tagihan->transaksi->kd_transaksi))
                            <form action="{{ route('tagihan-spp.update-status.pembayaran') }}" method="post" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="kd_transaksi" value="{{ $tagihan->transaksi->kd_transaksi }}">
                                <button type="submit" class="btn btn-outline-warning"><i class="fa-solid fa-arrows-spin"></i> Cek Status</button>
                            </form>
                        @endif
                    </div>
                    @endif
                    <div class="mb-3">
                        <a href="{{ route('tagihan-spp.kuitansi', $tagihan->id) }}" target="_blank" class="btn btn-outline-warning">
                            <i class="fa-solid fa-file-arrow-down"></i> Kuitansi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                    url: '{{ route("tagihan-spp.bayar") }}',
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
                                    // location.reload();
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
                        url: '{{ route("tagihan-spp.update-status.pembayaran") }}',
                        method: 'PUT',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'PUT',
                            kd_transaksi : $order_id
                        },
                        success: function(response) {
                            location.reload();
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
