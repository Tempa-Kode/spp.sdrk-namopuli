<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\GuruPegawai;
use App\Models\Kelas;
use App\Models\TagihanSpp;
use App\Models\TarifSPP;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = Carbon::now()->format('Y-m');

        // Statistik utama
        $totalSiswa = Siswa::count();
        $totalGuruPegawai = GuruPegawai::count();
        $totalKelas = Kelas::count();

        // Statistik transaksi bulan ini
        $transaksiLunasBulanIni = TagihanSpp::where('bulan', $currentMonth)
                                            ->where('status', 'lunas')
                                            ->count();

        $tagihanBelumBayarBulanIni = TagihanSpp::where('bulan', $currentMonth)
                                               ->where('status', 'belum_bayar')
                                               ->count();

        // Total nominal transaksi lunas bulan ini
        $totalNominalBulanIni = TagihanSpp::where('bulan', $currentMonth)
                                          ->where('status', 'lunas')
                                          ->with('tarif')
                                          ->get()
                                          ->sum(function($tagihan) {
                                              return $tagihan->tarif->nominal ?? 0;
                                          });

        // Transaksi terbaru (20 data terakhir yang lunas)
        $transaksiTerbaru = TagihanSpp::with(['siswa.kelas', 'tarif'])
                                      ->where('status', 'lunas')
                                      ->orderBy('updated_at', 'desc')
                                      ->limit(20)
                                      ->get();

        // Data untuk chart - tagihan per bulan (6 bulan terakhir)
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');
            $monthName = Carbon::now()->subMonths($i)->format('M Y');

            $lunas = TagihanSpp::where('bulan', $month)->where('status', 'lunas')->count();
            $belumBayar = TagihanSpp::where('bulan', $month)->where('status', 'belum_bayar')->count();

            $chartData[] = [
                'month' => $monthName,
                'lunas' => $lunas,
                'belum_bayar' => $belumBayar
            ];
        }

        return view('dashboard-stackholder', compact(
            'totalSiswa',
            'totalGuruPegawai',
            'totalKelas',
            'transaksiLunasBulanIni',
            'tagihanBelumBayarBulanIni',
            'totalNominalBulanIni',
            'transaksiTerbaru',
            'chartData',
            'currentMonth'
        ));
    }

    public function wali()
    {
        return view('dashboard-wali');
    }
}
