<?php

namespace App\Http\Controllers;

use App\Models\PerjalananDinas;
use Illuminate\Http\Request;

class VerifikasiStaffController extends Controller
{
    /**
     * Menampilkan daftar pengajuan untuk diverifikasi oleh staff
     */
    public function index(Request $request)
    {
        // ambil jumlah data per halaman (default 10)
        $perPage = $request->get('per_page', 10);

        $query = PerjalananDinas::with([
            'pegawai',
            'biaya.sbuItem',
            'biaya.pegawaiTerkait'
        ])
        ->whereNotIn('status', ['ditolak', 'revisi_operator']);

        // 🔍 Filter bulan dan tahun dari tanggal_spt
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_spt', $request->bulan);
        }

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
                  });
            });
        }

        // urutkan dan paginasi
        $perjalanans = $query->orderByDesc('tanggal_spt')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.verifikasi-staff', compact('perjalanans'))
            ->with([
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'perPage' => $perPage
            ]);
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

        return view('admin.verifikasi-staff', compact('perjalanan'));
    }

public function update(Request $request, $id)
{
    $perjalanan = PerjalananDinas::findOrFail($id);

    $aksi    = $request->input('aksi_verifikasi');
    $catatan = $request->input('catatan_verifikator');

    if ($aksi === 'verifikasi') {
        $perjalanan->status = 'diproses'; // 👈 kirim ke halaman verifikasi pengajuan
    } elseif ($aksi === 'tolak') {
        $perjalanan->status = 'ditolak';
    } elseif ($aksi === 'revisi_operator') {
        $perjalanan->status = 'revisi_operator';
    }

    $perjalanan->verifikator_id = auth()->id();
    $perjalanan->catatan_verifikator = $catatan;
    $perjalanan->save();

    // 🔁 Redirect otomatis ke halaman verifikasi pengajuan kalau disetujui
    if ($aksi === 'verifikasi') {
        return redirect()->route('verifikasi-staff.index')
            ->with('success', 'Pengajuan telah disetujui dan dikirim ke verifikator pengajuan.');
    }

    return redirect()->route('verifikasi-staff.index')->with('success', 'Status pengajuan berhasil diperbarui.');
}
}
