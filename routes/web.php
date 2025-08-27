<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
})->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'authenticate'])->name('login.post');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::prefix('/profil')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\ProfilController::class, 'index'])->name('profil');
    Route::put('/update-profile', [App\Http\Controllers\ProfilController::class, 'updateProfile'])->name('profil.update');
    Route::put('/update-password', [App\Http\Controllers\ProfilController::class, 'updatePassword'])->name('profil.update.password');
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
