@extends('template')
@section('title', 'Edit Kelas')
@section('page', 'Edit Kelas')
@section('body')
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Edit Data Kelas</h6>
                <a href="{{ route('kelas.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('kelas.update', $kelas->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tingkat_kelas" class="form-label">Tingkat Kelas <span class="text-danger">*</span></label>
                                <select class="form-select @error('tingkat_kelas') is-invalid @enderror"
                                        id="tingkat_kelas" name="tingkat_kelas" required>
                                    <option value="">Pilih Tingkat Kelas</option>
                                    @for($i = 1; $i <= 6; $i++)
                                        <option value="{{ $i }}" {{ old('tingkat_kelas', $kelas->tingkat_kelas) == $i ? 'selected' : '' }}>
                                            Kelas {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                                @error('tingkat_kelas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="wali_kelas_id" class="form-label">Wali Kelas</label>
                                <select class="form-select @error('wali_kelas_id') is-invalid @enderror"
                                        id="wali_kelas_id" name="wali_kelas_id">
                                    <option value="">Pilih Wali Kelas (Opsional)</option>
                                    @foreach($guruPegawai as $guru)
                                        <option value="{{ $guru->id }}" {{ old('wali_kelas_id', $kelas->wali_kelas_id) == $guru->id ? 'selected' : '' }}>
                                            {{ $guru->nama }} - {{ $guru->jabatan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('wali_kelas_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Wali kelas dapat diisi kemudian jika belum ditentukan.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Jumlah Siswa Saat Ini</label>
                                <div class="form-control bg-light">
                                    {{ $kelas->siswa->count() }} siswa
                                </div>
                                <div class="form-text">Jumlah siswa yang terdaftar di kelas ini.</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('kelas.index') }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Perbarui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
