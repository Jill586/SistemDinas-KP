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

    /**
     * Relasi ke PerjalananDinas
     *
     * @return BelongsTo
     */
    public function perjalananDinas(): BelongsTo
    {
        return $this->belongsTo(PerjalananDinas::class, 'perjalanan_dinas_id');
    }

    /**
     * Relasi ke SbuItem
     *
     * @return BelongsTo
     */
    public function sbuItem(): BelongsTo
    {
        return $this->belongsTo(SbuItem::class, 'sbu_item_id');
    }

    /**
     * Relasi ke User (jika kamu menyimpan referensi ke tabel users)
     *
     * @return BelongsTo
     */
    public function userTerkait(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'pegawai_id_terkait');
    }

    /**
     * Relasi ke Pegawai (jika kamu menyimpan referensi ke tabel pegawai)
     *
     * @return BelongsTo
     */
    public function pegawaiTerkait(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id_terkait');
    }

    /**
     * Accessor helper untuk mendapatkan nama personil (prioritas: pegawai, lalu user)
     * Usage: $biaya->personil_name
     *
     * @return string|null
     */
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
