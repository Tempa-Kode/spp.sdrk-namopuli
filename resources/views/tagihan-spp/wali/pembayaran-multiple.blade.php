@extends("template")
@section("title", "Pembayaran SPP Multiple")
@section("page", "Pembayaran SPP Multiple")
@section("body")
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6 class="mb-0">Pembayaran SPP Multiple Bulan</h6>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Detail Siswa:</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td width="100"><strong>Nama</strong></td>
                                <td>: {{ $tagihanList->first()->siswa->nama_siswa }}</td>
                            </tr>
                            <tr>
                                <td><strong>NISN</strong></td>
                                <td>: {{ $tagihanList->first()->siswa->nisn }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kelas</strong></td>
                                <td>: {{ $tagihanList->first()->siswa->kelas->tingkat_kelas ?? "-" }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-info">
                            <h6><i class="fa fa-info-circle"></i> Informasi Pembayaran</h6>
                            <p class="mb-1">Total Tagihan: <strong>{{ count($tagihanList) }} bulan</strong></p>
                            <p class="mb-0">Total Pembayaran: <strong>Rp {{ number_format($total, 0, ",", ".") }}</strong>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <h6>Detail Tagihan:</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Bulan</th>
                                        <th>Kode Tagihan</th>
                                        <th class="text-end">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tagihanList as $tagihan)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::createFromFormat("Y-m", $tagihan->bulan)->locale("id")->translatedFormat("F Y") }}
                                            </td>
                                            <td>{{ $tagihan->kode_tagihan }}</td>
                                            <td class="text-end">Rp
                                                {{ number_format($tagihan->tarif->nominal ?? 0, 0, ",", ".") }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="3" class="text-end">Total:</th>
                                        <th class="text-end">Rp {{ number_format($total, 0, ",", ".") }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 text-center">
                        <button class="btn btn-success btn-lg" id="pay-button">
                            <i class="fa fa-credit-card"></i> Bayar Sekarang
                        </button>
                        <a href="{{ route("tagihan-spp.wali") }}" class="btn btn-secondary btn-lg ms-2">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Midtrans Snap.js -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config("services.midtrans.client_key") }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function() {
            // SnapToken acquired from previous step
            snap.pay('{{ $snapToken }}', {
                // Optional
                onSuccess: function(result) {
                    console.log('Payment success:', result);

                    // Update payment status
                    fetch('{{ route("tagihan-spp.wali.update-status-multiple") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                kd_transaksi: result.order_id
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Pembayaran berhasil!');
                                window.location.href = data.redirect_url;
                            } else {
                                alert(
                                    'Pembayaran berhasil, namun terjadi kesalahan dalam pembaruan status. Silakan hubungi admin.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert(
                                'Pembayaran berhasil, namun terjadi kesalahan dalam konfirmasi. Silakan hubungi admin.');
                        });
                },
                // Optional
                onPending: function(result) {
                    console.log('Payment pending:', result);
                    alert('Pembayaran pending. Silakan selesaikan pembayaran Anda.');
                },
                // Optional
                onError: function(result) {
                    console.log('Payment error:', result);
                    alert('Terjadi kesalahan dalam pembayaran. Silakan coba lagi.');
                }
            });
        };
    </script>
@endsection
