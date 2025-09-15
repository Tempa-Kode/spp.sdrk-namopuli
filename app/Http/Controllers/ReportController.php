<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Kelas;
use App\Models\TagihanSpp;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Tampilkan halaman report tagihan SPP
     */
    public function tagihanSpp()
    {
        $kelasList = Kelas::orderBy('tingkat_kelas')->orderBy('tingkat_kelas')->get();
        $tahunList = TagihanSpp::selectRaw('YEAR(STR_TO_DATE(CONCAT(bulan, "-01"), "%Y-%m-%d")) as tahun')
                               ->distinct()
                               ->orderBy('tahun', 'desc')
                               ->pluck('tahun');

        return view('reports.tagihan-spp', compact('kelasList', 'tahunList'));
    }

    /**
     * Generate PDF report tagihan SPP
     */
    public function generateTagihanSppPdf(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => 'nullable|exists:kelas,id',
            'bulan' => 'nullable|date_format:Y-m',
            'tahun' => 'nullable|integer|min:2020|max:2050',
            'status' => 'nullable|in:belum_bayar,lunas',
        ]);

        if(Auth::user()->role == 'wali_kelas'){
            $query = TagihanSpp::with(['siswa.kelas', 'tarif'])
                               ->whereHas('siswa', function($q) {
                                   $q->where('kelas_id', Auth::user()->petugas->kelas->id);
                               });
        } else {
            $query = TagihanSpp::with(['siswa.kelas', 'tarif']);
        }

        // Filter berdasarkan kelas
        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }

        // Filter berdasarkan bulan
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }

        // Filter berdasarkan tahun
        if ($request->filled('tahun')) {
            $query->whereRaw('YEAR(STR_TO_DATE(CONCAT(bulan, "-01"), "%Y-%m-%d")) = ?', [$request->tahun]);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tagihan = $query->orderBy('bulan', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->get();

        // Data untuk header report
        $filters = [
            'kelas' => $request->kelas_id ? Kelas::find($request->kelas_id)->tingkat_kelas : 'Semua Kelas',
            'bulan' => $request->bulan ? Carbon::createFromFormat('Y-m', $request->bulan)->format('F Y') : 'Semua Bulan',
            'tahun' => $request->tahun ?: 'Semua Tahun',
            'status' => $request->status ? ucfirst(str_replace('_', ' ', $request->status)) : 'Semua Status',
        ];

        // Statistik
        $totalTagihan = $tagihan->count();
        $totalNominal = $tagihan->sum(function($item) {
            return $item->tarif->nominal ?? 0;
        });
        $tagihanLunas = $tagihan->where('status', 'lunas')->count();
        $tagihanBelumBayar = $tagihan->where('status', 'belum_bayar')->count();

        $stats = [
            'total_tagihan' => $totalTagihan,
            'total_nominal' => $totalNominal,
            'tagihan_lunas' => $tagihanLunas,
            'tagihan_belum_bayar' => $tagihanBelumBayar,
            'persentase_lunas' => $totalTagihan > 0 ? round(($tagihanLunas / $totalTagihan) * 100, 2) : 0,
        ];

        // Generate PDF
        $pdf = Pdf::loadView('reports.pdf.tagihan-spp', compact('tagihan', 'filters', 'stats'));
        $pdf->setPaper('A4', 'landscape');

        $filename = 'laporan-tagihan-spp-' . date('Y-m-d-H-i-s') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Preview report sebelum download PDF
     */
    public function previewTagihanSpp(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => 'nullable|exists:kelas,id',
            'bulan' => 'nullable|date_format:Y-m',
            'tahun' => 'nullable|integer|min:2020|max:2050',
            'status' => 'nullable|in:belum_bayar,lunas',
        ]);

        if(Auth::user()->role == 'wali_kelas'){
            $query = TagihanSpp::with(['siswa.kelas', 'tarif'])
                               ->whereHas('siswa', function($q) {
                                   $q->where('kelas_id', Auth::user()->petugas->kelas->id);
                               });
        } else {
            $query = TagihanSpp::with(['siswa.kelas', 'tarif']);
        }

        // Apply same filters as PDF generation
        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }

        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereRaw('YEAR(STR_TO_DATE(CONCAT(bulan, "-01"), "%Y-%m-%d")) = ?', [$request->tahun]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tagihan = $query->orderBy('bulan', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->get();

        $kelasList = Kelas::orderBy('tingkat_kelas')->orderBy('tingkat_kelas')->get();
        $tahunList = TagihanSpp::selectRaw('YEAR(STR_TO_DATE(CONCAT(bulan, "-01"), "%Y-%m-%d")) as tahun')
                               ->distinct()
                               ->orderBy('tahun', 'desc')
                               ->pluck('tahun');

        // Statistik
        $totalTagihan = $query->count();
        $totalNominal = $query->get()->sum(function($item) {
            return $item->tarif->nominal ?? 0;
        });

        return view('reports.tagihan-spp', compact('tagihan', 'kelasList', 'tahunList', 'totalTagihan', 'totalNominal'));
    }

    /**
     * Tampilkan halaman report tagihan SPP per tahun
     */
    public function tagihanSppTahunan()
    {
        $kelasList = Kelas::orderBy('tingkat_kelas')->get();
        $tahunList = TagihanSpp::selectRaw('YEAR(STR_TO_DATE(CONCAT(bulan, "-01"), "%Y-%m-%d")) as tahun')
                               ->distinct()
                               ->orderBy('tahun', 'desc')
                               ->pluck('tahun');

        return view('reports.tagihan-spp-tahunan', compact('kelasList', 'tahunList'));
    }

    /**
     * Generate PDF report tagihan SPP per tahun
     */
    public function generateTagihanSppTahunanPdf(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => 'nullable|exists:kelas,id',
            'tahun' => 'required|integer|min:2020|max:2050',
        ]);

        if(Auth::user()->role == 'wali_kelas'){
            $query = TagihanSpp::with(['siswa.kelas', 'tarif'])
                               ->whereHas('siswa', function($q) {
                                   $q->where('kelas_id', Auth::user()->petugas->kelas->id);
                               });
        } else {
            $query = TagihanSpp::with(['siswa.kelas', 'tarif']);
        }

        // Filter berdasarkan tahun (wajib)
        $query->whereRaw('YEAR(STR_TO_DATE(CONCAT(bulan, "-01"), "%Y-%m-%d")) = ?', [$request->tahun]);

        // Filter berdasarkan kelas
        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }

        $tagihan = $query->orderBy('bulan', 'asc')->get();

        // Data untuk header report
        $filters = [
            'kelas' => $request->kelas_id ? Kelas::find($request->kelas_id)->tingkat_kelas : 'Semua Kelas',
            'tahun' => $request->tahun,
        ];

        // Statistik per bulan
        $statistikBulanan = [];
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $bulanStr = str_pad($bulan, 2, '0', STR_PAD_LEFT);
            $bulanKey = $request->tahun . '-' . $bulanStr;

            $tagihanBulan = $tagihan->filter(function($item) use ($bulanKey) {
                return $item->bulan === $bulanKey;
            });

            $statistikBulanan[$bulanKey] = [
                'nama_bulan' => Carbon::createFromDate($request->tahun, $bulan, 1)->format('F'),
                'total_tagihan' => $tagihanBulan->count(),
                'total_nominal' => $tagihanBulan->sum(function($item) {
                    return $item->tarif->nominal ?? 0;
                }),
                'tagihan_lunas' => $tagihanBulan->where('status', 'lunas')->count(),
                'tagihan_belum_bayar' => $tagihanBulan->where('status', 'belum_bayar')->count(),
                'nominal_lunas' => $tagihanBulan->where('status', 'lunas')->sum(function($item) {
                    return $item->tarif->nominal ?? 0;
                }),
                'nominal_belum_bayar' => $tagihanBulan->where('status', 'belum_bayar')->sum(function($item) {
                    return $item->tarif->nominal ?? 0;
                }),
            ];
        }

        // Statistik keseluruhan
        $totalTagihan = $tagihan->count();
        $totalNominal = $tagihan->sum(function($item) {
            return $item->tarif->nominal ?? 0;
        });
        $tagihanLunas = $tagihan->where('status', 'lunas')->count();
        $tagihanBelumBayar = $tagihan->where('status', 'belum_bayar')->count();
        $nominalLunas = $tagihan->where('status', 'lunas')->sum(function($item) {
            return $item->tarif->nominal ?? 0;
        });
        $nominalBelumBayar = $tagihan->where('status', 'belum_bayar')->sum(function($item) {
            return $item->tarif->nominal ?? 0;
        });

        $stats = [
            'total_tagihan' => $totalTagihan,
            'total_nominal' => $totalNominal,
            'tagihan_lunas' => $tagihanLunas,
            'tagihan_belum_bayar' => $tagihanBelumBayar,
            'nominal_lunas' => $nominalLunas,
            'nominal_belum_bayar' => $nominalBelumBayar,
            'persentase_lunas' => $totalTagihan > 0 ? round(($tagihanLunas / $totalTagihan) * 100, 2) : 0,
            'persentase_nominal_lunas' => $totalNominal > 0 ? round(($nominalLunas / $totalNominal) * 100, 2) : 0,
        ];

        // Generate PDF
        $pdf = Pdf::loadView('reports.pdf.tagihan-spp-tahunan', compact('tagihan', 'filters', 'stats', 'statistikBulanan'));
        $pdf->setPaper('A4', 'landscape');

        $filename = 'laporan-tagihan-spp-tahunan-' . $request->tahun . '-' . date('Y-m-d-H-i-s') . '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Preview report tahunan sebelum download PDF
     */
    public function previewTagihanSppTahunan(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => 'nullable|exists:kelas,id',
            'tahun' => 'required|integer|min:2020|max:2050',
        ]);

        if(Auth::user()->role == 'wali_kelas'){
            $query = TagihanSpp::with(['siswa.kelas', 'tarif'])
                               ->whereHas('siswa', function($q) {
                                   $q->where('kelas_id', Auth::user()->petugas->kelas->id);
                               });
        } else {
            $query = TagihanSpp::with(['siswa.kelas', 'tarif']);
        }

        // Filter berdasarkan tahun (wajib)
        $query->whereRaw('YEAR(STR_TO_DATE(CONCAT(bulan, "-01"), "%Y-%m-%d")) = ?', [$request->tahun]);

        // Filter berdasarkan kelas
        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }

        $tagihan = $query->orderBy('bulan', 'asc')->get();

        $kelasList = Kelas::orderBy('tingkat_kelas')->get();
        $tahunList = TagihanSpp::selectRaw('YEAR(STR_TO_DATE(CONCAT(bulan, "-01"), "%Y-%m-%d")) as tahun')
                               ->distinct()
                               ->orderBy('tahun', 'desc')
                               ->pluck('tahun');

        // Statistik per bulan untuk preview
        $allTagihan = $tagihan;
        $statistikBulanan = [];
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $bulanStr = str_pad($bulan, 2, '0', STR_PAD_LEFT);
            $bulanKey = $request->tahun . '-' . $bulanStr;

            $tagihanBulan = $allTagihan->filter(function($item) use ($bulanKey) {
                return $item->bulan === $bulanKey;
            });

            if ($tagihanBulan->count() > 0) {
                $statistikBulanan[$bulanKey] = [
                    'nama_bulan' => Carbon::createFromDate($request->tahun, $bulan, 1)->format('F'),
                    'total_tagihan' => $tagihanBulan->count(),
                    'total_nominal' => $tagihanBulan->sum(function($item) {
                        return $item->tarif->nominal ?? 0;
                    }),
                    'tagihan_lunas' => $tagihanBulan->where('status', 'lunas')->count(),
                    'tagihan_belum_bayar' => $tagihanBulan->where('status', 'belum_bayar')->count(),
                ];
            }
        }

        // Statistik keseluruhan
        $totalTagihan = $allTagihan->count();
        $totalNominal = $allTagihan->sum(function($item) {
            return $item->tarif->nominal ?? 0;
        });

        return view('reports.tagihan-spp-tahunan', compact('tagihan', 'kelasList', 'tahunList', 'statistikBulanan', 'totalTagihan', 'totalNominal'));
    }
}
