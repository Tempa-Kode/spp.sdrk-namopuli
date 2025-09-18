<?php

namespace Database\Seeders;

use App\Models\Siswa;
use App\Models\TarifSPP;
use App\Models\Transaksi;
use App\Models\TagihanSpp;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DataDummyTABaruSeeer extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siswa = Siswa::all();

        // Array bulan untuk tahun 2025
        $bulanList = [
            '2025-07', '2025-08'
        ];

        foreach ($siswa as $item) {
            $kelas = $item->kelas->tingkat_kelas;
            $tarif = TarifSPP::select('id', 'nominal')->where('tingkat_kelas', $kelas)->first();

            // Skip jika tarif tidak ditemukan
            if (!$tarif) {
                continue;
            }

            // Buat transaksi untuk setiap bulan
            foreach ($bulanList as $bulan) {
                $kodeRandom = random_int(100, 999);

                // Buat timestamp untuk created_at tagihan (awal bulan)
                $tahun = substr($bulan, 0, 4);
                $bulanNum = substr($bulan, 5, 2);
                $hariCreated = str_pad(random_int(1, 5), 2, '0', STR_PAD_LEFT); // Awal bulan (1-5)
                $jamCreated = str_pad(random_int(8, 10), 2, '0', STR_PAD_LEFT); // Pagi hari
                $menitCreated = str_pad(random_int(0, 59), 2, '0', STR_PAD_LEFT);

                $createdAtTagihan = "{$tahun}-{$bulanNum}-{$hariCreated} {$jamCreated}:{$menitCreated}:00";

                $tagihanBaru = TagihanSpp::create([
                    'kode_tagihan' => "{$bulan}-{$kodeRandom}-{$item->nisn}",
                    'siswa_id' => $item->id,
                    'tarif_id' => $tarif->id,
                    'bulan' => $bulan,
                    'status' => 'lunas',
                    'created_at' => $createdAtTagihan,
                    'updated_at' => $createdAtTagihan,
                ]);

                // Buat tanggal bayar random dalam bulan yang sesuai (setelah tagihan dibuat)
                $hariPembayaran = str_pad(random_int(6, 28), 2, '0', STR_PAD_LEFT); // Setelah tagihan dibuat
                $jamPembayaran = str_pad(random_int(8, 16), 2, '0', STR_PAD_LEFT);
                $menitPembayaran = str_pad(random_int(0, 59), 2, '0', STR_PAD_LEFT);

                $tanggalBayar = "{$tahun}-{$bulanNum}-{$hariPembayaran} {$jamPembayaran}:{$menitPembayaran}:00";

                // Update tagihan status menjadi lunas pada saat pembayaran
                $updatedAtTagihan = $tanggalBayar;

                $transaksi = Transaksi::create([
                    'kd_transaksi' => $tagihanBaru->kode_tagihan,
                    'tagihan_id' => $tagihanBaru->id,
                    'jumlah_bayar' => $tarif->nominal,
                    'tanggal_bayar' => $tanggalBayar,
                    'created_at' => $tanggalBayar,
                    'updated_at' => $tanggalBayar,
                ]);

                // Update tagihan dengan updated_at yang sesuai dengan waktu pembayaran
                $tagihanBaru->update([
                    'updated_at' => $updatedAtTagihan
                ]);
            }
        }
    }
}
