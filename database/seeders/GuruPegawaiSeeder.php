<?php

namespace Database\Seeders;

use App\Models\GuruPegawai;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GuruPegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $heading = true;
        $input_file = fopen(base_path("database/data/data-guru-pegawai.csv"), "r");
        while (($record = fgetcsv($input_file, 1000, ",")) !== FALSE)
        {
            if (!$heading)
            {
                $data = array(
                    "nama" => $record['0'],
                    "nuptk" => $record['1'],
                    "jenkel" => $record['2'],
                    "tempat_lahir" => $record['3'],
                    "tanggal_lahir" => $record['4'],
                    "jabatan" => $record['5'],
                    "created_at" => now(),
                    "updated_at" => now(),
                );
                $simpanGuruPegawai = GuruPegawai::create($data);
                User::create([
                    'petugas_id' => $simpanGuruPegawai->id,
                    'nama' => $record['0'],
                    'password' => bcrypt($record['1']),
                    'role' => 'petugas',
                ]);
            }
            $heading = false;
        }
        fclose($input_file);
    }
}
