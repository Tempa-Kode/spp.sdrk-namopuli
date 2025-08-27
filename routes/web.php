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
