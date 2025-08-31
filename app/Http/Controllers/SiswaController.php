<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with('kelas')->orderBy('nama_siswa');

        // Filter berdasarkan kelas
        if ($request->filled('kelas_id') && $request->kelas_id != '') {
            $query->where('kelas_id', $request->kelas_id);
        }

        $data = $query->get();
        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view('siswa.index', compact('data', 'kelas'));
    }

    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('siswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'nisn' => 'required|string|unique:siswa,nisn',
            'kelas_id' => 'required|exists:kelas,id',
            'jenkel' => 'required|in:L,P',
            'nomor_telp_orangtua' => 'nullable|string|max:15',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string|max:50',
        ]);

        Siswa::create([
            'nama_siswa' => $request->nama_siswa,
            'nisn' => $request->nisn,
            'kelas_id' => $request->kelas_id,
            'jenkel' => $request->jenkel,
            'nomor_telp_orangtua' => $request->nomor_telp_orangtua,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'agama' => $request->agama,
        ]);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'nisn' => 'required|string|max:20|unique:siswa,nisn,' . $id,
            'kelas_id' => 'required|exists:kelas,id',
            'jenkel' => 'required|in:L,P',
            'nomor_telp_orangtua' => 'nullable|string|max:15',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string|max:50',
        ]);

        $siswa->update([
            'nama_siswa' => $request->nama_siswa,
            'nisn' => $request->nisn,
            'kelas_id' => $request->kelas_id,
            'jenkel' => $request->jenkel,
            'nomor_telp_orangtua' => $request->nomor_telp_orangtua,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'agama' => $request->agama,
        ]);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $nama = $siswa->nama_siswa;

        $siswa->delete();

        return redirect()->route('siswa.index')->with('success', "Data siswa {$nama} berhasil dihapus!");
    }
}
