<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $fillable = [
        'nama_kelas',
        'tingkat_kelas',
        'wali_kelas_id',
    ];

    public function waliKelas() : HasOne
    {
        return $this->hasOne(GuruPegawai::class, 'wali_kelas_id');
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }
}
