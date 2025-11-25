<?php

namespace App\Http\Controllers;

use App\Models\LaporanPerjalananDinas;
use App\Models\PerjalananDinas;
use Illuminate\Http\Request;
use App\Exports\VerifikasiLaporanExport;
use Maatwebsite\Excel\Facades\Excel;


class VerifikasiLaporanPerjalananDinasController extends Controller
{
public function index(Request $request)
{
    $search = $request->input('search');

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

    $perPage = $request->get('per_page', 10);

    // Urutkan data terbaru berdasarkan tanggal_spt
    $laporans = $query->orderByDesc('tanggal_spt')->paginate($perPage);

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
}
