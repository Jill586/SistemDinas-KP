<?php

namespace App\Http\Controllers;

use App\Models\PerjalananDinas;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class DokumenPerjalananDinasController extends Controller
{
public function index(Request $request)
{    
    // Ambil semua perjalanan dinas dengan status "disetujui"
    $query = PerjalananDinas::with('pegawai')
        ->where('status', 'disetujui');

    // 🔍 Filter berdasarkan bulan dan tahun (dari tanggal_spt)
    if ($request->filled('bulan') && $request->filled('tahun')) {
        $query->whereYear('tanggal_spt', $request->tahun)
              ->whereMonth('tanggal_spt', $request->bulan);
    } 
    elseif ($request->filled('tahun')) {
        $query->whereYear('tanggal_spt', $request->tahun);
    }

    $perPage = $request->get('per_page', 10);

    // Urutkan data terbaru berdasarkan tanggal_spt
    $dokumens = $query->orderByDesc('tanggal_spt')->paginate($perPage);
    
    // Update otomatis status 'selesai' jadi 'disetujui'
    foreach ($dokumens as $dokumen) {
        if ($dokumen->status === 'selesai') {
            $dokumen->status = 'disetujui';
            $dokumen->save();
        }
    }

    // Kirim data ke view + tetap bawa parameter bulan & tahun
    return view('dokumen.index', compact('dokumens', 'perPage'))
        ->with([
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
        ]);
}

    public function update(Request $request, $id)
    {
        $perjalanan = PerjalananDinas::findOrFail($id);
        $aksi = $request->input('aksi_verifikasi');
        $catatan = $request->input('catatan_verifikator');

        if ($aksi === 'revisi_operator') {
            $perjalanan->status = 'revisi_operator';
            $perjalanan->catatan_atasan = $catatan;
        } elseif ($aksi === 'tolak') {
            $perjalanan->status = 'ditolak';
            $perjalanan->catatan_atasan = $catatan;
        } elseif ($aksi === 'verifikasi') {
            // === Disetujui & diterbitkan surat ===
            $perjalanan->status = 'disetujui';
            $perjalanan->catatan_atasan = 'Disetujui dan diterbitkan surat';

            // === Buat No SPT otomatis dengan format 000.1.2.3/SPT/XXX ===
            $prefix = '000.1.2.3/SPT';
            $startNumber = 800;

            // Ambil surat terakhir yang sesuai prefix
            $last = PerjalananDinas::where('nomor_spt', 'like', "$prefix/%")
                ->orderBy('created_at', 'desc')
                ->first();

            if ($last && preg_match('/(\d+)$/', $last->nomor_spt, $matches)) {
                $lastNumber = (int)$matches[1];
                $newNumber = $lastNumber + 1;
            } else {
                // Jika belum ada data sama sekali, mulai dari 400
                $newNumber = $startNumber;
            }

            // Format nomor baru: 000.1.2.3/SPT/400, dst
            $perjalanan->nomor_spt = "{$prefix}/{$newNumber}";
        }

        $perjalanan->save();

        return back()->with('success', 'Pengajuan berhasil diperbarui.');
    }

    public function download($id, $type, Request $request)
    {
        Carbon::setLocale('id'); // pastikan tanggal dalam bahasa Indonesia

        $dokumen = PerjalananDinas::with('pegawai')->findOrFail($id);

        // Pegawai yang dipilih dari dropdown
        $pegawaiId = $request->input('pegawai_id');
        $pegawai = $pegawaiId
            ? $dokumen->pegawai->where('id', $pegawaiId)->first()
            : $dokumen->pegawai->first();

        if (!$pegawai) {
            return back()->with('error', 'Data pegawai tidak ditemukan.');
        }

        // Tentukan template berdasarkan jenis dokumen
        $isSPT = str_contains($type, 'spt');
        $isSPPD = str_contains($type, 'sppd');

        $templatePath = $isSPT
            ? public_path('templates/template_surat.docx')
            : public_path('templates/template_sppd.docx');

        if (!file_exists($templatePath)) {
            return back()->with('error', 'Template tidak ditemukan di: ' . $templatePath);
        }

        // Folder temp
        $folderTemp = storage_path('app/temp');
        if (!file_exists($folderTemp)) {
            mkdir($folderTemp, 0775, true);
        }

        $template = new TemplateProcessor($templatePath);

        // Hitung lama hari perjalanan
        $lamaHari = Carbon::parse($dokumen->tanggal_mulai)
            ->diffInDays(Carbon::parse($dokumen->tanggal_selesai)) + 1;

        // Pengikut = semua pegawai kecuali yang dipilih
        $pengikutList = $dokumen->pegawai->where('id', '!=', $pegawai->id)->pluck('nama')->toArray();
        $pengikutText = !empty($pengikutList) ? implode(', ', $pengikutList) : '-';

        // === Isi Template ===
        if ($isSPT) {
            // ==== bagian personil ====
            $pegawaiList = $dokumen->pegawai;
            $template->cloneBlock('pegawai_block', $pegawaiList->count(), true, true);

            $no = 1;
            foreach ($pegawaiList as $index => $pg) {
                $i = $index + 1; // penting untuk #1, #2, dst
                $template->setValue("no#{$i}", $no++);
                $template->setValue("nama_pegawai#{$i}", $pg->nama ?? '-');
                $template->setValue("nip#{$i}", $pg->nip ?? '-');
                $template->setValue("golongan#{$i}", $pg->golongan ?? '-');
                $template->setValue("jabatan#{$i}", $pg->jabatan ?? '-');
            }
            $template->setValue('dasar_spt', $dokumen->dasar_spt ?? '-');
            $template->setValue('uraian_spt', $dokumen->uraian_spt ?? '-');
            $template->setValue('surat_tujuan', $dokumen->tujuan_spt ?? '-');
            $template->setValue('lama_hari', $lamaHari);
            $template->setValue('tanggal_mulai', Carbon::parse($dokumen->tanggal_mulai)->translatedFormat('d F Y'));
            $template->setValue('tanggal_selesai', Carbon::parse($dokumen->tanggal_selesai)->translatedFormat('d F Y'));
            $template->setValue('tanggal_spt', Carbon::parse($dokumen->tanggal_spt)->translatedFormat('d F Y'));
            $template->setValue('nomor_spt', $dokumen->nomor_spt ?? '-');
        }

        if ($isSPPD) {
            $template->setValue('nama_pegawai', $pegawai->nama ?? '-');
            $template->setValue('golongan', $pegawai->golongan ?? '-');
            $template->setValue('jabatan', $pegawai->jabatan ?? '-');
            $template->setValue('tingkat', $pegawai->tingkat ?? '-');
            $template->setValue('uraian_spt', $dokumen->uraian_spt ?? '-');
            $template->setValue('alat_angkut', $dokumen->alat_angkut ?? '-');
            $template->setValue('tujuan_spt', $dokumen->tujuan_spt ?? '-');
            $template->setValue('provinsi_tujuan_id', $dokumen->provinsi_tujuan_id ?? '-');
            $template->setValue('kota_tujuan_id', $dokumen->kota_tujuan_id ?? '-');
            $template->setValue('lama_hari', $lamaHari);
            $template->setValue('tanggal_mulai', Carbon::parse($dokumen->tanggal_mulai)->translatedFormat('d F Y'));
            $template->setValue('tanggal_selesai', Carbon::parse($dokumen->tanggal_selesai)->translatedFormat('d F Y'));
            $template->setValue('tanggal_spt', Carbon::parse($dokumen->tanggal_spt)->translatedFormat('d F Y'));
            $template->setValue('nomor_spt', $dokumen->nomor_spt ?? '-');
            $template->setValue('pengikut', $pengikutText);
        }

        // Simpan sementara hasil template
        $namaFile = strtoupper($isSPT ? 'SPT' : 'SPPD') . '_' .
            str_replace('/', '-', $dokumen->nomor_spt) . '_' .
            str_replace(' ', '_', $pegawai->nama);

        $tempWord = "{$folderTemp}/{$namaFile}.docx";
        $template->saveAs($tempWord);

        // === Download Word ===
        if (str_contains($type, 'word')) {
            return response()->download($tempWord, "{$namaFile}.docx")->deleteFileAfterSend(true);
        }

        // === Convert ke PDF ===
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($tempWord);
        $htmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
        $htmlPath = "{$folderTemp}/{$namaFile}.html";
        $htmlWriter->save($htmlPath);

        $pdf = Pdf::loadHTML(file_get_contents($htmlPath))->setPaper('A4');
        return $pdf->download("{$namaFile}.pdf");
    }
}
