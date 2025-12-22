<?php

namespace App\Http\Controllers;

use App\Models\LaporanPerjalananDinas;
use App\Models\PerjalananDinas;
use Illuminate\Http\Request;
use App\Exports\VerifikasiLaporanExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class VerifikasiLaporanPerjalananDinasController extends Controller
{
public function index(Request $request)
{
    $search = $request->input('search');
     // gunakan "perPage" supaya konsisten dengan input hidden di Blade
    $perPage = $request->get('perPage', 10);

    // Ambil semua perjalanan dinas dengan status_laporan 'diproses' atau 'selesai'
    $query = PerjalananDinas::with(['pegawai', 'biayaRiil', 'laporan'])
        ->whereIn('status_laporan', ['diproses', 'selesai']);

    // 🔍 Filter berdasarkan bulan dari tanggal_spt (jika ada di request)
    if ($request->filled('bulan')) {
        $query->whereMonth('tanggal_spt', $request->bulan);
    }

    // 🔍 Filter berdasarkan tahun dari tanggal_spt (jika ada di request)
    if ($request->filled('tahun')) {
        $query->whereYear('tanggal_spt', $request->tahun);
    }
     // 🔥 **Filter Status**
            if ($request->filled('status_laporan')) {
                $query->where('status_laporan', $request->status_laporan);
            }

    // 🔍 Jika ada pencarian
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('nomor_spt', 'like', "%{$search}%")
              ->orWhere('tujuan_spt', 'like', "%{$search}%")
              ->orWhereHas('pegawai', function ($q2) use ($search) {
                  $q2->where('nama', 'like', "%{$search}%");
            })
              ->orWhereHas('operator', function ($q3) use ($search) {
                  $q3->where('name', 'like', "%{$search}%");
            });
        });
    }
    // Urutkan data terbaru berdasarkan tanggal_spt
    $laporans = $query->orderByDesc('tanggal_spt')->paginate($perPage);

        foreach ($laporans as $p) {

            $lamaMalam = Carbon::parse($p->tanggal_mulai)
                        ->diffInDays(Carbon::parse($p->tanggal_selesai));

            $data = [];

            foreach ($p->pegawai as $pg) {

                $totalPenginapan = $p->biaya
                    ->where('pegawai_id_terkait', $pg->id)
                    ->where('sbuItem.kategori_biaya', 'PENGINAPAN')
                    ->sum('subtotal_biaya');

                $hargaSatuan = $p->biaya
                    ->where('pegawai_id_terkait', $pg->id)
                    ->where('sbuItem.kategori_biaya', 'PENGINAPAN')
                    ->first()->harga_satuan ?? 0;


                $data[] = [
                    'nama' => $pg->nama,
                    'harga_satuan' => $hargaSatuan,
                    'total_penginapan' => $totalPenginapan,
                    'jumlah_malam' => $lamaMalam,
                    'uang_30' => $totalPenginapan * 0.30,
                ];
            }

            // tempel ke model agar bisa diakses dari Blade
            $p->data_penginapan = $data;
        }
    $perPage = $request->get('per_page', 10);

    // Kirim data dan nilai filter ke view
    return view('laporan.verifikasi-laporan-perjalanan-dinas', compact('laporans', 'perPage'))
        ->with([
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'search' => $search,
            'status_laporan' => $request->status_laporan,
        ]);
}

public function update(Request $request, $id)
{
    $perjalanan = PerjalananDinas::findOrFail($id);
    $laporan = $perjalanan->laporan;

    $request->validate([
        'catatan_verifikator' => 'nullable|string|max:255',
    ]);

    if ($request->aksi_verifikasi === 'revisi_pelapor') {
        $perjalanan->status_laporan = 'belum_dibuat';
    } elseif ($request->aksi_verifikasi === 'tolak') {
        $perjalanan->status_laporan = 'ditolak';
    } elseif ($request->aksi_verifikasi === 'revisi_operator') {
        $perjalanan->status_laporan = 'revisi_operator';
     } elseif ($request->aksi_verifikasi === 'selesai') {
        $perjalanan->status_laporan = 'selesai';
        $laporan->status_bayar = 'sudah_bayar';
    }

    $laporan->catatan_preview = $request->catatan_preview;
    $laporan->save();
    $perjalanan->save();

    return redirect()->back()->with('success', 'Status laporan berhasil diperbarui.');
}

public function exportExcel(Request $request)
{
    return Excel::download(new VerifikasiLaporanExport($request), 'verifikasi-laporan.xlsx');
}
public function exportPdf(Request $request)
{
    // Query sama seperti index(), tapi tanpa paginate
    $query = PerjalananDinas::with(['pegawai', 'laporan'])
        ->whereIn('status_laporan', ['diproses', 'selesai']);

    if ($request->filled('bulan')) {
        $query->whereMonth('tanggal_spt', $request->bulan);
    }

    if ($request->filled('tahun')) {
        $query->whereYear('tanggal_spt', $request->tahun);
    }

    if ($request->filled('status_laporan')) {
        $query->where('status_laporan', $request->status_laporan);
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('nomor_spt', 'like', "%{$search}%")
              ->orWhere('tujuan_spt', 'like', "%{$search}%")
              ->orWhereHas('pegawai', function ($q2) use ($search) {
                  $q2->where('nama', 'like', "%{$search}%");
              });
        });
    }

    $data = $query->orderByDesc('tanggal_spt')->get();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.verifikasi-laporan-perjadin', [
        'data' => $data
    ])->setPaper('A4', 'landscape');

    return $pdf->download('verifikasi-laporan.pdf');
}

}
