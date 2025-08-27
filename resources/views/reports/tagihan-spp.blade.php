@extends("template")
@section("title", "Laporan Tagihan SPP")
@section("page", "Laporan Tagihan SPP")
@section("body")
    <div class="col-12">
        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6 class="mb-0">Filter Laporan Tagihan SPP</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route("reports.tagihan-spp.preview") }}" id="filterForm">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="kelas_id" class="form-label">Kelas</label>
                                <select class="form-select" id="kelas_id" name="kelas_id">
                                    <option value="">Semua Kelas</option>
                                    @foreach ($kelasList as $kelas)
                                        <option value="{{ $kelas->id }}"
                                            {{ request("kelas_id") == $kelas->id ? "selected" : "" }}>
                                            {{ $kelas->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="bulan" class="form-label">Bulan</label>
                                <input type="month" class="form-control" id="bulan" name="bulan"
                                    value="{{ request("bulan") }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">Semua Status</option>
                                    <option value="belum_bayar" {{ request("status") == "belum_bayar" ? "selected" : "" }}>
                                        Belum Bayar</option>
                                    <option value="lunas" {{ request("status") == "lunas" ? "selected" : "" }}>Lunas
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Preview Data
                        </button>
                        <button type="button" class="btn btn-success" onclick="generatePDF()">
                            <i class="fas fa-file-pdf"></i> Download PDF
                        </button>
                        <a href="{{ route("reports.tagihan-spp") }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistics Section -->
        @if (isset($tagihan) && $tagihan->count() > 0)
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="d-flex">
                                <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                    <i class="fas fa-list text-lg text-white opacity-10"></i>
                                </div>
                                <div class="ms-3">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Tagihan</p>
                                    <h5 class="font-weight-bolder mb-0">{{ number_format($totalTagihan ?? 0) }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="d-flex">
                                <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                    <i class="fas fa-money-bill-wave text-lg text-white opacity-10"></i>
                                </div>
                                <div class="ms-3">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Nominal</p>
                                    <h5 class="font-weight-bolder mb-0">Rp
                                        {{ number_format($totalNominal ?? 0, 0, ",", ".") }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="d-flex">
                                <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                    <i class="fas fa-check-circle text-lg text-white opacity-10"></i>
                                </div>
                                <div class="ms-3">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Lunas</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $tagihan->where("status", "lunas")->count() }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="d-flex">
                                <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                    <i class="fas fa-clock text-lg text-white opacity-10"></i>
                                </div>
                                <div class="ms-3">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Belum Bayar</p>
                                    <h5 class="font-weight-bolder mb-0">
                                        {{ $tagihan->where("status", "belum_bayar")->count() }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Data Table -->
        @if (isset($tagihan))
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Data Tagihan SPP</h6>
                        @if ($tagihan->count() > 0)
                            <button type="button" class="btn btn-success btn-sm" onclick="generatePDF()">
                                <i class="fas fa-file-pdf"></i> Download PDF
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    @if ($tagihan->count() > 0)
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No
                                        </th>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tagihan as $index => $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $tagihan->firstItem() + $index }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $item->siswa->nama_siswa ?? "-" }}
                                                        </h6>
                                                        <p class="text-xs text-secondary mb-0">
                                                            {{ $item->siswa->nisn ?? "-" }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <p class="text-xs text-secondary mb-0">
                                                            {{ $item->siswa->kelas->nama_kelas ?? "-" }}
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
                                                        @if ($item->status == "lunas")
                                                            <span class="badge badge-sm bg-gradient-success">Lunas</span>
                                                        @else
                                                            <span class="badge badge-sm bg-gradient-warning">Belum
                                                                Bayar</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $tagihan->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-search text-muted" style="font-size: 3rem;"></i>
                            <h6 class="text-muted mt-3">Tidak ada data yang sesuai filter</h6>
                            <p class="text-muted text-sm">Silakan ubah kriteria filter untuk melihat data tagihan SPP.</p>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-chart-bar text-muted" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mt-3">Laporan Tagihan SPP</h5>
                    <p class="text-muted">Gunakan filter di atas untuk melihat data tagihan SPP dan mengunduh laporan PDF.
                    </p>
                </div>
            </div>
        @endif
    </div>

    <script>
        function generatePDF() {
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            const params = new URLSearchParams();

            for (let [key, value] of formData.entries()) {
                if (value) {
                    params.append(key, value);
                }
            }

            const url = "{{ route("reports.tagihan-spp.pdf") }}" + '?' + params.toString();
            window.open(url, '_blank');
        }
    </script>
@endsection
