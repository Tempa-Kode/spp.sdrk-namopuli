<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Siswa;
use App\Models\TarifSPP;
use App\Models\TagihanSpp;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules\Password;

class WaliController extends Controller
{
    public function profil()
    {
        $siswa = Auth::user()->siswa;
        $data = Siswa::where('id', $siswa->id)->with('kelas')->first();
        return view('profile-siswa', compact('data'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'Password lama harus diisi.',
            'password.required' => 'Password baru harus diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password baru harus terdiri dari minimal 8 karakter.',
        ]);

        $user = Auth::user();

        // Cek apakah password lama benar
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profil.siswa')->with('success', 'Password berhasil diperbarui!');
    }

    public function tagihan()
    {
        $tagihan = TagihanSpp::where('siswa_id', Auth::user()->siswa->id)
            ->with('siswa', 'tarif')
            ->orderBy('bulan', 'desc')
            ->get();
        return view('tagihan-spp.wali.index', compact('tagihan'));
    }

    public function detailTagihan($id)
    {
        $tagihan = TagihanSpp::where('id', $id)
            ->where('siswa_id', Auth::user()->siswa->id)
            ->with('siswa', 'tarif', 'transaksi')
            ->firstOrFail();
            // dd($tagihan);
        return view('tagihan-spp.wali.detail', compact('tagihan'));
    }

    public function buatTagihan(Request $request)
    {
        $validated = $request->validate([
            'bulan_mulai' => 'required|date_format:Y-m',
            'bulan_akhir' => 'required|date_format:Y-m|after_or_equal:bulan_mulai',
        ]);

        try {
            DB::beginTransaction();

            $siswa = Auth::user()->siswa;
            $bulanMulai = Carbon::createFromFormat('Y-m', $validated['bulan_mulai']);
            $bulanAkhir = Carbon::createFromFormat('Y-m', $validated['bulan_akhir']);

            $tagihanCreated = 0;
            $tagihanSkipped = 0;
            $current = $bulanMulai->copy();

            while ($current <= $bulanAkhir) {
                $bulanString = $current->format('Y-m');
                $tahun = $current->year;

                // Cek apakah sudah ada tagihan untuk bulan ini
                $existingTagihan = TagihanSpp::where('siswa_id', $siswa->id)
                                             ->where('bulan', $bulanString)
                                             ->exists();

                if ($existingTagihan) {
                    $tagihanSkipped++;
                    $current->addMonth();
                    continue;
                }

                // Cari tarif berdasarkan tahun dan tingkat kelas
                $tarif = TarifSPP::where('tahun', $tahun)
                                 ->where('tingkat_kelas', $siswa->kelas->tingkat_kelas ?? 0)
                                 ->first();

                if ($tarif) {
                    $kodeBulan = $current->format('m');
                    $kodeRandom = random_int(100, 999);
                    $nisn = $siswa->nisn ?: '0000';
                    $kodeTagihan = "{$kodeBulan}-{$kodeRandom}-{$nisn}";

                    TagihanSpp::create([
                        'kode_tagihan' => $kodeTagihan,
                        'siswa_id' => $siswa->id,
                        'tarif_id' => $tarif->id,
                        'bulan' => $bulanString,
                        'status' => 'belum_bayar',
                    ]);

                    $tagihanCreated++;
                }

                $current->addMonth();
            }

            DB::commit();

            $message = "Berhasil membuat {$tagihanCreated} tagihan SPP.";
            if ($tagihanSkipped > 0) {
                $message .= " ({$tagihanSkipped} bulan dilewati karena sudah ada tagihan).";
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat tagihan: ' . $e->getMessage());
        }
    }

    public function bayarMultiple(Request $request)
    {
        $validated = $request->validate([
            'tagihan_ids' => 'required|array|min:1',
            'tagihan_ids.*' => 'exists:tagihan_spp,id',
        ]);

        try {
            \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
            \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
            \Midtrans\Config::$isSanitized = config('services.midtrans.is_sanitized');
            \Midtrans\Config::$is3ds = config('services.midtrans.is_3ds');

            $siswaId = Auth::user()->siswa->id;

            // Ambil semua tagihan yang dipilih
            $tagihanList = TagihanSpp::whereIn('id', $validated['tagihan_ids'])
                                    ->where('siswa_id', $siswaId)
                                    ->where('status', 'belum_bayar')
                                    ->with(['tarif', 'siswa'])
                                    ->get();

            if ($tagihanList->isEmpty()) {
                return back()->with('error', 'Tidak ada tagihan valid yang dipilih.');
            }

            // Generate kode transaksi gabungan SATU untuk semua
            $kodeTransaksiGabungan = 'MULTI-' . now()->format('YmdHis') . '-' . $siswaId;

            // Hitung total
            $totalBayar = $tagihanList->sum(fn($t) => $t->tarif->nominal ?? 0);

            DB::beginTransaction();

            // Buat transaksi untuk SETIAP tagihan dengan kode yang sama
            foreach ($tagihanList as $tagihan) {
                Transaksi::create([
                    'kd_transaksi' => $kodeTransaksiGabungan, // ← SAMA untuk semua
                    'tagihan_id' => $tagihan->id,
                    'jumlah_bayar' => $tagihan->tarif->nominal ?? 0, // ← Individual
                    'tanggal_bayar' => now(),
                    // TIDAK ADA transaksi_gabungan_id karena tidak perlu hierarchy
                ]);
            }

            // Setup Midtrans
            $siswa = $tagihanList->first()->siswa;
            $bulanList = $tagihanList->pluck('bulan')->map(function($bulan) {
                return \Carbon\Carbon::createFromFormat('Y-m', $bulan)->format('M Y');
            })->implode(', ');

            $params = [
                'transaction_details' => [
                    'order_id' => $kodeTransaksiGabungan,
                    'gross_amount' => $totalBayar,
                ],
                'customer_details' => [
                    'first_name' => $siswa->nama_siswa,
                    'last_name' => $siswa->nisn ?? '',
                    'email' => 'siswa@example.com',
                    'phone' => $siswa->nomor_telp_orangtua ?? '08111222333',
                ],
                'item_details' => [
                    [
                        'id' => 'SPP-MULTIPLE',
                        'price' => $totalBayar,
                        'quantity' => 1,
                        'name' => 'Pembayaran SPP Multiple Bulan (' . $bulanList . ')',
                    ]
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Update SEMUA transaksi dengan snap token yang sama
            Transaksi::where('kd_transaksi', $kodeTransaksiGabungan)
                    ->update(['snap_token' => $snapToken]);

            DB::commit();

            return redirect()->route('tagihan-spp.wali.pembayaran-multiple', [
                'snap_token' => $snapToken,
                'tagihan_ids' => implode(',', $validated['tagihan_ids']),
                'total' => $totalBayar
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function pembayaranMultiple(Request $request)
    {
        $snapToken = $request->get('snap_token');
        $tagihanIds = explode(',', $request->get('tagihan_ids'));
        $total = $request->get('total');

        if (!$snapToken || empty($tagihanIds)) {
            return redirect()->route('tagihan-spp.wali')->with('error', 'Data pembayaran tidak valid.');
        }

        $siswaId = Auth::user()->siswa->id;
        $tagihanList = TagihanSpp::whereIn('id', $tagihanIds)
                                 ->where('siswa_id', $siswaId)
                                 ->with(['tarif', 'siswa'])
                                 ->get();

        if ($tagihanList->isEmpty()) {
            return redirect()->route('tagihan-spp.wali')->with('error', 'Tagihan tidak ditemukan.');
        }

        return view('tagihan-spp.wali.pembayaran-multiple', compact('snapToken', 'tagihanList', 'total'));
    }

    public function kwitansiGabungan(Request $request)
    {
        $kodeTransaksi = $request->get('kd_transaksi');

        if (!$kodeTransaksi) {
            return back()->with('error', 'Kode transaksi tidak valid.');
        }

        // Ambil SEMUA transaksi dengan kode yang sama
        $semuaTransaksi = Transaksi::where('kd_transaksi', $kodeTransaksi)
                                ->with(['tagihan.siswa', 'tagihan.tarif'])
                                ->get();

        if ($semuaTransaksi->isEmpty()) {
            return back()->with('error', 'Transaksi tidak ditemukan.');
        }

        // Pastikan milik siswa yang login
        $siswaId = $semuaTransaksi->first()->tagihan->siswa->id;
        if ($siswaId !== Auth::user()->siswa->id) {
            abort(403, 'Unauthorized access.');
        }

        // Hitung total (jumlahkan semua)
        $totalPembayaran = $semuaTransaksi->sum('jumlah_bayar');

        // Detail per bulan
        $detailTagihan = $semuaTransaksi->map(function($transaksi) {
            return [
                'bulan' => $transaksi->tagihan->bulan,
                'nominal' => $transaksi->jumlah_bayar,
                'kode_tagihan' => $transaksi->tagihan->kode_tagihan,
            ];
        });

        $transaksiPertama = $semuaTransaksi->first(); // Untuk data siswa

        return view('tagihan-spp.wali.kwitansi-gabungan', compact('transaksiPertama', 'detailTagihan', 'totalPembayaran'));
    }

    public function updateStatusMultiple(Request $request)
    {
        $validated = $request->validate([
            'kd_transaksi' => 'required|string'
        ]);

        try {
            // Check payment status
            $serverKey = config('services.midtrans.server_key');
            $authString = base64_encode($serverKey . ':');

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . $authString
            ])->get("https://api.sandbox.midtrans.com/v2/{$validated['kd_transaksi']}/status");

            if ($response->successful()) {
                $data = $response->json();

                if ($data['transaction_status'] ?? '' === 'settlement') {
                    DB::beginTransaction();

                    // Update SEMUA tagihan dengan kd_transaksi yang sama
                    $transaksiList = Transaksi::where('kd_transaksi', $validated['kd_transaksi'])->get();

                    foreach ($transaksiList as $transaksi) {
                        $transaksi->tagihan->status = 'lunas';
                        $transaksi->tagihan->save();
                    }

                    DB::commit();

                    return response()->json([
                        'success' => true,
                        'message' => 'Pembayaran berhasil dikonfirmasi',
                        'redirect_url' => route('tagihan-spp.wali.kwitansi-gabungan') . '?kd_transaksi=' . $validated['kd_transaksi']
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Pembayaran belum selesai atau gagal'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
