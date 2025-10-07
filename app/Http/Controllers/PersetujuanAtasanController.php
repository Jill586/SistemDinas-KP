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

            // === Buat No SPT Otomatis ===
            $prefix = '000.1.2.3/SPT';

            // Ambil surat terakhir yang sudah selesai dan sesuai prefix
            $last = PerjalananDinas::where('status', 'disetujui')
                ->where('nomor_spt', 'like', "$prefix/%")
                ->orderBy('created_at', 'desc')
                ->first();

            $newNumber = 1;
            if ($last && $last->nomor_spt) {
                $parts = explode('/', $last->nomor_spt);
                $lastNumber = (int)$parts[2];
                $newNumber = $lastNumber + 1;
            }

            $perjalanan->nomor_spt = $prefix . '/' . $newNumber;
        }

        $perjalanan->save();

        return back()->with('success', 'Pengajuan berhasil diperbarui.');
    }
}
