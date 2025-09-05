@extends("template")
@section("title", "Edit Tagihan SPP")
@section("page", "Edit Tagihan SPP")
@section("body")
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Edit Tagihan SPP</h6>
                <a href="{{ route("tagihan-spp.index") }}" class="btn btn-secondary btn-sm">
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

                <form action="{{ route("tagihan-spp.update", $tagihanSpp->id) }}" method="POST">
                    @csrf
                    @method("PUT")
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="siswa_id" class="form-label">Siswa <span class="text-danger">*</span></label>
                                <select class="form-select select2-single @error("siswa_id") is-invalid @enderror" id="siswa_id"
                                    name="siswa_id" required>
                                    <option value="">Pilih Siswa</option>
                                    @foreach ($siswaList as $siswa)
                                        <option value="{{ $siswa->id }}"
                                            {{ old("siswa_id", $tagihanSpp->siswa_id) == $siswa->id ? "selected" : "" }}>
                                            [{{ $siswa->nisn }}] {{ $siswa->nama_siswa }} - Kelas {{ $siswa->kelas->tingkat_kelas ?? "No Set" }}
                                        </option>
                                    @endforeach
                                </select>
                                @error("siswa_id")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tarif_id" class="form-label">Tarif SPP <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error("tarif_id") is-invalid @enderror" id="tarif_id"
                                    name="tarif_id" required>
                                    <option value="">Pilih Tarif SPP</option>
                                    @foreach ($tarifList as $tarif)
                                        <option value="{{ $tarif->id }}"
                                            {{ old("tarif_id", $tagihanSpp->tarif_id) == $tarif->id ? "selected" : "" }}>
                                            {{ $tarif->tahun }} - Kelas {{ $tarif->tingkat_kelas }} - Rp
                                            {{ number_format($tarif->nominal, 0, ",", ".") }}
                                        </option>
                                    @endforeach
                                </select>
                                @error("tarif_id")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="bulan" class="form-label">Bulan <span class="text-danger">*</span></label>
                                <input type="month" class="form-control @error("bulan") is-invalid @enderror"
                                    id="bulan" name="bulan" value="{{ old("bulan", $tagihanSpp->bulan) }}" required>
                                @error("bulan")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error("status") is-invalid @enderror" id="status"
                                    name="status" required>
                                    <option value="">Pilih Status</option>
                                    <option value="belum_bayar"
                                        {{ old("status", $tagihanSpp->status) == "belum_bayar" ? "selected" : "" }}>Belum
                                        Bayar</option>
                                    <option value="lunas"
                                        {{ old("status", $tagihanSpp->status) == "lunas" ? "selected" : "" }}>Lunas
                                    </option>
                                </select>
                                @error("status")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nominal Tagihan</label>
                                <div class="form-control bg-light">
                                    Rp {{ number_format($tagihanSpp->tarif->nominal ?? 0, 0, ",", ".") }}
                                </div>
                                <div class="form-text">Nominal berdasarkan tarif SPP yang dipilih.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kode Tagihan</label>
                                <div class="form-control bg-light">
                                    {{ $tagihanSpp->kode_tagihan }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Catatan:</strong> Perubahan data tagihan akan mempengaruhi laporan dan transaksi terkait.
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route("tagihan-spp.index") }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Perbarui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
