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
    $search = $request->input('search');

   $query = PerjalananDinas::with([
    'pegawai',
    'biaya.sbuItem',
    'biaya.pegawaiTerkait'
    ])
    ->whereNotIn('status', ['ditolak', 'revisi_operator', 'draft']);

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
              })
              ->orWhereHas('operator', function ($q3) use ($search) {
                  $q3->where('name', 'like', "%{$search}%");
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
            'perPage' => $perPage,
            'search' => $search,
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

   public function update(Request $request, $id)
{
    $perjalanan = PerjalananDinas::findOrFail($id);

    $aksi = $request->input('aksi_verifikasi');
    $catatan = $request->input('catatan_verifikator');
    $role   = auth()->user()->role ?? null;

    switch ($aksi) {
        case 'disetujui':
            $perjalanan->status = 'disetujui_verifikator';
            break;

        case 'tolak':
            $perjalanan->status = 'ditolak';
            break;

        case 'revisi_operator':
            $perjalanan->status = 'revisi_operator';
            break;

        case 'verifikasi':
            $perjalanan->status = 'verifikasi';
            break;
    }

    $perjalanan->verifikator_id = auth()->id();
    $perjalanan->catatan_verifikator = $catatan;
    $perjalanan->save();

    return redirect()->back()->with('success', 'Status laporan berhasil diperbarui.');
  }
}
