<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\GuruPegawai;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Kelas::with('waliKelas');

        // Filter berdasarkan tingkat kelas
        if ($request->filled('tingkat_kelas')) {
            $query->where('tingkat_kelas', $request->tingkat_kelas);
        }

        // Filter berdasarkan wali kelas
        if ($request->filled('wali_kelas_id')) {
            $query->where('wali_kelas_id', $request->wali_kelas_id);
        }

        $kelas = $query->paginate(10);
        $guruPegawai = GuruPegawai::all();

        return view('kelas.index', compact('kelas', 'guruPegawai'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $guruPegawai = GuruPegawai::all();
        return view('kelas.create', compact('guruPegawai'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tingkat_kelas' => 'required|integer|min:1|max:12',
            'wali_kelas_id' => 'nullable|exists:guru_pegawai,id',
        ]);

        try {
            Kelas::create($validated);
            return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan data kelas.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Kelas $kelas)
    {
        $kelas->load('waliKelas', 'siswa');
        return view('kelas.show', compact('kelas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelas $kelas)
    {
        $guruPegawai = GuruPegawai::all();
        return view('kelas.edit', compact('kelas', 'guruPegawai'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'tingkat_kelas' => 'required|integer|min:1|max:12',
            'wali_kelas_id' => 'nullable|exists:guru_pegawai,id',
        ]);

        try {
            $kelas->update($validated);
            return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui data kelas.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kelas $kelas)
    {
        try {
            // Cek apakah kelas masih memiliki siswa
            if ($kelas->siswa()->count() > 0) {
                return back()->with('error', 'Tidak dapat menghapus kelas yang masih memiliki siswa.');
            }

            $kelas->delete();
            return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data kelas.');
        }
    }
}
