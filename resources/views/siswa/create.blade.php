@extends("template")
@section("title", "Tambah Siswa")
@section("page", "Tambah Siswa")
@section("body")
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Tambah Data Siswa</h6>
                <a href="{{ route("siswa.index") }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                @if (session("error"))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session("error") }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route("siswa.store") }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama_siswa" class="form-label">Nama Siswa <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error("nama_siswa") is-invalid @enderror"
                                    id="nama_siswa" name="nama_siswa" value="{{ old("nama_siswa") }}" required>
                                @error("nama_siswa")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nisn" class="form-label">NISN <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error("nisn") is-invalid @enderror"
                                    id="nisn" name="nisn" value="{{ old("nisn") }}" required>
                                @error("nisn")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="kelas_id" class="form-label">Kelas <span class="text-danger">*</span></label>
                                <select class="form-select @error("kelas_id") is-invalid @enderror" id="kelas_id"
                                    name="kelas_id" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($kelas as $item)
                                        <option value="{{ $item->id }}"
                                            {{ old("kelas_id") == $item->id ? "selected" : "" }}>
                                            Kelas {{ $item->tingkat_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                                @error("kelas_id")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input @error("jenkel") is-invalid @enderror" type="radio"
                                            name="jenkel" id="laki_laki" value="L"
                                            {{ old("jenkel") == "L" ? "checked" : "" }} required>
                                        <label class="form-check-label" for="laki_laki">
                                            Laki-laki
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input @error("jenkel") is-invalid @enderror" type="radio"
                                            name="jenkel" id="perempuan" value="P"
                                            {{ old("jenkel") == "P" ? "checked" : "" }} required>
                                        <label class="form-check-label" for="perempuan">
                                            Perempuan
                                        </label>
                                    </div>
                                </div>
                                @error("jenkel")
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tempat_lahir" class="form-label">Tempat Lahir <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error("tempat_lahir") is-invalid @enderror"
                                    id="tempat_lahir" name="tempat_lahir" value="{{ old("tempat_lahir") }}" required>
                                @error("tempat_lahir")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span
                                        class="text-danger">*</span></label>
                                <input type="date" class="form-control @error("tanggal_lahir") is-invalid @enderror"
                                    id="tanggal_lahir" name="tanggal_lahir" value="{{ old("tanggal_lahir") }}" required>
                                @error("tanggal_lahir")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="agama" class="form-label">Agama <span class="text-danger">*</span></label>
                                <select class="form-select @error("agama") is-invalid @enderror" id="agama"
                                    name="agama" required>
                                    <option value="">Pilih Agama</option>
                                    <option value="Islam" {{ old("agama") == "Islam" ? "selected" : "" }}>Islam</option>
                                    <option value="Kristen" {{ old("agama") == "Kristen" ? "selected" : "" }}>Kristen
                                    </option>
                                    <option value="Katolik" {{ old("agama") == "Katolik" ? "selected" : "" }}>Katolik
                                    </option>
                                    <option value="Hindu" {{ old("agama") == "Hindu" ? "selected" : "" }}>Hindu</option>
                                    <option value="Buddha" {{ old("agama") == "Buddha" ? "selected" : "" }}>Buddha
                                    </option>
                                    <option value="Konghucu" {{ old("agama") == "Konghucu" ? "selected" : "" }}>Konghucu
                                    </option>
                                </select>
                                @error("agama")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nomor_telp_orangtua" class="form-label">No Telp Orang Tua</label>
                                <input type="text" class="form-control @error("nomor_telp_orangtua") is-invalid @enderror"
                                    id="nomor_telp_orangtua" name="nomor_telp_orangtua"
                                    value="{{ old("nomor_telp_orangtua") }}">
                                @error("nomor_telp_orangtua")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route("siswa.index") }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
