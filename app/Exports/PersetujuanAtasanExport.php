<?php

namespace App\Exports;

use App\Models\PerjalananDinas;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PersetujuanAtasanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = PerjalananDinas::with(['pegawai'])
            ->whereIn('status', ['verifikasi', 'disetujui']);

        // Filter bulan
        if ($this->request->filled('bulan')) {
            $query->whereMonth('tanggal_spt', $this->request->bulan);
        }

        // Filter tahun
        if ($this->request->filled('tahun')) {
            $query->whereYear('tanggal_spt', $this->request->tahun);
        }

        return $query->get();
    }

    public function map($item): array
    {
        return [
            $item->nomor_spt ?? '-',
            $item->pegawai->first()->nama ?? '-',
            $item->tujuan_spt,
            $item->tanggal_spt,
            $item->status,
        ];
    }

    public function headings(): array
    {
        return [
            'Nomor SPT',
            'Nama Pegawai',
            'Tujuan',
            'Tanggal SPT',
            'Status',
        ];
    }
}
