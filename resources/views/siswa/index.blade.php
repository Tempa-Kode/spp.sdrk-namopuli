@extends("template")
@section("title", "Data Siswa")
@section("page", "Data Siswa")
@section("body")
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Data Siswa</h6>
                <a href="" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Siswa
                </a>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="datatable table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                    width="5%">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2"
                                    width="25%">Nama Siswa</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                    width="15%">NISN</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                    width="15%">Kelas</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                    width="15%">Jenis Kelamin</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                    width="25%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                <tr>
                                    <td class="text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $loop->iteration }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $item->nama_siswa }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $item->nisn }}</span>
                                    </td>
                                    <td class="text-center">{{ $item->kelas->nama_kelas ?? "-" }}</td>
                                    <td class="text-center">
                                        <span
                                            class="badge badge-sm {{ $item->jenkel == "L" ? "bg-gradient-primary" : "bg-gradient-secondary" }}">
                                            {{ $item->jenis_kelamin_lengkap }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="" class="btn btn-outline-primary btn-sm me-2" title="Edit Data">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="" method="POST" class="d-inline">
                                            @csrf
                                            @method("DELETE")
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus data siswa {{ $item->nama_siswa }}?')"
                                                title="Hapus Data">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-users fa-3x text-secondary mb-3"></i>
                                            <h6 class="text-secondary mb-0">Belum ada data siswa</h6>
                                            <small class="text-muted">Data siswa akan muncul di sini setelah
                                                ditambahkan</small>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
