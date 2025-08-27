<?php

namespace App\Http\Controllers;

use App\Models\TarifSPP;
use Illuminate\Http\Request;

class TarifSppController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TarifSPP::query();

        // Filter berdasarkan tahun
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Filter berdasarkan tingkat kelas
        if ($request->filled('tingkat_kelas')) {
            $query->where('tingkat_kelas', $request->tingkat_kelas);
        }

        $tarifSpp = $query->orderBy('tahun', 'desc')->orderBy('tingkat_kelas', 'asc')->paginate(10);

        // Ambil data tahun untuk filter
        $tahunList = TarifSPP::distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        return view('tarif-spp.index', compact('tarifSpp', 'tahunList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tarif-spp.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'required|integer|min:2020|max:2050',
            'tingkat_kelas' => 'required|integer|min:1|max:12',
            'nominal' => 'required|numeric|min:0',
        ]);

        // Cek apakah sudah ada tarif untuk tahun dan tingkat yang sama
        $exists = TarifSPP::where('tahun', $validated['tahun'])
                          ->where('tingkat_kelas', $validated['tingkat_kelas'])
                          ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Tarif SPP untuk tahun ' . $validated['tahun'] . ' tingkat kelas ' . $validated['tingkat_kelas'] . ' sudah ada.');
        }

        try {
            TarifSPP::create($validated);
            return redirect()->route('tarif-spp.index')->with('success', 'Tarif SPP berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan tarif SPP.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TarifSPP $tarifSpp)
    {
        $tarifSpp->load('tagihanSpp');
        return view('tarif-spp.show', compact('tarifSpp'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TarifSPP $tarifSpp)
    {
        return view('tarif-spp.edit', compact('tarifSpp'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TarifSPP $tarifSpp)
    {
        $validated = $request->validate([
            'tahun' => 'required|integer|min:2020|max:2050',
            'tingkat_kelas' => 'required|integer|min:1|max:12',
            'nominal' => 'required|numeric|min:0',
        ]);

        // Cek apakah sudah ada tarif untuk tahun dan tingkat yang sama (kecuali record saat ini)
        $exists = TarifSPP::where('tahun', $validated['tahun'])
                          ->where('tingkat_kelas', $validated['tingkat_kelas'])
                          ->where('id', '!=', $tarifSpp->id)
                          ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Tarif SPP untuk tahun ' . $validated['tahun'] . ' tingkat kelas ' . $validated['tingkat_kelas'] . ' sudah ada.');
        }

        try {
            $tarifSpp->update($validated);
            return redirect()->route('tarif-spp.index')->with('success', 'Tarif SPP berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui tarif SPP.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TarifSPP $tarifSpp)
    {
        try {
            // Cek apakah tarif masih digunakan di tagihan
            if ($tarifSpp->tagihanSpp()->count() > 0) {
                return back()->with('error', 'Tidak dapat menghapus tarif SPP yang masih digunakan dalam tagihan.');
            }

            $tarifSpp->delete();
            return redirect()->route('tarif-spp.index')->with('success', 'Tarif SPP berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus tarif SPP.');
        }
    }
}
