<?php

namespace App\Exports;

use App\Models\SbuItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SbuItemExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return SbuItem::select(
            'kategori_biaya',
            'uraian_biaya',
            'provinsi_tujuan',
            'kota_tujuan',
            'satuan',
            'tingkat_pejabat_atau_golongan',
            'tipe_perjalanan',
            'besaran_biaya'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Kategori Biaya',
            'Uraian Biaya',
            'Provinsi Tujuan',
            'Kota Tujuan',
            'Satuan',
            'Golongan',
            'Tipe Perjalanan',
            'Besaran Biaya'
        ];
    }

    // Style Excel
    public function styles(Worksheet $sheet)
    {
        // Membuat header (baris 1) menjadi bold & background abu
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold' => true
            ],
            'fill' => [
                'fillType' => 'solid',
                'color' => ['rgb' => 'E0E0E0'] // abu muda
            ],
            'alignment' => [
                'horizontal' => 'center'
            ]
        ]);

        return [];
    }
}
