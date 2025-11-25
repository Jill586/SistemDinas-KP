<?php

namespace App\Exports;

use App\Models\PerjalananDinas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PersetujuanAtasanExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = PerjalananDinas::with(['pegawai', 'operator', 'verifikator'])
            ->whereIn('status', ['verifikasi', 'disetujui']);

        if ($this->request->filled('bulan')) {
            $query->whereMonth('tanggal_spt', $this->request->bulan);
        }

        if ($this->request->filled('tahun')) {
            $query->whereYear('tanggal_spt', $this->request->tahun);
        }

        return $query->orderByDesc('tanggal_spt')->get();
    }

    public function headings(): array
    {
        return [
            'NO',
            'NO. SPT',
            'TGL. SPT',
            'OPERATOR',
            'VERIFIKATOR',
            'TUJUAN',
            'PERSONIL',
            'PELAKSANAAN',
            'STATUS',
        ];
    }

    public function map($item): array
    {
        // personil = list semua pegawai
        $personil = $item->pegawai->pluck('nama')->join(', ');

        // pelaksanaan = tanggal mulai - tanggal selesai
        $pelaksanaan = $item->tanggal_mulai . " s/d " . $item->tanggal_selesai;

        return [
            $item->id,
            $item->nomor_spt ?? 'Belum ada',
            \Carbon\Carbon::parse($item->tanggal_spt)->format('d M Y'),
            $item->operator->name ?? '-',
            $item->verifikator->name ?? '-',
            $item->tujuan_spt,
            $personil,
            $pelaksanaan,
            strtoupper($item->status),
        ];
    }

    // 🔥 BOLD header
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]]
        ];
    }
}
