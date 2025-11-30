<?php

namespace App\Http\Controllers;

use App\Models\PerjalananDinas;
use App\Models\PerjalananDinasBiayaRiil;
use App\Models\LaporanPerjalananDinas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPerjalananExport;
use App\Models\SbuItem;
use Carbon\Carbon;
use PDF;


class LaporanPerjalananDinasController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');
        $provinsi = SbuItem::select('provinsi_tujuan')->distinct()->get();
        $pegawai = \App\Models\Pegawai::all();

        // Ambil perjalanan dinas yang punya laporan dan status disetujui/selesai
        $query = PerjalananDinas::with(['pegawai', 'biaya', 'laporan', 'biayaRiil.pegawaiTerkait'])
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
        // 🔥 **Filter Status**
            if ($request->filled('status_laporan')) {
                $query->where('status_laporan', $request->status_laporan);
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

        $perPage = $request->get('per_page', 10);

        // Urutkan data terbaru berdasarkan tanggal_spt
        $perjalanans = $query->orderByDesc('tanggal_spt')->paginate($perPage);

        foreach ($perjalanans as $p) {

            // Ambil hanya item biaya riil HOTEL 30%
            $hotel30 = $p->biayaRiil
                ->where('deskripsi_biaya', 'Hotel 30%')
                ->groupBy('pegawai_id');

            $p->hotel30_grouped = $hotel30;

            $lamaMalam = Carbon::parse($p->tanggal_mulai)
                        ->diffInDays(Carbon::parse($p->tanggal_selesai));

            $data = [];

            foreach ($p->pegawai as $pg) {

                $hotel30 = $p->biayaRiil
                    ->where('deskripsi_biaya', 'Hotel 30%')
                    ->groupBy('pegawai_id_terkait'); 

                $totalPenginapan = $p->biaya
                    ->where('pegawai_id_terkait', $pg->id)
                    ->where('sbuItem.kategori_biaya', 'PENGINAPAN')
                    ->sum('subtotal_biaya');

                $hargaSatuan = $p->biaya
                    ->where('pegawai_id_terkait', $pg->id)
                    ->where('sbuItem.kategori_biaya', 'PENGINAPAN')
                    ->first()->harga_satuan ?? 0;

                $data[] = [
                    'pegawai_id' => $pg->id,
                    'nama' => $pg->nama,
                    'harga_satuan' => $hargaSatuan,
                    'total_penginapan' => $totalPenginapan,
                    'jumlah_malam' => $lamaMalam,
                    'uang_30' => $totalPenginapan * 0.30,
                ];
            }

            // tempel ke model agar bisa diakses dari Blade
            $p->data_penginapan = $data;
        }

        return view('laporan.laporan-perjalanan-dinas', compact('perjalanans', 'perPage', 'provinsi', 'pegawai'))
            ->with([
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'search' => $search,
                'status_laporan' => $request->status_laporan,
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

            if (($b['deskripsi'] ?? '') === 'Hotel 30%') {

                // 🔍 Ambil total penginapan pegawai dari tabel biaya estimasi
                $totalPenginapan = $perjalanan->biaya
                    ->where('pegawai_id_terkait', $b['pegawai_id'])
                    ->where('sbuItem.kategori_biaya', 'PENGINAPAN')
                    ->sum('subtotal_biaya');

                // Hitung 30%
                $subtotal = $totalPenginapan * 0.30;
            }

            PerjalananDinasBiayaRiil::create([
                'perjalanan_dinas_id' => $perjalanan->id,
                'pegawai_id' => $b['pegawai_id'] ?? null,
                'deskripsi_biaya' => $b['deskripsi'] ?? '',
                'provinsi_tujuan' => $b['provinsi_tujuan'] ?? '',
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

        return redirect()->route('laporan.index')
            ->with('success' .
            ($request->aksi === 'verifikasi' ? 'dan dikirim untuk verifikasi.' : 'sebagai draft.'));
    }

    public function downloadLaporan($id, $type)
    {
        $perjalanan = PerjalananDinas::with(['pegawai', 'laporan', 'biaya.sbuItem'])->findOrFail($id);

        if ($type === 'pernyataan') {

            $pegawaiList = $perjalanan->pegawai;

            $tanggalMulai = \Carbon\Carbon::parse($perjalanan->tanggal_mulai)->translatedFormat('d F Y');
            $tanggalSelesai = \Carbon\Carbon::parse($perjalanan->tanggal_selesai)->translatedFormat('d F Y');

            $templatePath = public_path('templates/template_pernyataan30%.docx');

            if (!file_exists($templatePath)) {
                return back()->with('error', 'Template pernyataan tidak ditemukan.');
            }

            $template = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

            $pegawaiList = $perjalanan->pegawai->filter(function ($pg) use ($perjalanan) {
                return $perjalanan->biayaRiil
                    ->where('pegawai_id', $pg->id)
                    ->where('deskripsi_biaya', 'Hotel 30%')
                    ->sum('subtotal_biaya') > 0;
            });

            // Jika semua 0, tetap generate dokumen kosong (opsional)
            if ($pegawaiList->isEmpty()) {
                $pegawaiList = collect([]);
            }

            // Clone block sesuai jumlah pegawai
            $template->cloneBlock('pegawai_block', $pegawaiList->count(), true, true);

            // Hitung jumlah malam (tanpa +1)
            $lamaMalam = Carbon::parse($perjalanan->tanggal_mulai)
                                ->diffInDays(Carbon::parse($perjalanan->tanggal_selesai));
            $jumlahMalamText = $lamaMalam . ' (' . $this->terbilang($lamaMalam) . ') Malam';

            $no = 1;
            foreach ($pegawaiList as $index => $pg) {
                $i = $index + 1;

                $biayaPenginapan = $perjalanan->biaya
                    ->where('pegawai_id_terkait', $pg->id)
                    ->where('sbuItem.kategori_biaya', 'PENGINAPAN')
                    ->sum('subtotal_biaya');

                $totalPenginapanFormatted = number_format($biayaPenginapan, 0, ',', '.');
                $uang30Persen = $biayaPenginapan * 0.30;

                $template->setValue("no#{$i}", $no++);
                $template->setValue("nama_pegawai#{$i}", $pg->nama ?? '-');
                $template->setValue("nip#{$i}", $pg->nip ?? '-');
                $template->setValue("jabatan_struktural#{$i}", $pg->jabatan_struktural ?? '-');
                $template->setValue("pangkat_golongan#{$i}", $pg->pangkat_golongan ?? '-');
                $template->setValue("total_penginapan#{$i}", $totalPenginapanFormatted);
                $template->setValue("biaya_30#{$i}", number_format($uang30Persen, 0, ',', '.'));
                $template->setValue("nomor_spt#{$i}", $perjalanan->nomor_spt ?? '-');
                $template->setValue("tanggal_mulai#{$i}", $tanggalMulai ?? '-');
                $template->setValue("tanggal_selesai#{$i}", $tanggalSelesai ?? '-');
                $template->setValue("jumlah_malam#{$i}", $lamaMalam);
                $template->setValue("jumlah_malam_text#{$i}", $jumlahMalamText);
            }

            $fileName = 'PERNYATAAN_30_' . str_replace('/', '-', $perjalanan->nomor_spt);
            $tempPath = storage_path("app/temp_{$fileName}.docx");
            $template->saveAs($tempPath);

            return response()->download($tempPath, $fileName . '.docx')
                ->deleteFileAfterSend(true);
        }

        if ($type === 'sp-mutlak') {

            $pegawaiList = $perjalanan->pegawai;

            $tanggalMulai = \Carbon\Carbon::parse($perjalanan->tanggal_mulai)->translatedFormat('d F Y');
            $tanggalSelesai = \Carbon\Carbon::parse($perjalanan->tanggal_selesai)->translatedFormat('d F Y');

            $templatePath = public_path('templates/template_sp-mutlak.docx');

            if (!file_exists($templatePath)) {
                return back()->with('error', 'Template SP Mutlak tidak ditemukan.');
            }

            $template = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

            // Clone section sesuai jumlah pegawai
            $template->cloneBlock('pegawai_block', $pegawaiList->count(), true, true);

            foreach ($pegawaiList as $index => $pg) {
                $i = $index + 1;

                $template->setValue("nama_pegawai#{$i}", $pg->nama ?? '-');
                $template->setValue("nip#{$i}", $pg->nip ?? '-');
                $template->setValue("jabatan_struktural#{$i}", $pg->jabatan_struktural ?? '-');
                $template->setValue("pangkat_golongan#{$i}", $pg->pangkat_golongan ?? '-');
                $template->setValue("nomor_spt#{$i}", $perjalanan->nomor_spt ?? '-');
                $template->setValue("tanggal_mulai#{$i}", $tanggalMulai ?? '-');
                $template->setValue("tanggal_selesai#{$i}", $tanggalSelesai ?? '-');
            }

            $fileName = 'SURAT_SP_MUTLAK_' . str_replace('/', '-', $perjalanan->nomor_spt);
            $tempPath = storage_path("app/temp_{$fileName}.docx");
            $template->saveAs($tempPath);

            return response()->download($tempPath, $fileName . '.docx')
                ->deleteFileAfterSend(true);
        }


        // Ambil data dasar, maksud & tujuan dari perjalanan dinas
        $dasar_spt = $perjalanan->dasar_spt ?? '-';
        $uraian_spt = $perjalanan->uraian_spt ?? '-';
        $hasil = $perjalanan->laporan->ringkasan_hasil_kegiatan ?? '-';
        $kendala = $perjalanan->laporan->kendala_dihadapi ?? '-';
        $saran = $perjalanan->laporan->saran_tindak_lanjut ?? '-';

        // --- Tentukan template ---
        $templatePath = public_path('templates/template_laporanSPT.docx');

        if (!file_exists($templatePath)) {
            return back()->with('error', 'Template laporan tidak ditemukan.');
        }

        // Load template Word
        $template = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        // Isi placeholder umum
        $template->setValue('dasar_spt', $dasar_spt);
        $template->setValue('uraian_spt', $uraian_spt);
        $template->setValue('ringkasan_hasil_kegiatan', $hasil);
        $template->setValue('kendala_dihadapi', $kendala);
        $template->setValue('saran_tindak_lanjut', $saran);

        // === BAGIAN MULTI PEGAWAI MENGGUNAKAN cloneBlock ===
        $pegawaiList = $perjalanan->pegawai;
        $template->cloneBlock('pegawai_block', $pegawaiList->count(), true, true);

        $no = 1;
        foreach ($pegawaiList as $index => $pg) {
            $i = $index + 1;

            $template->setValue("no#{$i}", $no++);
            $template->setValue("nama_pegawai#{$i}", $pg->nama ?? '-');
            $template->setValue("nip#{$i}", $pg->nip ?? '-');
            $template->setValue("jabatan_struktural#{$i}", $pg->jabatan_struktural ?? '-');
        }
            $template->setValue('nomor_spt', $perjalanan->nomor_spt ?? '-');
            // Ambil tanggal laporan dari relasi laporan
            $tanggalLaporan = optional($perjalanan->laporan)->tanggal_laporan;
            $tanggalFormatted = $tanggalLaporan
                ? \Carbon\Carbon::parse($tanggalLaporan)->translatedFormat('d F Y')
                : '-';
            $template->setValue('tanggal_laporan', $tanggalFormatted);

            // Tambahan: tanggal_mulai, tanggal_selesai, lama_hari
            $tanggalMulai = $perjalanan->tanggal_mulai
                ? \Carbon\Carbon::parse($perjalanan->tanggal_mulai)->translatedFormat('d F Y')
                : '-';

            $tanggalSelesai = $perjalanan->tanggal_selesai
                ? \Carbon\Carbon::parse($perjalanan->tanggal_selesai)->translatedFormat('d F Y')
                : '-';

            $lamaHari = ($perjalanan->tanggal_mulai && $perjalanan->tanggal_selesai)
                ? \Carbon\Carbon::parse($perjalanan->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($perjalanan->tanggal_selesai)) + 1
                : '-';
            $lamaHariText = $lamaHari . ' (' . $this->terbilang($lamaHari) . ')';

            $template->setValue('tanggal_mulai', $tanggalMulai);
            $template->setValue('tanggal_selesai', $tanggalSelesai);
            $template->setValue('lama_hari', $lamaHariText);

        // File output
        $fileName = 'LAPORAN_SPT_' . str_replace('/', '-', $perjalanan->nomor_spt);

        $tempPath = storage_path("app/temp_laporan_{$fileName}.docx");
        $template->saveAs($tempPath);

        // --- Download DOC ---
        if ($type === 'doc') {
            return response()->download($tempPath, $fileName . '.docx')
                ->deleteFileAfterSend(true);
        }

        // --- Convert to PDF ---
        if ($type === 'pdf') {
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($tempPath);
            $htmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
            $htmlPath = storage_path("app/temp_laporan_{$fileName}.html");
            $htmlWriter->save($htmlPath);

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML(file_get_contents($htmlPath))
                ->setPaper('A4');

            return $pdf->download($fileName . '.pdf');
        }

        return back()->with('error', 'Format dokumen tidak dikenali.');
    }

        private function terbilang($angka)
    {
        $angka = abs($angka);
        $baca = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];

        if ($angka < 12) {
            return $baca[$angka];
        } elseif ($angka < 20) {
            return $this->terbilang($angka - 10) . " belas";
        } elseif ($angka < 100) {
            return $this->terbilang($angka / 10) . " puluh " . $this->terbilang($angka % 10);
        } elseif ($angka < 200) {
            return "seratus " . $this->terbilang($angka - 100);
        } elseif ($angka < 1000) {
            return $this->terbilang($angka / 100) . " ratus " . $this->terbilang($angka % 100);
        }

        return $angka; // fallback
    }

    public function updateLaporan(Request $request, $id)
    {
        $laporan = LaporanPerjalananDinas::where('perjalanan_dinas_id', $id)->firstOrFail();
        $perjalanan = PerjalananDinas::findOrFail($id);

        // Update laporan
        $laporan->update([
            'tanggal_laporan' => $request->tanggal_laporan,
            'ringkasan_hasil_kegiatan' => $request->ringkasan_hasil_kegiatan,
            'kendala_dihadapi' => $request->kendala_dihadapi,
            'saran_tindak_lanjut' => $request->saran_tindak_lanjut,
        ]);

        // Update status laporan di tabel perjalanan_dinas
        $perjalanan->update([
            'status_laporan' => 'diproses'
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil diperbarui.');
    }
    
   public function exportExcel(Request $request)
{
    $user = Auth::user();

    // Gunakan QUERY yang sama seperti halaman tabel
    $query = PerjalananDinas::with(['pegawai', 'biaya', 'laporan'])
        ->whereIn('status', ['disetujui', 'selesai']);

    // Filter yang sama seperti halaman tabel
    if ($request->filled('bulan')) {
        $query->whereMonth('tanggal_spt', $request->bulan);
    }
    if ($request->filled('tahun')) {
        $query->whereYear('tanggal_spt', $request->tahun);
    }
    if ($request->filled('status_laporan')) {
        $query->where('status_laporan', $request->status_laporan);
    }

    $data = $query->orderByDesc('tanggal_spt')->get();

    return Excel::download(new LaporanPerjalananExport($data), 'laporan-perjalanan.xlsx');
}
    public function exportPdf()
    {
        $data = PerjalananDinas::with(['pegawai', 'laporan', 'biaya.sbuItem'])
            ->orderByDesc('tanggal_mulai')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.laporan-perjalanan-list', [
            'data' => $data
        ])->setPaper('A4', 'landscape');

        return $pdf->download('laporan-perjalanan-dinas.pdf');
    }

    public function hapusBiaya($id)
    {
        $biaya = \App\Models\PerjalananDinasBiaya::findOrFail($id);

        // Jangan sampai menghapus biaya hotel 30% (kalau ada aturan tertentu)
        if ($biaya->deskripsi_biaya === 'Hotel 30%') {
            return back()->with('error', 'Tidak boleh menghapus biaya Hotel 30% dari estimasi.');
        }

        $biaya->delete();

        return back()->with('success', 'Biaya berhasil dihapus.');
    }

    public function hapusHotel30(Request $request)
    {
        $perjalananId = $request->perjalanan_id;
        $pegawaiId = $request->pegawai_id;

        // Simpan ke session
        session()->push("hapus_hotel30.$perjalananId", $pegawaiId);

        return back()->with('success', 'Item berhasil dihapus dari daftar perhitungan.');
    }
}
