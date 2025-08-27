@extends("template")
@section("title", "Edit Tarif SPP")
@section("page", "Edit Tarif SPP")
@section("body")
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Edit Data Tarif SPP</h6>
                <a href="{{ route("tarif-spp.index") }}" class="btn btn-secondary btn-sm">
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

                <form action="{{ route("tarif-spp.update", $tarifSpp->id) }}" method="POST">
                    @csrf
                    @method("PUT")
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tahun" class="form-label">Tahun <span class="text-danger">*</span></label>
                                <select class="form-select @error("tahun") is-invalid @enderror" id="tahun"
                                    name="tahun" required>
                                    <option value="">Pilih Tahun</option>
                                    @for ($i = 2020; $i <= date("Y") + 5; $i++)
                                        <option value="{{ $i }}"
                                            {{ old("tahun", $tarifSpp->tahun) == $i ? "selected" : "" }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                                @error("tahun")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tingkat_kelas" class="form-label">Tingkat Kelas <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error("tingkat_kelas") is-invalid @enderror" id="tingkat_kelas"
                                    name="tingkat_kelas" required>
                                    <option value="">Pilih Tingkat Kelas</option>
                                    @for ($i = 1; $i <= 6; $i++)
                                        <option value="{{ $i }}"
                                            {{ old("tingkat_kelas", $tarifSpp->tingkat_kelas) == $i ? "selected" : "" }}>
                                            Kelas {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                                @error("tingkat_kelas")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nominal" class="form-label">Nominal SPP <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error("nominal") is-invalid @enderror"
                                        id="nominal" name="nominal" value="{{ old("nominal", $tarifSpp->nominal) }}"
                                        min="0" step="1000" placeholder="Contoh: 150000" required>
                                </div>
                                @error("nominal")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Masukkan nominal dalam rupiah (tanpa titik atau koma).</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Jumlah Tagihan Terkait</label>
                                <div class="form-control bg-light">
                                    {{ $tarifSpp->tagihanSpp->count() }} tagihan
                                </div>
                                <div class="form-text">Jumlah tagihan SPP yang menggunakan tarif ini.</div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning text-white">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Perhatian:</strong> Perubahan nominal tarif akan mempengaruhi tagihan yang belum dibayar.
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route("tarif-spp.index") }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Perbarui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
