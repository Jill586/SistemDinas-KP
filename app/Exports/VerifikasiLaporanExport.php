<?php

namespace App\Exports;

use App\Models\PerjalananDinas;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class VerifikasiLaporanExport implements FromCollection, WithMapping, WithHeadings, WithStyles
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = PerjalananDinas::with([
            'pegawai',
            'biaya.sbuItem',
            'biayaRiil',
            'laporan'
        ])->whereIn('status_laporan', ['diproses', 'selesai']);

        if ($this->request->filled('bulan')) {
            $query->whereMonth('tanggal_spt', $this->request->bulan);
        }

        if ($this->request->filled('tahun')) {
            $query->whereYear('tanggal_spt', $this->request->tahun);
        }

        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_spt', 'like', "%{$search}%")
                    ->orWhere('tujuan_spt', 'like', "%{$search}%")
                    ->orWhereHas('pegawai', function ($q2) use ($search) {
                        $q2->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        return $query->orderByDesc('tanggal_spt')->get();
    }

    public function map($row): array
    {
        // ===============================
        // HITUNG TOTAL DIBAYARKAN (VALID)
        // ===============================
        $totalSemuaPegawai = 0;

        foreach ($row->pegawai as $pg) {
            $totalDibayarPegawai = 0;

            // UANG HARIAN
            $uangHarian = $row->biaya
                ->where('pegawai_id_terkait', $pg->id)
                ->filter(fn ($b) =>
                    $b->sbuItem &&
                    $b->sbuItem->kategori_biaya === 'UANG_HARIAN'
                )
                ->first();

            $totalDibayarPegawai += $uangHarian->subtotal_biaya ?? 0;

            // BIAYA RIIL
            foreach ($row->biayaRiil->where('pegawai_id', $pg->id) as $riil) {
                $totalDibayarPegawai += $riil->subtotal_biaya;
            }

            $totalSemuaPegawai += $totalDibayarPegawai;
        }

        return [
            // 1
            $row->nomor_spt ?? '-',

            // 2
            $row->tanggal_spt
                ? Carbon::parse($row->tanggal_spt)->format('d M Y')
                : '-',

            // 3
            $row->tujuan_spt ?? '-',

            // 4
            $row->pegawai->pluck('nama')->implode(', ') ?: '-',

            // 5 (Uraian)
            $row->uraian ?? '-',

            // 6
            ucfirst($row->status_laporan) ?? '-',

            // 7
            optional($row->laporan)->status_bayar ?? '-',

            // 8
            $totalSemuaPegawai,

            // 9
            $row->created_at
                ? Carbon::parse($row->created_at)->translatedFormat('d F Y')
                : '-',
        ];
    }

    public function headings(): array
    {
        return [
            'Nomor SPT',
            'Tanggal SPT',
            'Tujuan',
            'Nama Pegawai',
            'Uraian',
            'Status Laporan',
            'Status Bayar',
            'Total Dibayar',
            'Tanggal Dibuat'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Header row bold
        ];
    }
}
