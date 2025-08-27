<?php

namespace Database\Seeders;

use App\Models\TarifSPP;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TarifSppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $heading = true;
        $input_file = fopen(base_path("database/data/tarif-spp.csv"), "r");
        while (($record = fgetcsv($input_file, 1000, ",")) !== FALSE)
        {
            if (!$heading)
            {
                $data = array(
                    "tahun" => $record['0'],
                    "tingkat_kelas" => $record['1'],
                    "nominal" => $record['2'],
                    "created_at" => now(),
                    "updated_at" => now(),
                );
                TarifSPP::create($data);
            }
            $heading = false;
        }
        fclose($input_file);
    }
}
