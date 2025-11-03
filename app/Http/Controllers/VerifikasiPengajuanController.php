<?php

namespace App\Http\Controllers;

use App\Models\PerjalananDinas;
use Illuminate\Http\Request;

class VerifikasiPengajuanController extends Controller
{
    /**
     * Menampilkan daftar pengajuan untuk diverifikasi
     */
public function index(Request $request)
{
    // ambil jumlah data per halaman dari input (default 10)
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
        ->withQueryString(); // biar filter tetap nyangkut di URL

    return view('admin.verifikasi-pengajuan', compact('perjalanans'))
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
        $perjalanan->verifikator_id = auth()->id(); // ✅ jika status verifikasi juga simpan id

    $perjalanan->catatan_verifikator = $catatan;
    $perjalanan->save();

    return redirect()->back()->with('success', 'Status laporan berhasil diperbarui.');
}

public function uploadUndangan(Request $request, $id)
{
    $request->validate([
        'bukti_undangan' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);

    $perjalanan = PerjalananDinas::findOrFail($id);

    if ($request->hasFile('bukti_undangan')) {
        // Simpan file ke folder public/undangan
        $file = $request->file('bukti_undangan');
        $path = $file->store('undangan', 'public'); // ✅ hasil: undangan/nama_file.pdf

        // Simpan ke database
        $perjalanan->bukti_undangan = $path;
        $perjalanan->save();
    }

    return redirect()->back()->with('success', 'Bukti undangan berhasil diunggah.');
}


}
