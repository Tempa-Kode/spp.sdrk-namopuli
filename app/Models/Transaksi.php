<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'kd_transaksi',
        'tagihan_id',
        'jumlah_bayar',
        'tanggal_bayar',
        'snap_token',
    ];

    public function tagihan()
    {
        return $this->belongsTo(TagihanSpp::class, 'tagihan_id');
    }
}
