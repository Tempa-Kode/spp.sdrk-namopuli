@extends("template")
@section("title", "Profil")
@section("page", "Profil")
@section("body")
    <div class="col-12">
        @if (session("success"))
            <div class="alert alert-success alert-dismissible fade show text-white" role="alert">
                {{ session("success") }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Data Profil</h6>
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Siswa</label>
                        <input type="text" class="form-control" id="nama"
                            name="nama" value="{{ $data->nama_siswa }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="kelas" class="form-label">Kelas</label>
                        <input type="text" class="form-control" id="kelas"
                            name="kelas" value="{{ $data->kelas->nama_kelas }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio"
                                    name="jenkel" id="laki_laki" value="L"
                                    {{ old("jenkel", $data->jenkel ?? "") == "L" ? "checked" : "" }} disabled>
                                <label class="form-check-label" for="laki_laki">
                                    Laki-laki
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio"
                                    name="jenkel" id="perempuan" value="P"
                                    {{ old("jenkel", $data->jenkel ?? "") == "P" ? "checked" : "" }} disabled>
                                <label class="form-check-label" for="perempuan">
                                    Perempuan
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                        <input type="text" class="form-control"
                            id="tempat_lahir" name="tempat_lahir" value="{{ old("tempat_lahir", $data->tempat_lahir) }}"
                            readonly>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control"
                            id="tanggal_lahir" name="tanggal_lahir"
                            value="{{ old("tanggal_lahir", $data->tanggal_lahir) }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="agama" class="form-label">Agama</label>
                        <input type="text" class="form-control" id="agama"
                            name="agama" value="{{ old("agama", $data->agama) }}" readonly>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Ubah Password</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('profil.siswa.update.password') }}" method="post">
                    @csrf
                    @method("PUT")

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password Lama</label>
                        <input type="password" class="form-control @error("current_password") is-invalid @enderror"
                            id="current_password" name="current_password" required>
                        @error("current_password")
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control @error("password") is-invalid @enderror" id="password"
                            name="password" required minlength="8">
                        @error("password")
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="password_confirmation"
                            name="password_confirmation" required minlength="8">
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Update Password</button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
