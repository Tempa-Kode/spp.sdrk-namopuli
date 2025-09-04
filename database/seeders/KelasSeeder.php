<?php

namespace Database\Seeders;

use App\Models\GuruPegawai;
use App\Models\Kelas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $heading = true;
        $input_file = fopen(base_path("database/data/data-kelas.csv"), "r");
        while (($record = fgetcsv($input_file, 1000, ",")) !== FALSE)
        {
            if (!$heading)
            {
                $waliKelas = GuruPegawai::where('nama', $record['2'])->first();
                $kelas = array(
                    "tingkat_kelas" => $record['1'],
                    "wali_kelas_id" => $waliKelas ? $waliKelas->id : null,
                    "created_at" => now(),
                    "updated_at" => now(),
                );
                $simpanKelas = Kelas::create($kelas);
            }
            $heading = false;
        }
        fclose($input_file);
    }
}
