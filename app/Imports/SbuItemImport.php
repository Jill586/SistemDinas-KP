<?php

namespace App\Imports;

use App\Models\SbuItem;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SbuItemImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new SbuItem([
            'kategori_biaya' => $row['kategori_biaya'] ?? null,
            'uraian_biaya' => $row['uraian_biaya'] ?? null,
            'provinsi_tujuan' => $row['provinsi_tujuan'] ?? null,
            'kota_tujuan' => $row['kota_tujuan'] ?? null,
            'kecamatan_tujuan' => $row['kecamatan_tujuan'] ?? null,
            'desa_tujuan' => $row['desa_tujuan'] ?? null,
            'satuan' => $row['satuan'] ?? null,
            'besaran_biaya' => $row['besaran_biaya'] ?? null,
            'tipe_perjalanan' => $row['tipe_perjalanan'] ?? null,
            'tingkat_pejabat_atau_golongan' => $row['tingkat_pejabat_atau_golongan'] ?? null,
            'keterangan' => $row['keterangan'] ?? null,
            'jarak_km_min' => $row['jarak_km_min'] ?? null,
            'jarak_km_max' => $row['jarak_km_max'] ?? null,
        ]);
    }
}
