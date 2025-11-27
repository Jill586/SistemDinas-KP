<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';

    protected $fillable = [
        'nama', 'email', 'nomor_hp', 'nip', 'golongan_id', 'jabatan_id', 'jabatan_struktural', 'pangkat_golongan'
    ];

    public function perjalananDinas()
    {
        return $this->belongsToMany(PerjalananDinas::class, 'perjalanan_dinas_user', 'pegawai_id', 'perjalanan_dinas_id');
    }

        // Relasi ke Golongan
    public function golongan()
    {
        return $this->belongsTo(Golongan::class);
    }

    // Relasi ke Jabatan
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

}
