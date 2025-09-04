<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $heading = true;
        $input_file = fopen(base_path("database/data/data-siswa.csv"), "r");
        while (($record = fgetcsv($input_file, 1000, ",")) !== FALSE)
        {
            if (!$heading)
            {
                $kelas = Kelas::where('tingkat_kelas', $record['7'])->first();
                $siswa = array(
                    'kelas_id' => $kelas ? $kelas->id : null,
                    "nisn" => $record['0'],
                    "nama_siswa" => $record['1'],
                    "jenkel" => $record['2'],
                    "tempat_lahir" => $record['3'],
                    "tanggal_lahir" => $record['4'],
                    "agama" => $record['5'],
                    "created_at" => now(),
                    "updated_at" => now(),
                );
                $simpanSiswa = Siswa::create($siswa);
                User::create([
                    'siswa_id' => $simpanSiswa->id,
                    'nama' => $record['1'],
                    'password' => bcrypt($record['0']),
                    'role' => 'wali',
                ]);
            }
            $heading = false;
        }
        fclose($input_file);
    }
}
