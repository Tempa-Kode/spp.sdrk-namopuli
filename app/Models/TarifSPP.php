<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifSPP extends Model
{
    protected $table = 'tarif_spp';
    protected $fillable = ['tahun', 'tingkat_kelas', 'nominal'];

    public function tagihanSpp()
    {
        return $this->hasMany(TagihanSpp::class, 'tarif_id');
    }
}
