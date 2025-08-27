@extends("template")
@section("title", "Data Guru/Pegawai")
@section("page", "Data Guru/Pegawai")
@section("body")
    @if (session("success"))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session("success") }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Data Guru/Pegawai</h6>
                <a href="{{ route("guru-pegawai.create") }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Guru/Pegawai
                </a>
            </div>

            <!-- Filter Section -->
            <div class="card-body pt-0 pb-3">
                <div class="bg-light rounded p-3 mb-3">
                    <form method="GET" action="{{ route("guru-pegawai.index") }}">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <label for="jabatan" class="form-label text-sm font-weight-bold mb-2">
                                    <i class="fas fa-filter text-primary"></i> Filter Berdasarkan Jabatan
                                </label>
                                <select class="form-select" id="jabatan" name="jabatan" onchange="this.form.submit()">
                                    <option value="">Semua Jabatan</option>
                                    @foreach ($jabatanList as $jabatan)
                                        <option value="{{ $jabatan }}"
                                            {{ request("jabatan") == $jabatan ? "selected" : "" }}>
                                            {{ $jabatan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="jenkel" class="form-label text-sm font-weight-bold mb-2">
                                    <i class="fas fa-filter text-primary"></i> Filter Jenis Kelamin
                                </label>
                                <select class="form-select" id="jenkel" name="jenkel" onchange="this.form.submit()">
                                    <option value="">Semua</option>
                                    <option value="L" {{ request("jenkel") == "L" ? "selected" : "" }}>Laki-laki
                                    </option>
                                    <option value="P" {{ request("jenkel") == "P" ? "selected" : "" }}>Perempuan
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                @if (request("jabatan") || request("jenkel"))
                                    <a href="{{ route("guru-pegawai.index") }}" class="btn btn-outline-secondary w-100">
                                        <i class="fas fa-times"></i> Reset Filter
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Filter Status -->
                @if (request("jabatan") || request("jenkel"))
                    <div class="alert alert-primary py-2 px-3 mb-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-2"></i>
                            <span class="text-sm mb-0">
                                Filter aktif:
                                @if (request("jabatan"))
                                    <strong>Jabatan: {{ request("jabatan") }}</strong>
                                @endif
                                @if (request("jabatan") && request("jenkel"))
                                    ,
                                @endif
                                @if (request("jenkel"))
                                    <strong>Jenis Kelamin:
                                        {{ request("jenkel") == "L" ? "Laki-laki" : "Perempuan" }}</strong>
                                @endif
                                <span class="badge bg-white text-primary ms-2">{{ $data->count() }} orang</span>
                            </span>
                        </div>
                    </div>
                @else
                    <div class="alert alert-light py-2 px-3 mb-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-users me-2 text-muted"></i>
                            <span class="text-sm mb-0 text-muted">
                                Menampilkan semua guru/pegawai
                                <span class="badge bg-secondary ms-2">{{ $data->count() }} orang</span>
                            </span>
                        </div>
                    </div>
                @endif
            </div>

            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="datatable table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center"
                                    width="5%">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2"
                                    width="20%">Nama</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                    width="12%">NUPTK</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                    width="12%">Jenis Kelamin</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                    width="15%">Tempat, Tanggal Lahir</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                    width="16%">Jabatan</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                    width="20%">Aksi</th>
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
                                                <h6 class="mb-0 text-sm">{{ $item->nama }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $item->nuptk }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge badge-sm {{ $item->jenkel == "L" ? "bg-gradient-primary" : "bg-gradient-secondary" }}">
                                            {{ $item->jenis_kelamin_lengkap }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-secondary text-xs">
                                            {{ $item->tempat_lahir }},
                                            {{ \Carbon\Carbon::parse($item->tanggal_lahir)->format("d/m/Y") }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-sm bg-gradient-success">{{ $item->jabatan }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route("guru-pegawai.edit", $item->id) }}"
                                            class="btn btn-outline-primary btn-sm me-2" title="Edit Data">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route("guru-pegawai.destroy", $item->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method("DELETE")
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus data {{ $item->nama }}?')"
                                                title="Hapus Data">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i
                                                class="fas fa-{{ request()->hasAny(["jabatan", "jenkel"]) ? "filter" : "users" }} fa-3x text-secondary mb-3"></i>
                                            <h6 class="text-secondary mb-0">
                                                @if (request()->hasAny(["jabatan", "jenkel"]))
                                                    Tidak ada data guru/pegawai yang sesuai dengan filter
                                                @else
                                                    Belum ada data guru/pegawai
                                                @endif
                                            </h6>
                                            <small class="text-muted">
                                                @if (request()->hasAny(["jabatan", "jenkel"]))
                                                    Coba ubah filter atau reset filter untuk melihat semua data
                                                @else
                                                    Data guru/pegawai akan muncul di sini setelah ditambahkan
                                                @endif
                                            </small>
                                            @if (request()->hasAny(["jabatan", "jenkel"]))
                                                <a href="{{ route("guru-pegawai.index") }}"
                                                    class="btn btn-sm btn-outline-primary mt-2">
                                                    <i class="fas fa-times"></i> Reset Filter
                                                </a>
                                            @endif
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
