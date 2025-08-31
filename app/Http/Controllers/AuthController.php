<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'kridensial' => ['required'],
            'password' => ['required'],
        ], [
            'kridensial.required' => 'NISN/NUPTK wajib diisi.',
            'password.required' => 'password wajib diisi.',
        ]);

        $user = User::whereHas('siswa', function($query) use ($credentials) {
            $query->where('nisn', $credentials['kridensial']);
        })->orWhereHas('petugas', function($query) use ($credentials) {
            $query->where('nuptk', $credentials['kridensial']);
        })->first();

        // jika user tidak ditemukan menggunakan NISN/NUPTK, maka menggunakan email
        if (!$user && filter_var($credentials['kridensial'], FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $credentials['kridensial'])->first();
        }

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            $data = Auth::user();
            $request->session()->regenerate();
            switch ($data->role) {
                case 'admin':
                    return redirect()->route('dashboard.stackholder');
                case 'petugas':
                    return redirect()->route('dashboard.stackholder');
                case 'wali':
                    return redirect()->route('dashboard.wali');
                default:
                    Auth::logout();
                    return back()->with('login_error', 'pengguna tidak dikenali.');
            }
        }

        return back()->with('login_error', 'Login Gagal! Periksa kembali kridensial anda.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
