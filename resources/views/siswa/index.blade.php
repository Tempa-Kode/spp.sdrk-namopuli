@extends("template")
@section("title", "Data Siswa")
@section("page", "Data Siswa")
@section("body")
    <div class="col-12">
        @if (session("success"))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session("success") }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Data Siswa</h6>
                @can('crud-data')
                <a href="{{ route("siswa.create") }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Siswa
                </a>
                @endcan
            </div>

            @can('filter-data')
            <!-- Filter Section -->
            <div class="card-body pt-0 pb-3">
                <div class="bg-light rounded p-3 mb-3">
                    <form method="GET" action="{{ route("siswa.index") }}">
                        <div class="row align-items-end">
                            <div class="col-md-9">
                                <label for="kelas_id" class="form-label text-sm font-weight-bold mb-2">
                                    <i class="fas fa-filter text-primary"></i> Filter Berdasarkan Kelas
                                </label>
                                <div class="row d-flex">
                                    <div class="col-6">
                                        <select class="form-select" id="kelas_id" name="kelas_id" onchange="this.form.submit()">
                                            <option value="">Semua Kelas</option>
                                            @foreach ($kelas as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ request("kelas_id") == $item->id ? "selected" : "" }}>
                                                    {{ $item->nama_kelas }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        @if (request("kelas_id"))
                                            <a href="{{ route("siswa.index") }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-times"></i> Reset Filter
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Filter Status -->
                @if (request("kelas_id"))
                    @php
                        $selectedKelas = $kelas->where("id", request("kelas_id"))->first();
                    @endphp
                    <div class="alert alert-primary py-2 px-3 mb-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-2"></i>
                            <span class="text-sm mb-0">
                                Menampilkan siswa dari kelas: <strong>{{ $selectedKelas->nama_kelas ?? "Unknown" }}</strong>
                                <span class="badge bg-white text-primary ms-2">{{ $data->count() }} siswa</span>
                            </span>
                        </div>
                    </div>
                @else
                    <div class="alert alert-light py-2 px-3 mb-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-users me-2 text-muted"></i>
                            <span class="text-sm mb-0 text-muted">
                                Menampilkan semua siswa
                                <span class="badge bg-secondary ms-2">{{ $data->count() }} siswa</span>
                            </span>
                        </div>
                    </div>
                @endif
            </div>
            @endcan

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
                                    width="10%">No Telp Orang Tua</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                    width="15%">Jenis Kelamin</th>
                                @can('crud-data')
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                    width="25%">Aksi</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
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
                                    <td class="text-center">{{ $item->nomor_telp_orangtua ?? "-" }}</td>
                                    <td class="text-center">
                                        <span
                                            class="badge badge-sm {{ $item->jenkel == "L" ? "bg-gradient-primary" : "bg-gradient-secondary" }}">
                                            {{ $item->jenis_kelamin_lengkap }}
                                        </span>
                                    </td>
                                    @can('crud-data')
                                    <td class="text-center">
                                        <a href="{{ route("siswa.edit", $item->id) }}"
                                            class="btn btn-outline-primary btn-sm me-2" title="Edit Data">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route("siswa.destroy", $item->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method("DELETE")
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus data siswa {{ $item->nama_siswa }}?')"
                                                title="Hapus Data">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
