<?php

namespace App\Http\Controllers;

use App\Models\PerjalananDinas;
use App\Models\PerjalananDinasBiayaRiil;
use App\Models\LaporanPerjalananDinas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanPerjalananDinasController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Ambil perjalanan dinas yang punya laporan dan status disetujui/selesai
        $query = PerjalananDinas::with(['pegawai', 'biaya', 'laporan'])
            ->whereIn('status', ['disetujui', 'selesai']);

        // 🔒 Filter berdasarkan role
        if (in_array($user->role, ['super_admin', 'verifikator1', 'verifikator2', 'verifikator3'])) {
            // Super Admin, Verifikator 1, dan Verifikator 2, Verifikator 3 bisa lihat semua data
        } elseif ($user->role === 'admin_bidang') {
            // Admin bidang bisa lihat semua operator bidang + dirinya + verifikator + atasan
            $relatedRoles = [
                'umum_kepegawaian',
                'sekretariat',
                'sekretariat_keuangan',
                'sekretariat_perencanaan',
                'bidang_ikps',
                'bidang_tik',
                'verifikator1',
                'verifikator2',
                'verifikator3',
                'atasan'
            ];

            $operatorIds = User::whereIn('role', $relatedRoles)->pluck('id');
            $operatorIds->push($user->id);

            $query->whereIn('operator_id', $operatorIds);
        } elseif (in_array($user->role, [
            'umum_kepegawaian',
            'sekretariat',
            'sekretariat_keuangan',
            'sekretariat_perencanaan',
            'bidang_ikps',
            'bidang_tik',
            'verifikator1',
            'verifikator2',
            'verifikator3',
            'atasan'
        ])) {
            // Role lain hanya lihat laporan perjalanan yang dia buat
            $query->where('operator_id', $user->id);
        }

        // 🔍 Filter bulan (pakai tanggal_spt)
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_spt', $request->bulan);
        }

        // 🔍 Filter tahun (pakai tanggal_spt)
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

        // Simpan atau update laporan perjalanan
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

        // Hapus biaya riil lama
        $perjalanan->biayaRiil()->delete();

        // Ambil data biaya dan file upload
        $dataBiaya = $request->input('biaya', []);
        $files = $request->file('biaya', []);

        foreach ($dataBiaya as $index => $b) {
            $pathFile = null;

            // Simpan file bukti jika ada
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
                'path_bukti_file' => $pathFile,
                'keterangan_tambahan' => $b['keterangan'] ?? '',
                'subtotal_biaya' => $subtotal,
            ]);
        }

        // 🔥 Update status laporan jika dikirim untuk verifikasi
        if ($request->aksi === 'verifikasi') {
            $perjalanan->status_laporan = 'diproses';
            $perjalanan->save();
        }

        return redirect()->back()->with('success', 'Laporan berhasil disimpan ' .
            ($request->aksi === 'verifikasi' ? 'dan dikirim untuk verifikasi.' : 'sebagai draft.'));
    }
}
