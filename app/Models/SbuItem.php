<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SbuItem extends Model
{
    use HasFactory;

    protected $table = 'sbu_items'; // nama tabel

    protected $fillable = [
        'kategori_biaya',
        'uraian_biaya',
        'provinsi_tujuan',
        'kota_tujuan',
        'kecamatan_tujuan',
        'desa_tujuan',
        'satuan',
        'besaran_biaya',
        'tipe_perjalanan',
        'tingkat_pejabat_atau_golongan',
        'keterangan',
        'jarak_km_min',
        'jarak_km_max',
    ];
}
