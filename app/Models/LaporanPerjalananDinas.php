<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPerjalananDinas extends Model
{
    use HasFactory;

    protected $table = 'laporan_perjalanan_dinas';

    protected $fillable = [
        'perjalanan_dinas_id',
        'pegawai_id',
        'tanggal_laporan',
        'ringkasan_hasil_kegiatan',
        'kendala_dihadapi',
        'saran_tindak_lanjut',
        'status_laporan',
        'catatan_preview',
        'total_biaya_riil_dilaporkan',
    ];

    // Relasi ke tabel perjalanan_dinas
    public function perjalananDinas()
    {
        return $this->belongsTo(PerjalananDinas::class, 'perjalanan_dinas_id');
    }

    // Relasi ke pegawai pelapor
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }
}
