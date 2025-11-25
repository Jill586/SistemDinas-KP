<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VerifikasiStaffExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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

    public function headings(): array
    {
        return [
            'NO',
            'NO. SPT',
            'TGL. SPT',
            'OPERATOR PENGAJU',
            'TUJUAN',
            'PERSONIL',
            'PELAKSANAAN',
            'ESTIMASI BIAYA',
            'STATUS',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,

            $row->nomor_spt ?? 'Belum ada',

            \Carbon\Carbon::parse($row->tanggal_spt)->format('d M Y'),

            optional($row->operator)->name ?? '-',

            $row->tujuan_spt,

            // Personil (join semua nama pegawai)
            $row->pegawai->pluck('nama')->join(', '),

            // Pelaksanaan
            $row->tanggal_mulai . ' s/d ' . $row->tanggal_selesai,

            // Estimasi biaya (format rupiah)
            'Rp ' . number_format($row->total_estimasi_biaya, 0, ',', '.'),

            strtoupper($row->status),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]] // Heading bold
        ];
    }
}

