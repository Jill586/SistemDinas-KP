<?php

namespace App\Exports;

use App\Models\PerjalananDinas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanPerjalananExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
   public function collection()
    {
        return PerjalananDinas::with('pegawai')->get()->map(function ($item) {
            return [
                'Nomor SPT'        => $item->nomor_spt,
                'Nama Pegawai'     => $item->pegawai->first()->nama ?? '-',
                'Tanggal Berangkat'=> $item->tanggal_berangkat,
                'Tanggal Pulang'   => $item->tanggal_pulang,
                'Tujuan'           => $item->tujuan,
                'Keperluan'        => $item->keperluan,
                'Status'           => $item->status,
            ];
        });
    }

    /**
     * Header kolom Excel
     */
    public function headings(): array
    {
        return [
            'Nomor SPT',
            'Nama Pegawai',
            'Tanggal Berangkat',
            'Tanggal Pulang',
            'Tujuan',
            'Keperluan',
            'Status',
        ];
    }
}
