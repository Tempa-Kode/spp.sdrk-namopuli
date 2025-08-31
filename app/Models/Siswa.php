<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $fillable = [
        'kelas_id',
        'nisn',
        'nama_siswa',
        'jenkel',
        'nomor_telp_orangtua',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function getJenisKelaminLengkapAttribute()
    {
        return $this->jenkel == 'L' ? 'Laki-laki' : 'Perempuan';
    }

    public function tagihanSpp()
    {
        return $this->hasMany(TagihanSpp::class, 'siswa_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'siswa_id');
    }
}
