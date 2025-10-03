<?php

namespace App\Http\Controllers;

use App\Models\PerjalananDinas;
use Illuminate\Http\Request;

class VerifikasiPengajuanController extends Controller
{
    /**
     * Menampilkan daftar pengajuan untuk diverifikasi
     */
    public function index()
    {
        $perjalanans = PerjalananDinas::with([
            'pegawai',
            'biaya.sbuItem',
            'biaya.pegawaiTerkait'
        ])
        ->whereNotIn('status', ['ditolak', 'revisi_operator'])
        ->latest()
        ->get();

        return view('admin.verifikasi-pengajuan', compact('perjalanans'));
    }

    /**
     * Menampilkan detail pengajuan tertentu
     */
    public function show($id)
    {
        $perjalanan = PerjalananDinas::with([
            'pegawai',
            'biaya.sbuItem',
            'biaya.pegawaiTerkait'
        ])->findOrFail($id);

        return view('admin.verifikasi-pengajuan', compact('perjalanan'));
    }

    /**
     * Update status verifikasi (setujui, tolak, revisi)
     */
   public function update(Request $request, $id)
{
    $perjalanan = PerjalananDinas::findOrFail($id);

    $aksi = $request->input('aksi_verifikasi');
    $catatan = $request->input('catatan_verifikator');
    $role   = auth()->user()->role ?? null;

if ($aksi === 'disetujui') {
    if ($role === 'verifikator') {
        $perjalanan->status = 'disetujui_verifikator';
    } elseif ($role === 'atasan') {
        if ($perjalanan->status === 'disetujui_verifikator') {
            $perjalanan->status = 'disetujui_atasan';
        } else {
            return back()->with('error', 'Pengajuan harus disetujui verifikator dulu.');
        }
    }
    } elseif ($aksi === 'tolak') {
        $perjalanan->status = 'ditolak';
    } elseif ($aksi === 'revisi_operator') {
        $perjalanan->status = 'revisi_operator';
    } elseif ($aksi === 'proses') {
        $perjalanan->status = 'proses';
    } elseif ($aksi === 'verifikasi')
        $perjalanan->status = 'verifikasi';

    $perjalanan->catatan_verifikator = $catatan;
    $perjalanan->save();

    return redirect()->route('verifikasi-pengajuan.index')
                     ->with('success', 'Status pengajuan berhasil diperbarui.');
}

}
