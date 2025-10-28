<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TagihanSpp;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TagihanOktoberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();

            // Ambil semua tagihan bulan Oktober 2025 yang belum bayar
            $tagihanOktober = TagihanSpp::where('bulan', '2025-10')
                                        ->where('status', 'belum_bayar')
                                        ->with(['siswa', 'tarif'])
                                        ->get();

            if ($tagihanOktober->isEmpty()) {
                $this->command->info('Tidak ada tagihan Oktober 2025 yang belum bayar.');
                return;
            }

            $this->command->info("Ditemukan {$tagihanOktober->count()} tagihan Oktober 2025 yang belum bayar.");

            $counter = 0;
            $tipePembayaran = ['bank_transfer'];

            foreach ($tagihanOktober as $tagihan) {

                $kodeTransaksi = $tagihan->kode_tagihan;

                // Generate snap token dummy
                $snapToken = 'snap-' . uniqid() . '-' . bin2hex(random_bytes(10));

                // Pilih tipe pembayaran secara random
                $randomTipePembayaran = $tipePembayaran[array_rand($tipePembayaran)];

                // Buat transaksi
                Transaksi::create([
                    'kd_transaksi' => $kodeTransaksi,
                    'tagihan_id' => $tagihan->id,
                    'jumlah_bayar' => $tagihan->tarif->nominal ?? 0,
                    'tanggal_bayar' => Carbon::create(2025, 10, rand(1, 28))->setTime(rand(8, 16), rand(0, 59)),
                    'tipe_pembayaran' => $randomTipePembayaran,
                    'snap_token' => $snapToken,
                ]);

                // Update status tagihan menjadi lunas
                $tagihan->update(['status' => 'lunas']);

                $counter++;
                $this->command->info("✓ Tagihan #{$tagihan->id} ({$tagihan->siswa->nama_siswa}) - {$randomTipePembayaran}");
            }

            DB::commit();

            $this->command->info("\n✅ Berhasil memproses {$counter} tagihan Oktober 2025 menjadi lunas.");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
