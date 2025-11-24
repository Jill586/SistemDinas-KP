<?php

namespace App\Exports;

use App\Models\PerjalananDinas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VerifikasiPengajuanExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = PerjalananDinas::with('pegawai')
            ->whereNotIn('status', ['ditolak', 'revisi_operator', 'draft']);

        if ($this->request->filled('bulan')) {
            $query->whereMonth('tanggal_spt', $this->request->bulan);
        }

        if ($this->request->filled('tahun')) {
            $query->whereYear('tanggal_spt', $this->request->tahun);
        }

        return $query->get()->map(function($item) {
            return [
                'Nomor SPT'     => $item->nomor_spt ?? '-',
                'Nama' => $item->pegawai->first()->nama ?? '-',
                'Tujuan'        => $item->tujuan_spt,
                'Tanggal SPT'   => $item->tanggal_spt,
                'Status'        => $item->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nomor SPT',
            'Nama',
            'Tujuan',
            'Tanggal SPT',
            'Status'
        ];
    }
}
