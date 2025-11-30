<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerjalananDinasBiaya extends Model
{
    use HasFactory;

    protected $table = 'perjalanan_dinas_biaya';

    protected $fillable = [
        'perjalanan_dinas_id',
        'sbu_item_id',
        // DB bisa menggunakan salah satu dari dua kolom ini depending on your schema:
        'pegawai_id_terkait',    // jika mengacu ke tabel pegawai (optional)
        'deskripsi_biaya',
        'jumlah_personil_terkait',
        'jumlah_hari_terkait',
        'jumlah_unit',
        'harga_satuan',
        'subtotal_biaya',
        'keterangan_tambahan',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'subtotal_biaya' => 'decimal:2',
        'jumlah_personil_terkait' => 'integer',
        'jumlah_hari_terkait' => 'integer',
        'jumlah_unit' => 'integer',
    ];

    public function perjalananDinas(): BelongsTo
    {
        return $this->belongsTo(PerjalananDinas::class, 'perjalanan_dinas_id');
    }

    public function sbuItem(): BelongsTo
    {
        return $this->belongsTo(SbuItem::class, 'sbu_item_id');
    }

    public function userTerkait(): BelongsTo
    {
        return $this->belongsTo(\Pegawai::class, 'pegawai_id');
    }

    public function pegawaiTerkait(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id_terkait');
    }

    public function getPersonilNameAttribute(): ?string
    {
        if ($this->pegawaiTerkait) {
            return $this->pegawaiTerkait->nama ?? null;
        }
        if ($this->userTerkait) {
            // user mungkin punya field 'nama' atau 'name' -> sesuaikan
            return $this->userTerkait->nama ?? $this->userTerkait->name ?? null;
        }
        return null;
    }
}