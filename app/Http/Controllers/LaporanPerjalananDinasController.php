<?php

namespace App\Http\Controllers;

use App\Models\PerjalananDinas;
use App\Models\PerjalananDinasBiayaRiil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class LaporanPerjalananDinasController extends Controller
{
    public function index()
    {
        // Ambil data dari perjalanan dinas yang sudah disetujui (dari persetujuan atasan)
        $perjalanans = PerjalananDinas::with(['pegawai', 'biaya'])
            ->whereIn('status', ['disetujui', 'selesai'])
            ->orderBy('tanggal_spt', 'desc')
            ->get();

        return view('laporan.laporan-perjalanan-dinas', compact('perjalanans'));
    }

    public function edit($id)
    {
        $perjalanan = PerjalananDinas::with(['pegawai', 'biaya'])->findOrFail($id);

        return view('laporan.laporan-perjalanan-dinas', compact('perjalanan'));
    }

 public function update(Request $request, $id)
{
    $perjalanan = PerjalananDinas::findOrFail($id);

    $perjalanan->update([
        'tanggal_laporan' => $request->tanggal_laporan,
        'hasil_kegiatan' => $request->hasil_kegiatan,
        'kendala' => $request->kendala,
        'saran' => $request->saran,
    ]);

    // Hapus data biaya riil lama
    $perjalanan->biayaRiil()->delete();

    // Ambil semua data biaya dan file upload
    $dataBiaya = $request->input('biaya', []);
    $files = $request->file('biaya', []); // 🟣 file upload disini

    foreach ($dataBiaya as $index => $b) {
        $pathFile = null;

        // ✅ Cek jika ada file upload di index yang sama
        if (isset($files[$index]['upload_bukti']) && $files[$index]['upload_bukti']->isValid()) {
            $pathFile = $files[$index]['upload_bukti']->store('bukti_riil', 'public');
        }

        $subtotal = ($b['jumlah'] ?? 0) * ($b['harga'] ?? 0);

        PerjalananDinasBiayaRiil::create([
            'perjalanan_dinas_id' => $perjalanan->id,
            'deskripsi_biaya' => $b['deskripsi'] ?? '',
            'jumlah' => $b['jumlah'] ?? 0,
            'satuan' => $b['satuan'] ?? '',
            'harga_satuan' => $b['harga'] ?? 0,
            'nomor_bukti' => $b['bukti'] ?? '',
            'path_bukti_file' => $pathFile, // 🟢 sekarang akan tersimpan
            'keterangan_tambahan' => $b['keterangan'] ?? '',
            'subtotal_biaya' => $subtotal,
        ]);
    }

    // 🔥 Update status laporan
    if ($request->aksi === 'verifikasi') {
        $perjalanan->status_laporan = 'diproses';
        $perjalanan->save();
    }

    return redirect()->back()->with('success', 'Laporan berhasil disimpan ' .
        ($request->aksi === 'verifikasi' ? 'dan dikirim untuk verifikasi.' : 'sebagai draft.'));
}


}

