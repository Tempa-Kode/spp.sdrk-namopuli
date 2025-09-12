@extends("template")
@section("title", "Dashboard")
@section("page", "Dashboard")
@section("body")
    <div class="col-12">
        <!-- Statistics Cards -->
        <div class="row gy-3 mb-4">
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card">
                    <span class="mask bg-primary opacity-10 border-radius-lg"></span>
                    <div class="card-body p-3 position-relative">
                        <div class="row">
                            <div class="col-8 text-start">
                                <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                    <i class="fas fa-user-graduate text-primary text-gradient text-lg opacity-10"
                                        aria-hidden="true"></i>
                                </div>
                                <h5 class="text-white font-weight-bolder mb-0 mt-3">
                                    {{ number_format($totalSiswa) }}
                                </h5>
                                <span class="text-white text-sm">Total Siswa</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card">
                    <span class="mask bg-dark opacity-10 border-radius-lg"></span>
                    <div class="card-body p-3 position-relative">
                        <div class="row">
                            <div class="col-8 text-start">
                                <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                    <i class="fas fa-chalkboard-teacher text-dark text-gradient text-lg opacity-10"
                                        aria-hidden="true"></i>
                                </div>
                                <h5 class="text-white font-weight-bolder mb-0 mt-3">
                                    {{ number_format($totalGuruPegawai) }}
                                </h5>
                                <span class="text-white text-sm">Guru & Pegawai</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card">
                    <span class="mask bg-success opacity-10 border-radius-lg"></span>
                    <div class="card-body p-3 position-relative">
                        <div class="row">
                            <div class="col-8 text-start">
                                <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                    <i class="fas fa-check-circle text-success text-gradient text-lg opacity-10"
                                        aria-hidden="true"></i>
                                </div>
                                <h5 class="text-white font-weight-bolder mb-0 mt-3">
                                    {{ number_format($transaksiLunasBulanIni) }}
                                </h5>
                                <span class="text-white text-sm">Lunas Bulan Ini</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card">
                    <span class="mask bg-warning opacity-10 border-radius-lg"></span>
                    <div class="card-body p-3 position-relative">
                        <div class="row">
                            <div class="col-8 text-start">
                                <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                    <i class="fas fa-clock text-warning text-gradient text-lg opacity-10"
                                        aria-hidden="true"></i>
                                </div>
                                <h5 class="text-white font-weight-bolder mb-0 mt-3">
                                    {{ number_format($tagihanBelumBayarBulanIni) }}
                                </h5>
                                <span class="text-white text-sm">Belum Bayar Bulan Ini</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Statistics -->
        <div class="row gy-3 mb-4">
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card">
                    <span class="mask bg-info opacity-10 border-radius-lg"></span>
                    <div class="card-body p-3 position-relative">
                        <div class="row">
                            <div class="col-8 text-start">
                                <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                    <i class="fas fa-building text-info text-gradient text-lg opacity-10"
                                        aria-hidden="true"></i>
                                </div>
                                <h5 class="text-white font-weight-bolder mb-0 mt-3">
                                    {{ number_format($totalKelas) }}
                                </h5>
                                <span class="text-white text-sm">Total Kelas</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-6 col-12">
                <div class="card">
                    <span class="mask bg-gradient-success opacity-10 border-radius-lg"></span>
                    <div class="card-body p-3 position-relative">
                        <div class="row">
                            <div class="col-8 text-start">
                                <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                    <i class="fas fa-money-bill-wave text-success text-gradient text-lg opacity-10"
                                        aria-hidden="true"></i>
                                </div>
                                <h5 class="text-white font-weight-bolder mb-0 mt-3">
                                    Rp {{ number_format($totalNominalBulanIni, 0, ",", ".") }}
                                </h5>
                                <span class="text-white text-sm">Total Pendapatan SPP Bulan
                                    {{ \Carbon\Carbon::createFromFormat("Y-m", $currentMonth)->format("F Y") }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Transaksi SPP Terbaru</h6>
                            <a href="{{ route("tagihan-spp.index") }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye"></i> Lihat Semua
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        @if ($transaksiTerbaru->count() > 0)
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Siswa</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Kelas</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Bulan</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Nominal</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Status</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Tanggal Update</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transaksiTerbaru as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">{{ $item->siswa->nama_siswa ?? "-" }}</h6>
                                                            <p class="text-xs text-secondary mb-0">
                                                                {{ $item->siswa->nisn ?? "-" }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <p class="text-xs text-secondary mb-0">
                                                                Kelas {{ $item->siswa->kelas->tingkat_kelas ?? "-" }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <span class="badge badge-sm bg-gradient-info">
                                                                {{ \Carbon\Carbon::createFromFormat("Y-m", $item->bulan)->format("M Y") }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">Rp
                                                                {{ number_format($item->tarif->nominal ?? 0, 0, ",", ".") }}
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <span class="badge badge-sm bg-gradient-success">Lunas</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <p class="text-xs text-secondary mb-0">
                                                                {{ $item->updated_at->format("d M Y H:i") }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                                <h6 class="text-muted mt-3">Belum ada transaksi SPP</h6>
                                <p class="text-muted text-sm">Transaksi yang sudah lunas akan ditampilkan di sini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
