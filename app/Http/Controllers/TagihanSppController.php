<?php

namespace App\Http\Controllers;

use App\Models\TagihanSpp;
use App\Models\TarifSPP;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TagihanSppController extends Controller
{
    public function index(Request $request)
    {
        $query = TagihanSpp::with(['siswa.kelas', 'tarif']);

        // Filter berdasarkan bulan
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan kelas
        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }

        $tagihanSpp = $query->orderBy('bulan', 'desc')->orderBy('created_at', 'desc')->paginate(15);

        // Data untuk filter
        $bulanList = TagihanSpp::distinct()->orderBy('bulan', 'desc')->pluck('bulan');
        $kelasList = \App\Models\Kelas::all();

        // Cek apakah bulan ini sudah ada tagihan
        $currentMonth = Carbon::now()->format('Y-m');
        $currentMonthTagihan = TagihanSpp::where('bulan', $currentMonth)->exists();

        return view('tagihan-spp.index', compact('tagihanSpp', 'bulanList', 'kelasList', 'currentMonthTagihan', 'currentMonth'));
    }

    /**
     * Generate tagihan untuk bulan tertentu
     */
    public function generateTagihan(Request $request)
    {
        $validated = $request->validate([
            'bulan' => 'required|date_format:Y-m',
        ]);

        $bulan = $validated['bulan'];

        // Cek apakah sudah ada tagihan untuk bulan ini
        $existingTagihan = TagihanSpp::where('bulan', $bulan)->exists();

        if ($existingTagihan) {
            return back()->with('error', 'Tagihan untuk bulan ' . Carbon::createFromFormat('Y-m', $bulan)->format('F Y') . ' sudah ada.');
        }

        try {
            $tahun = Carbon::createFromFormat('Y-m', $bulan)->year;
            $siswaList = Siswa::with('kelas')->get();
            $tagihanCreated = 0;

            foreach ($siswaList as $siswa) {
                // Cari tarif berdasarkan tahun dan tingkat kelas siswa
                $tarif = TarifSPP::where('tahun', $tahun)
                                 ->where('tingkat_kelas', $siswa->kelas->tingkat_kelas ?? 0)
                                 ->first();

                if ($tarif) {
                    TagihanSpp::create([
                        'siswa_id' => $siswa->id,
                        'tarif_id' => $tarif->id,
                        'bulan' => $bulan,
                        'status' => 'belum_bayar',
                    ]);
                    $tagihanCreated++;
                }
            }

            if ($tagihanCreated > 0) {
                return back()->with('success', "Berhasil membuat {$tagihanCreated} tagihan SPP untuk bulan " . Carbon::createFromFormat('Y-m', $bulan)->format('F Y') . ".");
            } else {
                return back()->with('error', 'Tidak ada tagihan yang dibuat. Pastikan ada siswa aktif dan tarif SPP untuk tahun ini.');
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat tagihan SPP: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $siswaList = Siswa::with('kelas')->get();
        $tarifList = TarifSPP::orderBy('tahun', 'desc')->get();

        return view('tagihan-spp.create', compact('siswaList', 'tarifList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'tarif_id' => 'required|exists:tarif_spp,id',
            'bulan' => 'required|date_format:Y-m',
        ]);

        // Cek apakah siswa sudah punya tagihan untuk bulan tersebut
        $exists = TagihanSpp::where('siswa_id', $validated['siswa_id'])
                           ->where('bulan', $validated['bulan'])
                           ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Siswa sudah memiliki tagihan untuk bulan tersebut.');
        }

        try {
            TagihanSpp::create($validated);
            return redirect()->route('tagihan-spp.index')->with('success', 'Tagihan SPP berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan tagihan SPP.');
        }
    }

    public function edit(TagihanSpp $tagihanSpp)
    {
        $siswaList = Siswa::with('kelas')->get();
        $tarifList = TarifSPP::orderBy('tahun', 'desc')->get();

        return view('tagihan-spp.edit', compact('tagihanSpp', 'siswaList', 'tarifList'));
    }
    public function update(Request $request, TagihanSpp $tagihanSpp)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tarif_id' => 'required|exists:tarif_spp,id',
            'bulan' => 'required|date_format:Y-m',
            'status' => 'required|in:belum_bayar,lunas',
        ]);

        // Cek apakah siswa sudah punya tagihan untuk bulan tersebut (kecuali record saat ini)
        $exists = TagihanSpp::where('siswa_id', $validated['siswa_id'])
                           ->where('bulan', $validated['bulan'])
                           ->where('id', '!=', $tagihanSpp->id)
                           ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Siswa sudah memiliki tagihan untuk bulan tersebut.');
        }

        try {
            $tagihanSpp->update($validated);
            return redirect()->route('tagihan-spp.index')->with('success', 'Tagihan SPP berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui tagihan SPP.');
        }
    }

    public function destroy(TagihanSpp $tagihanSpp)
    {
        try {
            // Cek apakah tagihan sudah memiliki transaksi
            if ($tagihanSpp->transaksi) {
                return back()->with('error', 'Tidak dapat menghapus tagihan yang sudah memiliki transaksi pembayaran.');
            }

            $tagihanSpp->delete();
            return redirect()->route('tagihan-spp.index')->with('success', 'Tagihan SPP berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus tagihan SPP.');
        }
    }
}
