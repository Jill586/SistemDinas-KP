<?php

namespace App\Http\Controllers;

use App\Models\PerjalananDinas;
use Illuminate\Http\Request;

class PersetujuanAtasanController extends Controller
{
public function index(Request $request)
{
    $perPage  = $request->input('per_page', 10); // default 10
    $search = $request->input('search');

    $query = PerjalananDinas::with(['pegawai', 'biaya'])
        ->whereIn('status', ['verifikasi', 'disetujui']);

    // 🔍 Filter berdasarkan bulan dari tanggal_spt
    if ($request->filled('bulan')) {
        $query->whereMonth('tanggal_spt', $request->bulan);
    }

    // 🔍 Filter berdasarkan tahun dari tanggal_spt
    if ($request->filled('tahun')) {
        $query->whereYear('tanggal_spt', $request->tahun);
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
               })
              ->orWhereHas('verifikator', function ($q3) use ($search) {
                $q3->where('name', 'like', "%{$search}%");
            });
        });
    }

    // Urutkan dari tanggal SPT terbaru
    $perjalanans = $query->orderByDesc('tanggal_spt')->get();

    // 🔽 Pagination berdasarkan perPage
    $perjalanans = $query->orderByDesc('tanggal_spt')->paginate($perPage);

    // Pastikan parameter ikut dalam pagination link
    $perjalanans->appends([
        'per_page' => $perPage,
    ]);

    // Kirim juga nilai bulan & tahun agar tetap tampil di dropdown
    return view('admin.persetujuan-atasan', compact('perjalanans', 'perPage'))
        ->with([
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'search' => $search,
        ]);
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
            $perjalanan->atasan_id = auth()->id(); // ✅ simpan atasan yang menyetujui

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

        // Format nomor baru: 000.1.2.3/SPT/800, 000.1.2.3/SPT/801, dst
        $nomorSPT = "{$prefix}/{$newNumber}";
        $perjalanan->nomor_spt = $nomorSPT;
        }

        $perjalanan->save();

        return back()->with('success', 'Pengajuan berhasil diperbarui.');
    }
}
