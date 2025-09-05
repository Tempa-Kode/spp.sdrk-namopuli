@extends('template')
@section('title', 'Tagihan SPP')
@section('page', 'Tagihan SPP')
@section('body')
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Daftar Tagihan SPP</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
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

                @if($tagihan->count() > 0)
                    <div class="table-responsive p-0">
                        <table class="datatable table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode Tagihan</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Siswa</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kelas</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bulan</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nominal</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tagihan as $index => $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $loop->iteration }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <p class="text-xs text-secondary mb-0">{{ $item->kode_tagihan ?? '-' }}</p>
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
                                                        Kelas {{ $item->siswa->kelas->tingkat_kelas ?? '-' }}
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
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <a href="{{ route('tagihan-spp.wali.detail', $item->id) }}" class="btn btn-sm btn-outline-success">Detail</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <h5 class="text-muted">Belum Ada Tagihan</h5>
                        <p class="text-muted">Saat ini tidak ada tagihan SPP</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
