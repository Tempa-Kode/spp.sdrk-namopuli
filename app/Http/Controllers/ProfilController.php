<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller
{
    public function index()
    {
        $data = Auth::user()->petugas;
        return view('profile', compact('data'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nuptk' => 'required|string|max:20|unique:guru_pegawai,nuptk,' . Auth::user()->petugas->id,
            'jenkel' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jabatan' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $petugas = $user->petugas;

        // Update data guru/pegawai
        $petugas->update([
            'nama' => $request->nama,
            'nuptk' => $request->nuptk,
            'jenkel' => $request->jenkel,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jabatan' => $request->jabatan,
        ]);

        // Update nama di tabel user juga
        $user->update([
            'nama' => $request->nama,
        ]);

        return redirect()->route('profil')->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
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

        return redirect()->route('profil')->with('success', 'Password berhasil diperbarui!');
    }
}
