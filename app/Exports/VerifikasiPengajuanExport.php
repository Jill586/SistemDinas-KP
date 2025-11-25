<?php

namespace App\Exports;

use App\Models\PerjalananDinas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class VerifikasiPengajuanExport implements FromCollection, WithHeadings, WithMapping, WithStyles

{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = PerjalananDinas::with(['pegawai', 'operator', 'biaya'])
            ->whereNotIn('status', ['ditolak', 'revisi_operator', 'draft']);

        if ($this->request->filled('bulan')) {
            $query->whereMonth('tanggal_spt', $this->request->bulan);
        }

        if ($this->request->filled('tahun')) {
            $query->whereYear('tanggal_spt', $this->request->tahun);
        }

        return $query->orderBy('tanggal_spt', 'desc')->get();
    }

    // --- BARIS DATA ---
    public function map($item): array
    {
        // Personil: ambil list nama pegawai di relasi
        $personil = $item->pegawai->pluck('nama')->join(', ');

        // Pelaksanaan
        $pelaksanaan = $item->tanggal_mulai . " s/d " . $item->tanggal_selesai;

        // Estimasi Biaya: total semua biaya
        $totalBiaya = $item->biaya->sum('jumlah');

        return [
            $item->nomor_spt ?: 'Belum ada',
            $item->tanggal_spt,
            $item->operator->name ?? '-',
            $item->tujuan_spt,
            $personil,
            $pelaksanaan,
            "Rp " . number_format($totalBiaya, 0, ',', '.'),
            strtoupper($item->status),
        ];
    }

    // --- HEADER KOLOM ---
    public function headings(): array
    {
        return [
            'No. SPT',
            'Tanggal SPT',
            'Operator Pengaju',
            'Tujuan',
            'Personil',
            'Pelaksanaan',
            'Estimasi Biaya',
            'Status'
        ];
    }
     public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]] // Heading bold
        ];
    }
}
