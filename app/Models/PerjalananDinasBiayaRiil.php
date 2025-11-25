<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerjalananDinasBiayaRiil extends Model
{
    use HasFactory;

    protected $table = 'perjalanan_dinas_biaya_riil';

    protected $fillable = [
    'perjalanan_dinas_id',
    'deskripsi_biaya',
    'provinsi_tujuan',
    'jumlah',
    'satuan',
    'harga_satuan',
    'nomor_bukti',
    'path_bukti_file', // ✅ ini penting
    'keterangan_tambahan',
    'subtotal_biaya',
];


    protected $casts = [
        'jumlah' => 'integer',
        'harga_satuan' => 'decimal:2',
        'subtotal_biaya' => 'decimal:2',
    ];

    public function perjalananDinas(): BelongsTo
    {
        return $this->belongsTo(PerjalananDinas::class, 'perjalanan_dinas_id');
    }
}
