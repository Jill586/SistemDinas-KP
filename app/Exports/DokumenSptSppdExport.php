<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class DokumenSptSppdExport implements FromCollection, WithHeadings, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $rows = [];
        $no = 1;

        foreach ($this->data as $item) {
            $rows[] = [
                'NO'            => $no++,
                'Nomor SPT'     => $item->nomor_spt ?? '-',

                'TGL SPT'       => $item->tanggal_spt
                                    ? Carbon::parse($item->tanggal_spt)->format('d M Y')
                                    : '-',

                'TUJUAN'        => $item->tujuan_spt ?? '-',

                'PERSONIL'      => $item->pegawai->pluck('nama')->join(', '),

                'PELAKSANAAN'   =>
                    ($item->tanggal_mulai
                        ? Carbon::parse($item->tanggal_mulai)->format('d M Y')
                        : '-') .
                    ' s/d ' .
                    ($item->tanggal_selesai
                        ? Carbon::parse($item->tanggal_selesai)->format('d M Y')
                        : '-'),

                'STATUS'        => strtoupper($item->status ?? '-'),
            ];
        }

        return collect($rows);   // ✔ WAJIB
    }

    public function headings(): array
    {
        return [
            'NO',
            'Nomor SPT',
            'TGL SPT',
            'TUJUAN',
            'PERSONIL',
            'PELAKSANAAN',
            'STATUS',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // header bold
        ];
    }
}
