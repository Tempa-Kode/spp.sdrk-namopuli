<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\TagihanSpp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class WaliController extends Controller
{
    public function profil()
    {
        $siswa = Auth::user()->siswa;
        $data = Siswa::where('id', $siswa->id)->with('kelas')->first();
        return view('profile-siswa', compact('data'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'Password lama harus diisi.',
            'password.required' => 'Password baru harus diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password baru harus terdiri dari minimal 8 karakter.',
        ]);

        $user = Auth::user();

        // Cek apakah password lama benar
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profil.siswa')->with('success', 'Password berhasil diperbarui!');
    }

    public function tagihan()
    {
        $tagihan = TagihanSpp::where('siswa_id', Auth::user()->siswa->id)
            ->with('siswa', 'tarif')
            ->orderBy('bulan', 'desc')
            ->get();
        return view('tagihan-spp.wali.index', compact('tagihan'));
    }
}
