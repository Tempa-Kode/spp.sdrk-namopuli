<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Siswa;
use App\Models\TarifSPP;
use App\Models\TagihanSpp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagihanSppController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::user()->role == 'wali_kelas'){
            $query = TagihanSpp::with(['siswa.kelas', 'tarif'])
                               ->whereHas('siswa', function($q) {
                                   $q->where('kelas_id', Auth::user()->petugas->kelas->id);
                               });
        } else {
            $query = TagihanSpp::with(['siswa.kelas', 'tarif']);
        }

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

        $tagihanSpp = $query->orderBy('bulan', 'desc')->orderBy('created_at', 'desc')->get();

        // Data untuk filter
        $bulanList = TagihanSpp::distinct()->orderBy('bulan', 'desc')->pluck('bulan');
        $kelasList = \App\Models\Kelas::all();

        // Cek apakah bulan ini sudah ada tagihan
        $currentMonth = Carbon::now()->format('Y-m');
        $currentMonthTagihan = TagihanSpp::where('bulan', $currentMonth)->exists();

        // check jumlah tagihan saat ini
        $currentMonthTagihanCount = TagihanSpp::where('bulan', $currentMonth)->count();
        $jumlahSiswa = Siswa::whereHas('kelas')->count(); // menghitung jumlah siswa

        return view('tagihan-spp.index', compact('tagihanSpp', 'bulanList', 'kelasList', 'currentMonthTagihan', 'currentMonth', 'currentMonthTagihanCount', 'jumlahSiswa'));
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
        try {
            $tahun = Carbon::createFromFormat('Y-m', $bulan)->year;
            $siswaList = Siswa::with('kelas')->get();
            $tagihanCreated = 0;
            $skippedExisting = 0;

            foreach ($siswaList as $siswa) {
                // Jika siswa sudah punya tagihan untuk bulan ini, lewati
                $already = TagihanSpp::where('siswa_id', $siswa->id)
                                     ->where('bulan', $bulan)
                                     ->exists();

                if ($already) {
                    $skippedExisting++;
                    continue;
                }

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
                $message = "Berhasil membuat {$tagihanCreated} tagihan SPP untuk bulan " . Carbon::createFromFormat('Y-m', $bulan)->format('F Y') . ".";
                if ($skippedExisting > 0) {
                    $message .= " ({$skippedExisting} siswa sudah memiliki tagihan untuk bulan tersebut dan dilewati).";
                }
                return back()->with('success', $message);
            } else {
                if ($skippedExisting > 0) {
                    return back()->with('error', "Tidak ada tagihan yang dibuat. {$skippedExisting} siswa sudah memiliki tagihan untuk bulan tersebut. Pastikan juga ada tarif SPP untuk tahun ini.");
                }
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

    public function cekTagihan(Request $request)
    {
        $nisn = $request->nisn;

        if (!$nisn) {
            return redirect()->route('home.cek-tagihan')->with('error', 'Silakan masukkan NISN terlebih dahulu.');
        }

        $siswa = Siswa::where('nisn', $nisn)->first();
        if (!$siswa) {
            return redirect()->route('home.cek-tagihan')->with('error', 'NISN tidak ditemukan. Silakan periksa kembali.');
        }

        $tagihan = TagihanSpp::with(['siswa', 'tarif'])
                    ->whereHas('siswa', function($q) use ($nisn) {
                        $q->where('nisn', $nisn);
                    })
                    ->where('status', 'belum_bayar')
                    ->latest()
                    ->get();

        return view('home.tagihan-spp', compact('tagihan'));
    }

    public function bayarTagihan($tagihanId)
    {
        $tagihan = TagihanSpp::with(['siswa.kelas', 'tarif'])->find($tagihanId);
        if (!$tagihan) {
            return redirect()->route('home.cek-tagihan')->with('error', 'Tagihan tidak ditemukan.');
        }

        if ($tagihan->status == 'lunas') {
            return redirect()->route('home.cek-tagihan')->with('error', 'Tagihan ini sudah lunas.');
        }

        return view('home.pembayaran', compact('tagihan'));
    }
}
