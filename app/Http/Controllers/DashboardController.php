<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\GuruPegawai;
use App\Models\Kelas;
use App\Models\TagihanSpp;
use App\Models\TarifSPP;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return redirect()->route('login')->with('error', 'Data siswa tidak ditemukan');
        }

        $currentMonth = Carbon::now()->format('Y-m');
        $currentYear = Carbon::now()->year;

        // Data siswa
        $dataSiswa = $siswa->load('kelas');

        // Tagihan SPP bulan ini
        $tagihanBulanIni = TagihanSpp::with('tarif')
                                    ->where('siswa_id', $siswa->id)
                                    ->where('bulan', $currentMonth)
                                    ->first();

        // Total tagihan yang sudah dibayar tahun ini
        $tagihanLunas = TagihanSpp::with('tarif')
                                  ->where('siswa_id', $siswa->id)
                                  ->where('status', 'lunas')
                                  ->whereYear('created_at', $currentYear)
                                  ->get();

        $totalBayarTahunIni = $tagihanLunas->sum(function($tagihan) {
            return $tagihan->tarif->nominal ?? 0;
        });

        // Tagihan yang belum dibayar
        $tagihanBelumBayar = TagihanSpp::with('tarif')
                                      ->where('siswa_id', $siswa->id)
                                      ->where('status', 'belum_bayar')
                                      ->orderBy('bulan', 'asc')
                                      ->get();

        // Riwayat pembayaran 6 bulan terakhir
        $riwayatPembayaran = TagihanSpp::with('tarif')
                                       ->where('siswa_id', $siswa->id)
                                       ->where('status', 'lunas')
                                       ->orderBy('bulan', 'desc')
                                       ->limit(6)
                                       ->get();

        // Statistik pembayaran per bulan untuk chart (12 bulan terakhir)
        $chartData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');
            $monthName = Carbon::now()->subMonths($i)->format('M Y');

            $tagihan = TagihanSpp::with('tarif')
                                 ->where('siswa_id', $siswa->id)
                                 ->where('bulan', $month)
                                 ->first();

            $status = $tagihan ? $tagihan->status : 'belum_ada';
            $nominal = $tagihan && $tagihan->status == 'lunas' ? ($tagihan->tarif->nominal ?? 0) : 0;

            $chartData[] = [
                'month' => $monthName,
                'status' => $status,
                'nominal' => $nominal
            ];
        }

        return view('dashboard-wali', compact(
            'dataSiswa',
            'tagihanBulanIni',
            'totalBayarTahunIni',
            'tagihanBelumBayar',
            'riwayatPembayaran',
            'chartData',
            'currentMonth'
        ));
    }
}
