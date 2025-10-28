<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PerjalananDinas extends Model
{
    protected $table = 'perjalanan_dinas';

    protected $fillable = [
        'operator_id',
        'verifikator_id',
        'atasan_id',
        'nomor_spt',
        'tanggal_spt',
        'jenis_spt',
        'jenis_kegiatan',
        'tujuan_spt',
        'provinsi_tujuan_id',
        'kota_tujuan_id',
        'dasar_spt',
        'uraian_spt',
        'bukti_undangan',
        'alat_angkut',
        'lama_hari',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'status_laporan', 
        'catatan_verifikator',
        'catatan_atasan',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($perjalanan) {
            // default status ketika operator membuat pengajuan
            if (empty($perjalanan->status)) {
                $perjalanan->status = 'diproses';
            }

            // default status laporan jika belum diisi
            if (empty($perjalanan->status_laporan)) {
                $perjalanan->status_laporan = 'belum_dibuat';
            }
        });
    }

    // Relasi ke pegawai (pivot table)
    public function pegawai()
    {
        return $this->belongsToMany(Pegawai::class, 'perjalanan_dinas_user', 'perjalanan_dinas_id', 'pegawai_id');
    }

    // Relasi biaya
    public function biaya(): HasMany
    {
        return $this->hasMany(PerjalananDinasBiaya::class, 'perjalanan_dinas_id');
    }

    public function biayaRiil()
    {
        return $this->hasMany(PerjalananDinasBiayaRiil::class, 'perjalanan_dinas_id');
    }

    // ✅ cukup 1 relasi laporan saja
    public function laporan()
    {
        return $this->hasOne(LaporanPerjalananDinas::class, 'perjalanan_dinas_id', 'id');
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id', 'id');
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'verifikator_id');
    }

    public function atasan()
    {
        return $this->belongsTo(User::class, 'atasan_id');
    }


}
