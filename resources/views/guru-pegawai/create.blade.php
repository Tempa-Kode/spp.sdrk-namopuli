@extends("template")
@section("title", "Tambah Guru/Pegawai")
@section("page", "Tambah Guru/Pegawai")
@section("body")
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Tambah Data Guru/Pegawai</h6>
                <a href="{{ route("guru-pegawai.index") }}" class="btn btn-secondary btn-sm">
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

                <form action="{{ route("guru-pegawai.store") }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error("nama") is-invalid @enderror"
                                    id="nama" name="nama" value="{{ old("nama") }}" required>
                                @error("nama")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nuptk" class="form-label">NUPTK</label>
                                <input type="text" class="form-control @error("nuptk") is-invalid @enderror"
                                    id="nuptk" name="nuptk" value="{{ old("nuptk") }}">
                                @error("nuptk")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
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
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                                <select class="form-select @error("jabatan") is-invalid @enderror"
                                    id="jabatan" name="jabatan" required>
                                    <option value="">Pilih Jabatan</option>
                                    <option value="Kepala Sekolah" {{ old("jabatan") == "Kepala Sekolah" ? "selected" : "" }}>Kepala Sekolah</option>
                                    <option value="Guru" {{ old("jabatan") == "Guru" ? "selected" : "" }}>Guru</option>
                                    <option value="Tenaga Kependidikan" {{ old("jabatan") == "Tenaga Kependidikan" ? "selected" : "" }}>Tenaga Kependidikan</option>
                                </select>
                                @error("jabatan")
                                    <div class="invalid-feedback">{{ $message }}</div>
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
                                <label for="role" class="form-label">Role <span
                                        class="text-danger">*</span></label>
                                <select name="role" id="role" class="form-control">
                                    <option value="">Pilih Role</option>
                                    <option value="admin" {{ old("role") == "admin" ? "selected" : "" }}>Admin</option>
                                    <option value="petugas" {{ old("role") == "petugas" ? "selected" : "" }}>Petugas</option>
                                    <option value="wali_kelas" {{ old("role") == "wali_kelas" ? "selected" : "" }}>Wali Kelas</option>
                                </select>
                                @error("role")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span
                                        class="text-danger">*</span></label>
                                <input type="email" class="form-control @error("email") is-invalid @enderror"
                                    id="email" name="email" value="{{ old("email") }}">
                                @error("email")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route("guru-pegawai.index") }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
