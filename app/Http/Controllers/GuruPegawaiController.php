<?php

namespace App\Http\Controllers;

use App\Models\GuruPegawai;
use Illuminate\Http\Request;

class GuruPegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = GuruPegawai::orderBy('nama');

        // Filter berdasarkan jabatan
        if ($request->filled('jabatan') && $request->jabatan != '') {
            $query->where('jabatan', $request->jabatan);
        }

        // Filter berdasarkan jenis kelamin
        if ($request->filled('jenkel') && $request->jenkel != '') {
            $query->where('jenkel', $request->jenkel);
        }

        $data = $query->get();
        $jabatanList = GuruPegawai::select('jabatan')->distinct()->orderBy('jabatan')->pluck('jabatan');

        return view('guru-pegawai.index', compact('data', 'jabatanList'));
    }

    public function create()
    {
        return view('guru-pegawai.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nuptk' => 'unique:guru_pegawai,nuptk',
            'jenkel' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jabatan' => 'required|string|max:255',
            'role' => 'required|in:admin,petugas,wali_kelas',
            'email' => 'required|email|max:255|unique:pengguna,email'
        ]);

        $guruPegawai = GuruPegawai::create([
            'nama' => $request->nama,
            'nuptk' => $request->nuptk,
            'jenkel' => $request->jenkel,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jabatan' => $request->jabatan,
        ]);

        $guruPegawai->user()->create([
            'petugas_id' => $guruPegawai->id,
            'nama' => $guruPegawai->nama,
            'email' => $request->email,
            'password' => $request->nuptk ? bcrypt($request->nuptk) : bcrypt('password123'),
            'role' => $request->role
        ]);

        return redirect()->route('guru-pegawai.index')->with('success', 'Data guru/pegawai berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $guruPegawai = GuruPegawai::findOrFail($id);
        return view('guru-pegawai.edit', compact('guruPegawai'));
    }

    public function update(Request $request, $id)
    {
        $guruPegawai = GuruPegawai::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nuptk' => 'unique:guru_pegawai,nuptk,' . $id,
            'jenkel' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jabatan' => 'required|string|max:255',
            'role' => 'required|in:admin,petugas,wali_kelas'
        ]);

        $guruPegawai->update([
            'nama' => $request->nama,
            'nuptk' => $request->nuptk,
            'jenkel' => $request->jenkel,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jabatan' => $request->jabatan,
        ]);

        $guruPegawai->user->update([
            'email' => $request->email,
            'role' => $request->role
        ]);

        return redirect()->route('guru-pegawai.index')->with('success', 'Data guru/pegawai berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $guruPegawai = GuruPegawai::findOrFail($id);
        $nama = $guruPegawai->nama;

        $guruPegawai->delete();

        return redirect()->route('guru-pegawai.index')->with('success', "Data guru/pegawai {$nama} berhasil dihapus!");
    }
}
