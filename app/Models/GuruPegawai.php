<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GuruPegawai extends Model
{
    protected $table = 'guru_pegawai';

    protected $fillable = [
        'nama',
        'nuptk',
        'jenkel',
        'tempat_lahir',
        'tanggal_lahir',
        'jabatan',
    ];

    public function kelas() : HasOne
    {
        return $this->hasOne(Kelas::class, 'wali_kelas_id');
    }

    public function user() : HasOne
    {
        return $this->hasOne(User::class, 'petugas_id');
    }
}
