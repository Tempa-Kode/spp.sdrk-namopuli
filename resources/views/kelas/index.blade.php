@extends('template')
@section('title', 'Data Kelas')
@section('page', 'Data Kelas')
@section('body')
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Daftar Kelas</h6>
                <a href="{{ route('kelas.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Kelas
                </a>
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

                @if($kelas->count() > 0)
                    <div class="table-responsive p-0">
                        <table class="datatable table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tingkat</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Wali Kelas</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah Siswa</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kelas as $index => $item)
                                    <tr>
                                        <td class="text-center">
                                            <span class="text-secondary text-xs font-weight-bold">{{ $loop->iteration }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <span class="badge badge-sm bg-gradient-info">Kelas {{ $item->tingkat_kelas }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <p class="text-xs text-secondary mb-0">
                                                        {{ $item->waliKelas ? $item->waliKelas->nama : '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <p class="text-xs text-secondary mb-0">
                                                        {{ $item->siswa->count() }} siswa
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center">
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route('kelas.edit', $item->id) }}" class="btn btn-outline-primary btn-sm me-2" title="Edit Data">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('kelas.destroy', $item->id) }}" method="POST"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $kelas->withQueryString()->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <img src="{{ asset('assets/img/empty-state.svg') }}" alt="No data" class="img-fluid mb-3" style="max-width: 200px;">
                        <h5 class="text-muted">Belum ada data kelas</h5>
                        <p class="text-muted">Klik tombol "Tambah Kelas" untuk menambah data kelas baru.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
