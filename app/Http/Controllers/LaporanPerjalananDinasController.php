<?php

namespace App\Http\Controllers;

use App\Models\PerjalananDinas;
use App\Models\PerjalananDinasBiayaRiil;
use App\Models\LaporanPerjalananDinas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class LaporanPerjalananDinasController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua perjalanan dinas dengan status disetujui atau selesai
        $query = PerjalananDinas::with(['pegawai', 'biaya', 'laporan'])
            ->whereIn('status', ['disetujui', 'selesai']);

        // 🔍 Filter berdasarkan bulan (pakai tanggal_spt)
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_spt', $request->bulan);
        }

        // 🔍 Filter berdasarkan tahun (pakai tanggal_spt)
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_spt', $request->tahun);
        }

        $perPage = $request->get('per_page', 10);

        // Urutkan data terbaru berdasarkan tanggal_spt
        $perjalanans = $query->orderByDesc('tanggal_spt')->paginate($perPage);

        return view('laporan.laporan-perjalanan-dinas', compact('perjalanans', 'perPage'))
            ->with([
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
            ]);
    }

 public function update(Request $request, $id)
{
    $perjalanan = PerjalananDinas::findOrFail($id);

    $pegawaiId = $perjalanan->pegawai()->first()->id ?? null;

    // Simpan atau update laporan untuk perjalanan ini
    $laporan = LaporanPerjalananDinas::updateOrCreate(
        ['perjalanan_dinas_id' => $perjalanan->id],
        [
            'pegawai_id' => $pegawaiId,
            'tanggal_laporan' => $request->tanggal_laporan,
            'ringkasan_hasil_kegiatan' => $request->ringkasan_hasil_kegiatan,
            'kendala_dihadapi' => $request->kendala_dihadapi,
            'saran_tindak_lanjut' => $request->saran_tindak_lanjut,
        ]
    );


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