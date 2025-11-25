<?php

namespace App\Exports;

use App\Models\PerjalananDinas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class LaporanPerjalananExport implements FromCollection, WithHeadings, WithStyles
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

            // Personil
            $personil = $item->pegawai->pluck('nama')->join(', ');

            // Tanggal SPT
            $tglSpt = $item->tanggal_spt
                ? Carbon::parse($item->tanggal_spt)->format('d M Y')
                : '-';

            // Pelaksanaan
            $pelaksanaan =
                ($item->tanggal_mulai ? Carbon::parse($item->tanggal_mulai)->format('d M Y') : '-') .
                ' s/d ' .
                ($item->tanggal_selesai ? Carbon::parse($item->tanggal_selesai)->format('d M Y') : '-');

            // Status laporan
            $statusLaporan = $item->status_laporan ?? '-';

            $rows[] = [
                'NO'                => $no++,
                'Nomor SPT'         => $item->nomor_spt ?? '-',
                'TGL SPT'           => $tglSpt,
                'TUJUAN'            => $item->tujuan_spt ?? '-',
                'PERSONIL'          => $personil,
                'PELAKSANAAN'       => $pelaksanaan,
                'STATUS SPT'        => strtoupper($item->status ?? '-'),
                'STATUS LAPORAN'    => strtoupper($statusLaporan),
                'DOKUMEN LAPORAN'   => '-',   // Sesuai tampilan tabel
            ];
        }

        return collect($rows);
    }

    public function headings(): array
    {
        return [
            'NO',
            'NO. SPT',
            'TGL. SPT',
            'TUJUAN',
            'PERSONIL',
            'PELAKSANAAN',
            'STATUS SPT',
            'STATUS LAPORAN',
            'DOKUMEN LAPORAN',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
