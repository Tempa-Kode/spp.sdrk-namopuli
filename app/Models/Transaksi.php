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
        'transaksi_gabungan_id',
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
    ];

    public function tagihan()
    {
        return $this->belongsTo(TagihanSpp::class, 'tagihan_id');
    }

    public function transaksiGabungan()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_gabungan_id');
    }

    public function transaksiTerkait()
    {
        return $this->hasMany(Transaksi::class, 'transaksi_gabungan_id');
    }
}
