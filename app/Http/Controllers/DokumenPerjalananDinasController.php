<?php

namespace App\Http\Controllers;

use App\Models\PerjalananDinas;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class DokumenPerjalananDinasController extends Controller
{
     public function index()
    {
        // Ambil data yang sudah diterbitkan (status selesai)
        $dokumens = PerjalananDinas::with('pegawai')
            ->where('status', 'disetujui')
            ->latest()
            ->get();

        // Otomatis ubah status menjadi disetujui
        foreach ($dokumens as $dokumen) {
            if ($dokumen->status === 'selesai') {
                $dokumen->status = 'disetujui';
                $dokumen->save();
            }
        }

        return view('dokumen.index', compact('dokumens'));
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

            // Ambil surat terakhir yang sudah selesai dan sesuai prefix
            $last = PerjalananDinas::where('status', 'disetujui')
                ->where('nomor_spt', 'like', "$prefix/%")
                ->orderBy('created_at', 'desc')
                ->first();

            $newNumber = 1; // default jika belum ada surat
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

    public function download($id, $type, Request $request)
{
    $dokumen = PerjalananDinas::with('pegawai')->findOrFail($id);

    // Pegawai yang dipilih dari dropdown
    $pegawaiId = $request->input('pegawai_id');

    // Tentukan pegawai yang digunakan
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

    $template = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

    // Hitung lama hari
    $lamaHari = \Carbon\Carbon::parse($dokumen->tanggal_mulai)
        ->diffInDays(\Carbon\Carbon::parse($dokumen->tanggal_selesai)) + 1;

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
        $i = $index + 1; // penting: karena cloneBlock pakai #1, #2, dst
        $template->setValue("no#{$i}", $no++);
        $template->setValue("nama_pegawai#{$i}", $pg->nama ?? '-');
        $template->setValue("nip#{$i}", $pg->nip ?? '-');
        $template->setValue("golongan#{$i}", $pg->golongan ?? '-');
        $template->setValue("jabatan#{$i}", $pg->jabatan ?? '-');
    }        
        $template->setValue('uraian_spt', $dokumen->uraian_spt ?? '-');
        $template->setValue('surat_tujuan', $dokumen->tujuan_spt ?? '-');
        $template->setValue('lama_hari', $lamaHari);
        $template->setValue('tanggal_mulai', \Carbon\Carbon::parse($dokumen->tanggal_mulai)->format('d F Y'));
        $template->setValue('tanggal_selesai', \Carbon\Carbon::parse($dokumen->tanggal_selesai)->format('d F Y'));
        $template->setValue('tanggal_spt', \Carbon\Carbon::parse($dokumen->tanggal_spt)->format('d F Y'));
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
        $template->setValue('tanggal_mulai', \Carbon\Carbon::parse($dokumen->tanggal_mulai)->format('d F Y'));
        $template->setValue('tanggal_selesai', \Carbon\Carbon::parse($dokumen->tanggal_selesai)->format('d F Y'));
        $template->setValue('tanggal_spt', \Carbon\Carbon::parse($dokumen->tanggal_spt)->format('d F Y'));
        $template->setValue('nomor_spt', $dokumen->nomor_spt ?? '-');
        $template->setValue('pengikut', $pengikutText);
    }

    // Simpan sementara
    $namaFile = strtoupper($isSPT ? 'SPT' : 'SPPD') . '_' . str_replace('/', '-', $dokumen->nomor_spt) . '_' . str_replace(' ', '_', $pegawai->nama);
    $tempWord = "{$folderTemp}/{$namaFile}.docx";
    $template->saveAs($tempWord);

    // Download Word
    if (str_contains($type, 'word')) {
        return response()->download($tempWord, "{$namaFile}.docx")->deleteFileAfterSend(true);
    }

    // Convert ke PDF
    $phpWord = \PhpOffice\PhpWord\IOFactory::load($tempWord);
    $htmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
    $htmlPath = "{$folderTemp}/{$namaFile}.html";
    $htmlWriter->save($htmlPath);

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML(file_get_contents($htmlPath))->setPaper('A4');
    return $pdf->download("{$namaFile}.pdf");
}


}
