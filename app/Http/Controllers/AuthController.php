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

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();
            switch ($user->role) {
                case 'admin':
                    return redirect()->intended('/dashboard');
                case 'petugas':
                    return redirect()->intended('/dashboard');
                case 'wali':
                    return redirect()->intended('/');
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

        return redirect('/login');
    }
}
