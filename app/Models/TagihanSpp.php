<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagihanSpp extends Model
{
    protected $table = 'tagihan_spp';
    protected $fillable = [
        'siswa_id',
        'tarif_id',
        'bulan',
        'status',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function tarif()
    {
        return $this->belongsTo(TarifSPP::class, 'tarif_id');
    }

    public function transaksi()
    {
        return $this->hasOne(Transaksi::class, 'tagihan_id');
    }
}
