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

        $personil = $item->pegawai->pluck('nama')->join(', ');

        $tglSpt = $item->tanggal_spt
            ? Carbon::parse($item->tanggal_spt)->format('d M Y')
            : '-';

        $pelaksanaan =
            ($item->tanggal_mulai ? Carbon::parse($item->tanggal_mulai)->format('d M Y') : '-') .
            ' s/d ' .
            ($item->tanggal_selesai ? Carbon::parse($item->tanggal_selesai)->format('d M Y') : '-');

        $statusLaporan = $item->status_laporan ?? '-';

        $statusBayarLaporan = optional($item->laporan)->status_bayar;

        // ✅ FIX: ambil dari kolom yang BENAR
        $uraian = $item->uraian_spt ?? '-';

        $rows[] = [
            'NO'                => $no++,
            'Nomor SPT'         => $item->nomor_spt ?? '-',
            'TGL SPT'           => $tglSpt,
            'TUJUAN'            => $item->tujuan_spt ?? '-',
            'PERSONIL'          => $personil,
            'PELAKSANAAN'       => $pelaksanaan,
            'URAIAN'            => $uraian,
            'STATUS SPT'        => strtoupper($item->status ?? '-'),
            'STATUS LAPORAN'    => strtoupper($statusLaporan),
            'STATUS PEMBAYARAN' => strtoupper($statusBayarLaporan ?? '-'),
            'DOKUMEN LAPORAN'   => '-',
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
            'URAIAN',
            'STATUS SPT',
            'STATUS LAPORAN',
            'STATUS PEMBAYARAN',
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
