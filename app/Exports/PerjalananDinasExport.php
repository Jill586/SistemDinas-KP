<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PerjalananDinasExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithCustomStartCell,
    WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    // Mulai data dari row ke-3 agar row 1 dipakai untuk judul
    public function startCell(): string
    {
        return 'A3';
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Jenis SPT',
            'Jenis Kegiatan',
            'Tujuan',
            'Nama Pegawai',
            'Status',
            'Total Estimasi'
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->tanggal_spt,
            $row->jenis_spt,
            $row->jenis_kegiatan,
            $row->tujuan_spt,
            $row->pegawai->pluck('nama')->join(', '),
            $row->status,
            number_format($row->total_estimasi_biaya, 0, ',', '.'),
        ];
    }

    // Tambahkan styling untuk judul
    public function styles(Worksheet $sheet)
    {
        // Judul di baris 1
        $sheet->setCellValue('A1', 'Laporan Perjalanan Dinas');
        $sheet->mergeCells('A1:H1');

        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            3 => ['font' => ['bold' => true]] // Heading tabel
        ];
    }
}
