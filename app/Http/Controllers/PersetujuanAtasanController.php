<?php

namespace App\Http\Controllers;

use App\Models\PerjalananDinas;
use Illuminate\Http\Request;

class PersetujuanAtasanController extends Controller
{
    public function index()
    {
        $perjalanans = PerjalananDinas::whereIn('status', ['verifikasi', 'disetujui'])
            ->with(['pegawai','biaya'])
            ->latest()
            ->get();


        return view('admin.persetujuan-atasan', compact('perjalanans'));
    }

    public function update(Request $request, $id)
    {
        $perjalanan = PerjalananDinas::findOrFail($id);

        $aksi = $request->input('aksi_verifikasi'); // tombol yang ditekan
        $catatan = $request->input('catatan_verifikator');

        if ($aksi === 'revisi_operator') {
            $perjalanan->status = 'revisi_operator';
            $perjalanan->catatan_atasan = $catatan;

        } elseif ($aksi === 'tolak') {
            $perjalanan->status = 'ditolak';
            $perjalanan->catatan_atasan = $catatan;

        } elseif ($aksi === 'verifikasi') {
            // === Setujui & terbitkan surat ===
            $perjalanan->status = 'disetujui';
            $perjalanan->catatan_atasan = 'Disetujui dan diterbitkan surat';

        // === Buat No SPT otomatis dengan format 000.1.2.3/SPT/XXX ===
        $prefix = '000.1.2.3/SPT';
        $startNumber = 800;

        // Ambil surat terakhir yang sesuai prefix
        $last = \App\Models\PerjalananDinas::where('nomor_spt', 'like', "$prefix/%")
            ->orderBy('created_at', 'desc')
            ->first();

        if ($last && preg_match('/(\d+)$/', $last->nomor_spt, $matches)) {
            // Ambil angka terakhir dari nomor_spt
            $lastNumber = (int)$matches[1];
            $newNumber = $lastNumber + 1;
        } else {
            // Kalau belum ada data sama sekali, mulai dari 400
            $newNumber = $startNumber;
        }

        // Format nomor baru: 000.1.2.3/SPT/400, 000.1.2.3/SPT/401, dst
        $nomorSPT = "{$prefix}/{$newNumber}";
        $perjalanan->nomor_spt = $nomorSPT;
        }

        $perjalanan->save();

        return back()->with('success', 'Pengajuan berhasil diperbarui.');
    }
}
