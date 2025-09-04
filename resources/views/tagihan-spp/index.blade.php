@extends('template')
@section('title', 'Tagihan SPP')
@section('page', 'Tagihan SPP')
@section('body')
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Daftar Tagihan SPP</h6>
                <div class="d-flex gap-2">
                    @if(!$currentMonthTagihan || $currentMonthTagihanCount < $jumlahSiswa)
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#generateModal">
                            <i class="fas fa-plus-circle"></i> Buat Tagihan Bulan Ini
                        </button>
                    @endif
                    @if (Auth::user()->role != 'wali' && Auth::user()->role != 'wali_kelas')
                        <a href="{{ route('tagihan-spp.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Manual
                        </a>
                    @endif
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <!-- Filter Section -->
                <div class="row mb-3 px-3">
                    <div class="col-md-3">
                        <form method="GET" action="{{ route('tagihan-spp.index') }}" id="filterForm">
                            <select name="bulan" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                <option value="">Semua Bulan</option>
                                @foreach($bulanList as $bulan)
                                    <option value="{{ $bulan }}" {{ request('bulan') == $bulan ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::createFromFormat('Y-m', $bulan)->format('F Y') }}
                                    </option>
                                @endforeach
                            </select>
                    </div>
                    <div class="col-md-3">
                            <select name="status" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                <option value="">Semua Status</option>
                                <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                            </select>
                    </div>
                    @can('filter-data')
                    <div class="col-md-3">
                            <select name="kelas_id" class="form-select" onchange="document.getElementById('filterForm').submit()">
                                <option value="">Semua Kelas</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                        {{ $kelas->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                    </div>
                    @endcan
                    <div class="col-md-2">
                            <a href="{{ route('tagihan-spp.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-undo"></i> Reset
                            </a>
                        </form>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mx-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mx-3" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($tagihanSpp->count() > 0)
                    <div class="table-responsive p-0">
                        <table class="datatable table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Siswa</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kelas</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bulan</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nominal</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    @can('filter-data')
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tagihanSpp as $index => $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $tagihanSpp->firstItem() + $index }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $item->siswa->nama_siswa ?? '-' }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $item->siswa->nisn ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <p class="text-xs text-secondary mb-0">
                                                        {{ $item->siswa->kelas ? "Kelas {$item->siswa->kelas->tingkat_kelas}" : '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <span class="badge badge-sm bg-gradient-info">
                                                        {{ \Carbon\Carbon::createFromFormat('Y-m', $item->bulan)->locale('id')->translatedFormat('M Y') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">Rp {{ number_format($item->tarif->nominal ?? 0, 0, ',', '.') }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    @if($item->status == 'lunas')
                                                        <span class="badge badge-sm bg-gradient-success">Lunas</span>
                                                    @else
                                                        <span class="badge badge-sm bg-gradient-warning">Belum Bayar</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        @can('filter-data')
                                            <td>
                                                <a href="{{ route('tagihan-spp.edit', $item) }}" class="btn btn-sm btn-outline-warning font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit Tagihan">
                                                    Edit
                                                </a>
                                                <form action="{{ route('tagihan-spp.destroy', $item) }}" class="d-inline" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tagihan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Hapus Tagihan">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        @endcan
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <h5 class="text-muted">Belum ada tagihan SPP</h5>
                        <p class="text-muted">
                            @if(!$currentMonthTagihan)
                                Klik tombol "Buat Tagihan Bulan Ini" untuk membuat tagihan otomatis atau "Tambah Manual" untuk menambah tagihan individual.
                            @else
                                Klik tombol "Tambah Manual" untuk menambah tagihan individual.
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Generate Tagihan -->
    <div class="modal fade" id="generateModal" tabindex="-1" aria-labelledby="generateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="generateModalLabel">Buat Tagihan SPP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tagihan-spp.generate') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="bulan" class="form-label">Bulan <span class="text-danger">*</span></label>
                            <input type="month" class="form-control" id="bulan" name="bulan"
                                   value="{{ $currentMonth }}" required>
                            <div class="form-text">Pilih bulan untuk pembuatan tagihan SPP.</div>
                        </div>
                        <div class="alert alert-info text-white">
                            <i class="fas fa-info-circle"></i>
                            <strong>Catatan:</strong> Sistem akan membuat tagihan untuk semua siswa aktif berdasarkan tarif SPP yang sesuai dengan tingkat kelas masing-masing.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda yakin ingin membuat tagihan untuk semua siswa?')">
                            <i class="fas fa-plus-circle"></i> Buat Tagihan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
