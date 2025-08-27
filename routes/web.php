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
