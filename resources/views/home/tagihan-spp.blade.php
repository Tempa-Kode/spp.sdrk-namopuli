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
                            <h1 class="mb-4">Tagihan SPP</h1>
                            <p class="lead text-white">
                                Cek dan Bayar Tagihan SPP Putra-Putri Anda dengan Mudah dan Aman
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Tagihan Section --}}
    <section class="p-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="ti-receipt mr-2"></i>Tagihan SPP
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Bulan</th>
                                        <th scope="col">Nominal</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($tagihan as $item)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $item->siswa->nama_siswa }}</td>
                                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $item->bulan)->locale('id')->isoFormat('MMMM Y') }}</td>
                                            <td>{{ 'Rp ' . number_format($item->tarif->nominal, 0, ',', '.') }}</td>
                                            <td>
                                                @if($item->status == 'belum_bayar')
                                                    <span class="badge badge-warning text-white p-2">
                                                        <i class="ti-time mr-1"></i>
                                                        Belum Bayar
                                                    </span>
                                                @else
                                                    <span class="badge badge-success text-white p-2">
                                                        <i class="ti-check mr-1"></i>
                                                        Lunas
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-block">
                                                    @if($item->status == 'belum_bayar')
                                                        <a href="{{ route('home.pembayaran', $item->id) }}" class="btn btn-primary btn-sm d-block">
                                                            <i class="ti-credit-card mr-1"></i> Bayar
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('home.tagihan-spp.kuitansi', $item->id) }}" class="btn btn-outline-secondary btn-sm d-block mt-1">
                                                            <i class="ti-download mr-1"></i>Kuitansi
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada tagihan yang ditemukan.</td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('tagihan-spp.wali') }}" class="btn_1">
                                <i class="ti-receipt mr-2"></i>
                                Lihat Riwayat Tagihan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
