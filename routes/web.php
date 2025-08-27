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
