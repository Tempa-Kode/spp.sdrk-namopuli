@extends("template")
@section("title", "Dashboard")
@section("page", "Dashboard Wali")
@section("body")
    <!-- Informasi Siswa -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header pb-0">
                <div class="d-flex align-items-center">
                    <p class="mb-0">Informasi Siswa</p>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex px-2 py-1">
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">{{ $dataSiswa->nama_siswa }}</h6>
                                <p class="text-xs text-secondary mb-0">NISN: {{ $dataSiswa->nisn }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-end">
                            <h6 class="mb-0">Kelas: {{ $dataSiswa->kelas->tingkat_kelas ?? "Belum ditentukan" }}</h6>
                            <p class="text-xs mb-0">{{ $dataSiswa->jenis_kelamin_lengkap ?? '-' }} | {{ $dataSiswa->agama ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="col-12">
        <div class="row gy-3">
            <!-- Status Tagihan Bulan Ini -->
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <div class="card h-100">
                    <div class="card-body p-3 d-flex flex-column">
                        <div class="row flex-grow-1">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Tagihan Bulan Ini</p>
                                    @if ($tagihanBulanIni)
                                        <h5 class="font-weight-bolder mb-0">
                                            @if ($tagihanBulanIni->status == "lunas")
                                                <span class="text-success">LUNAS</span>
                                            @else
                                                <span class="text-warning">BELUM BAYAR</span>
                                            @endif
                                        </h5>
                                        <p class="mb-0">
                                            <span class="text-sm">Rp
                                                {{ number_format($tagihanBulanIni->tarif->nominal ?? 0, 0, ",", ".") }}</span>
                                        </p>
                                    @else
                                        <h5 class="font-weight-bolder mb-0">
                                            <span class="text-secondary">BELUM ADA</span>
                                        </h5>
                                    @endif
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                    <i class="fas fa-money-bill-wave text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Pembayaran Tahun Ini -->
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <div class="card h-100">
                    <div class="card-body p-3 d-flex flex-column">
                        <div class="row flex-grow-1">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Bayar Tahun Ini</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        Rp {{ number_format($totalBayarTahunIni, 0, ",", ".") }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                    <i class="fas fa-chart-line text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tagihan Belum Bayar -->
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                <div class="card h-100">
                    <div class="card-body p-3 d-flex flex-column">
                        <div class="row flex-grow-1">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Tagihan Belum Bayar</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        {{ $tagihanBelumBayar->count() }} Bulan
                                    </h5>
                                    <p class="mb-0">
                                        <span class="text-sm">Rp
                                            {{ number_format($tagihanBelumBayar->sum(fn($tagihan) => $tagihan->tarif->nominal ?? 0), 0, ",", ".") }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                    <i class="fas fa-exclamation-triangle text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Tagihan Belum Bayar -->
@if ($tagihanBelumBayar->count() > 0)
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header pb-0">
                <h6>Tagihan Belum Bayar</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bulan
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Nominal</th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Status</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tagihanBelumBayar as $tagihan)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">
                                                    {{ \Carbon\Carbon::parse($tagihan->bulan . "-01")->format("F Y") }}
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">Rp
                                            {{ number_format($tagihan->tarif->nominal ?? 0, 0, ",", ".") }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-warning">Belum Bayar</span>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('tagihan-spp.wali.detail', $tagihan->id) }}" class="text-secondary font-weight-bold text-xs"
                                            data-toggle="tooltip" data-original-title="Bayar sekarang">
                                            Bayar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Riwayat Pembayaran -->
@if ($riwayatPembayaran->count() > 0)
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header pb-0">
                <h6>Riwayat Pembayaran Terakhir</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bulan
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Nominal</th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Status</th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Tanggal Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($riwayatPembayaran as $riwayat)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">
                                                    {{ \Carbon\Carbon::parse($riwayat->bulan . "-01")->format("F Y") }}
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">Rp
                                            {{ number_format($riwayat->tarif->nominal ?? 0, 0, ",", ".") }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-success">Lunas</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span
                                            class="text-secondary text-xs font-weight-bold">{{ $riwayat->updated_at->format("d M Y") }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif

<script>
    // Chart configuration
    var ctx = document.getElementById("chart-bars").getContext("2d");

    new Chart(ctx, {
        type: "bar",
        data: {
            labels: {!! json_encode(array_column($chartData, "month")) !!},
            datasets: [{
                label: "Pembayaran SPP",
                tension: 0.4,
                borderWidth: 0,
                borderRadius: 4,
                borderSkipped: false,
                backgroundColor: function(context) {
                    const index = context.dataIndex;
                    const data = {!! json_encode($chartData) !!};
                    const status = data[index].status;

                    if (status === 'lunas') {
                        return '#28a745'; // Green for paid
                    } else if (status === 'belum_bayar') {
                        return '#ffc107'; // Yellow for unpaid
                    } else {
                        return '#6c757d'; // Gray for no bill
                    }
                },
                data: {!! json_encode(array_column($chartData, "nominal")) !!},
                maxBarThickness: 6
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [5, 5]
                    },
                    ticks: {
                        display: true,
                        padding: 10,
                        color: '#b2b9bf',
                        font: {
                            size: 11,
                            family: "Inter",
                            style: 'normal',
                            lineHeight: 2
                        },
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false,
                        borderDash: [5, 5]
                    },
                    ticks: {
                        display: true,
                        color: '#b2b9bf',
                        padding: 20,
                        font: {
                            size: 11,
                            family: "Inter",
                            style: 'normal',
                            lineHeight: 2
                        },
                    }
                },
            },
        },
    });
</script>
@endsection
