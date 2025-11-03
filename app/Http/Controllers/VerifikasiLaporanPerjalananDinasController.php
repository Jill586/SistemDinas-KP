<?php

namespace App\Http\Controllers;

use App\Models\LaporanPerjalananDinas;
use App\Models\PerjalananDinas;
use Illuminate\Http\Request;

class VerifikasiLaporanPerjalananDinasController extends Controller
{
public function index(Request $request)
{
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

    $perPage = $request->get('per_page', 10);

    // Urutkan data terbaru berdasarkan tanggal_spt
    $laporans = $query->orderByDesc('tanggal_spt')->paginate($perPage);

    // Kirim data dan nilai filter ke view
    return view('laporan.verifikasi-laporan-perjalanan-dinas', compact('laporans', 'perPage'))
        ->with([
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
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
}
