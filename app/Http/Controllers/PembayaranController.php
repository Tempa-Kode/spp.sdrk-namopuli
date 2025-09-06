<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\TagihanSpp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;

class PembayaranController extends Controller
{
    public function bayar(Request $request)
    {
        $request->validate([
            'tagihan_id' => 'required|exists:tagihan_spp,id',
            'jumlah_bayar' => 'required|numeric|min:1',
        ]);

        $transaksi = Transaksi::where('tagihan_id', $request->tagihan_id)->first();
        if ($transaksi && $transaksi->snap_token) {
            return response()->json([
                'status'     => 'success',
                'snap_token' => $transaksi->snap_token,
            ]);
        } else {
            \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
            \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
            \Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized');
            \Midtrans\Config::$is3ds = config('services.midtrans.is_3ds');

            try {
                $snapToken = null;
                $tagihan = TagihanSpp::with('siswa')->findOrFail($request->tagihan_id);
                DB::transaction(function () use ($request, &$snapToken, $tagihan) {
                    $nisn = $tagihan->siswa->nisn ?? Auth::user()->siswa->nisn;
                    $kodeTransaksi = "TRX-" . $nisn . "-" . now()->timestamp;
                    $transaksi = Transaksi::create([
                        'kd_transaksi' => $kodeTransaksi,
                        'tagihan_id' => $request->tagihan_id,
                        'jumlah_bayar' => $request->jumlah_bayar,
                        'tanggal_bayar' => now(),
                    ]);

                    $params = [
                        'transaction_details' => [
                            'order_id' => $kodeTransaksi,
                            'gross_amount' => $request->jumlah_bayar,
                        ],
                        'customer_details' => [
                            'first_name' => $tagihan->siswa->nama_siswa ?? Auth::user()->siswa->nama_siswa,
                            'last_name' => '',
                            'email' => 'siswa@example.com',
                            'phone' => '08111222333',
                        ],
                    ];

                    $snapToken = \Midtrans\Snap::getSnapToken($params);
                    $transaksi->snap_token = $snapToken;
                    $transaksi->save();
                });

                return response()->json([
                    'status'     => 'success',
                    'snap_token' => $snapToken,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => $e->getMessage(),
                ], 500);
            }
        }
    }

    public function generateKuitansi($id)
    {
        $tagihan = TagihanSpp::with(['siswa.kelas', 'transaksi'])->findOrFail($id);

        // if ($tagihan->status !== 'lunas') {
        //     return redirect()->back()->with('error', 'Kuitansi hanya dapat diunduh untuk pembayaran yang sudah lunas');
        // }

        $pdf = PDF::loadView('tagihan-spp.kuitansi', [
            'tagihan' => $tagihan,
            'transaksi' => $tagihan->transaksi
        ]);

        return $pdf->stream('kuitansi-spp-'.$tagihan->siswa->nama_siswa.'-'.$tagihan->bulan.'.pdf');
    }

    public function updateStatus(Request $request)
    {
        $transaksi = Transaksi::where('kd_transaksi', $request->kd_transaksi)->first();
        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        $serverKey = config('services.midtrans.server_key');

        $authString = base64_encode($serverKey . ':');

        // Request ke Midtrans API
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . $authString
        ])->get("https://api.sandbox.midtrans.com/v2/{$request->kd_transaksi}/status");

        if ($response->successful()) {
            $data = $response->json();
            if ($data['transaction_status'] ?? '' === 'settlement') {
                $transaksi->tagihan->status = 'lunas';
                $transaksi->tagihan->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Status pembayaran berhasil diperbarui menjadi lunas.',
                    'status' => 'lunas'
                ]);
            } else {
                $transaksi->tagihan->status = 'belum_bayar';
                $transaksi->tagihan->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Status pembayaran diperbarui.',
                    'status' => 'belum_bayar'
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal memeriksa status transaksi'
        ], 500);
    }
}
