<?php

namespace App\Exports;

use App\Models\PerjalananDinas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PerjalananDinasExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return PerjalananDinas::with('pegawai')->get();
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->tanggal_spt,
            $row->jenis_spt,
            $row->jenis_kegiatan,
            $row->tujuan_spt,
            $row->provinsi_tujuan_id,
            $row->kota_tujuan_id,
            $row->tanggal_mulai,
            $row->tanggal_selesai,
            $row->status,
            $row->pegawai->pluck('nama')->implode(', '), // banyak pegawai
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tanggal SPT',
            'Jenis SPT',
            'Jenis Kegiatan',
            'Tujuan',
            'Provinsi',
            'Kota',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Status',
            'Personil',
        ];
    }
}
