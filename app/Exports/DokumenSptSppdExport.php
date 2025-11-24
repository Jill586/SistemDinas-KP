<?php

namespace App\Exports;

use App\Models\PerjalananDinas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DokumenSptSppdExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return PerjalananDinas::with('pegawai')
            ->where('status', 'disetujui')
            ->get()
            ->map(function($item) {
                return [
                    'Nomor SPT'     => $item->nomor_spt ?? '-',
                    'Nama Pegawai'  => $item->pegawai->pluck('nama')->join(', ') ?? '-',
                    'Tujuan'        => $item->tujuan_spt ?? '-',
                    'Tanggal SPT'   => $item->tanggal_spt ?? '-',
                    'Status'        => $item->status ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nomor SPT',
            'Nama Pegawai',
            'Tujuan Perjalanan',
            'Tanggal SPT',
            'Status'
        ];
    }
}
