<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.index');
});

Route::get('/login', function () {
    return view('login');
})->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'authenticate'])->name('login.post');
Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->middleware('auth')->name('dashboard.stackholder');
Route::get('/dashboard/wali', [App\Http\Controllers\DashboardController::class, 'wali'])->middleware('auth')->name('dashboard.wali');
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::prefix('/profil')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\ProfilController::class, 'index'])->name('profil');
    Route::put('/update-profile', [App\Http\Controllers\ProfilController::class, 'updateProfile'])->name('profil.update');
    Route::put('/update-password', [App\Http\Controllers\ProfilController::class, 'updatePassword'])->name('profil.update.password');
    Route::get('/siswa', [App\Http\Controllers\WaliController::class, 'profil'])->name('profil.siswa');
    Route::put('/siswa/update-password', [App\Http\Controllers\WaliController::class, 'updatePassword'])->name('profil.siswa.update.password');
});

Route::prefix('/siswa')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\SiswaController::class, 'index'])->name('siswa.index');
    Route::get('/create', [App\Http\Controllers\SiswaController::class, 'create'])->name('siswa.create');
    Route::post('/', [App\Http\Controllers\SiswaController::class, 'store'])->name('siswa.store');
    Route::get('/{id}/edit', [App\Http\Controllers\SiswaController::class, 'edit'])->name('siswa.edit');
    Route::put('/{id}', [App\Http\Controllers\SiswaController::class, 'update'])->name('siswa.update');
    Route::delete('/{id}', [App\Http\Controllers\SiswaController::class, 'destroy'])->name('siswa.destroy');
});

Route::prefix('/guru-pegawai')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\GuruPegawaiController::class, 'index'])->name('guru-pegawai.index');
    Route::get('/create', [App\Http\Controllers\GuruPegawaiController::class, 'create'])->name('guru-pegawai.create');
    Route::post('/', [App\Http\Controllers\GuruPegawaiController::class, 'store'])->name('guru-pegawai.store');
    Route::get('/{id}/edit', [App\Http\Controllers\GuruPegawaiController::class, 'edit'])->name('guru-pegawai.edit');
    Route::put('/{id}', [App\Http\Controllers\GuruPegawaiController::class, 'update'])->name('guru-pegawai.update');
    Route::delete('/{id}', [App\Http\Controllers\GuruPegawaiController::class, 'destroy'])->name('guru-pegawai.destroy');
});

Route::prefix('/kelas')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\KelasController::class, 'index'])->name('kelas.index');
    Route::get('/create', [App\Http\Controllers\KelasController::class, 'create'])->name('kelas.create');
    Route::post('/', [App\Http\Controllers\KelasController::class, 'store'])->name('kelas.store');
    Route::get('/{kelas}/edit', [App\Http\Controllers\KelasController::class, 'edit'])->name('kelas.edit');
    Route::put('/{kelas}', [App\Http\Controllers\KelasController::class, 'update'])->name('kelas.update');
    Route::delete('/{kelas}', [App\Http\Controllers\KelasController::class, 'destroy'])->name('kelas.destroy');
});

Route::prefix('/tarif-spp')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\TarifSppController::class, 'index'])->name('tarif-spp.index');
    Route::get('/create', [App\Http\Controllers\TarifSppController::class, 'create'])->name('tarif-spp.create');
    Route::post('/', [App\Http\Controllers\TarifSppController::class, 'store'])->name('tarif-spp.store');
    Route::get('/{tarifSpp}/edit', [App\Http\Controllers\TarifSppController::class, 'edit'])->name('tarif-spp.edit');
    Route::put('/{tarifSpp}', [App\Http\Controllers\TarifSppController::class, 'update'])->name('tarif-spp.update');
    Route::delete('/{tarifSpp}', [App\Http\Controllers\TarifSppController::class, 'destroy'])->name('tarif-spp.destroy');
});

Route::prefix('/tagihan-spp')->middleware('auth')->group(function () {
    Route::get('/wali', [App\Http\Controllers\WaliController::class, 'tagihan'])->name('tagihan-spp.wali');
    Route::post('/bayar', [App\Http\Controllers\PembayaranController::class, 'bayar'])->name('tagihan-spp.bayar');
    Route::put('/update-status/', [App\Http\Controllers\PembayaranController::class, 'updateStatus'])->name('tagihan-spp.update-status.pembayaran');
    Route::get('/detail/{id}', [App\Http\Controllers\WaliController::class, 'detailTagihan'])->name('tagihan-spp.wali.detail');
    Route::get('/kuitansi/{id}', [App\Http\Controllers\PembayaranController::class, 'generateKuitansi'])->name('tagihan-spp.kuitansi');
    Route::get('/', [App\Http\Controllers\TagihanSppController::class, 'index'])->name('tagihan-spp.index');
    Route::post('/generate', [App\Http\Controllers\TagihanSppController::class, 'generateTagihan'])->name('tagihan-spp.generate');
    Route::get('/create', [App\Http\Controllers\TagihanSppController::class, 'create'])->name('tagihan-spp.create');
    Route::post('/', [App\Http\Controllers\TagihanSppController::class, 'store'])->name('tagihan-spp.store');
    Route::get('/{tagihanSpp}', [App\Http\Controllers\TagihanSppController::class, 'show'])->name('tagihan-spp.show');
    Route::get('/{tagihanSpp}/edit', [App\Http\Controllers\TagihanSppController::class, 'edit'])->name('tagihan-spp.edit');
    Route::put('/{tagihanSpp}', [App\Http\Controllers\TagihanSppController::class, 'update'])->name('tagihan-spp.update');
    Route::patch('/{tagihanSpp}/status', [App\Http\Controllers\TagihanSppController::class, 'updateStatus'])->name('tagihan-spp.update-status');
    Route::delete('/{tagihanSpp}', [App\Http\Controllers\TagihanSppController::class, 'destroy'])->name('tagihan-spp.destroy');

});

Route::prefix('/reports')->middleware('auth')->group(function () {
    Route::get('/tagihan-spp', [App\Http\Controllers\ReportController::class, 'tagihanSpp'])->name('reports.tagihan-spp');
    Route::get('/tagihan-spp/preview', [App\Http\Controllers\ReportController::class, 'previewTagihanSpp'])->name('reports.tagihan-spp.preview');
    Route::get('/tagihan-spp/pdf', [App\Http\Controllers\ReportController::class, 'generateTagihanSppPdf'])->name('reports.tagihan-spp.pdf');
});
