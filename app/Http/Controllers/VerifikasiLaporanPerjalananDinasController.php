<?php

namespace App\Http\Controllers;

use App\Models\LaporanPerjalananDinas;
use App\Models\PerjalananDinas;
use Illuminate\Http\Request;

class VerifikasiLaporanPerjalananDinasController extends Controller
{
    public function index()
    {
        // Ambil perjalanan dinas yang status_laporan = 'diproses'
        $laporans = PerjalananDinas::with(['pegawai', 'biayaRiil', 'laporan'])
            ->whereIn('status_laporan', ['diproses', 'selesai'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('laporan.verifikasi-laporan-perjalanan-dinas', compact('laporans'));
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
