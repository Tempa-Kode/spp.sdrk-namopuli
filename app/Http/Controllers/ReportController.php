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
        $kelasList = Kelas::orderBy('tingkat_kelas')->orderBy('nama_kelas')->get();
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
            'kelas' => $request->kelas_id ? Kelas::find($request->kelas_id)->nama_kelas : 'Semua Kelas',
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
                        ->paginate(20);

        $kelasList = Kelas::orderBy('tingkat_kelas')->orderBy('nama_kelas')->get();
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
}
