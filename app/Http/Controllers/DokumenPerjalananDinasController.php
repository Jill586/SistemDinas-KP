<?php

namespace App\Http\Controllers;

use App\Models\PerjalananDinas;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\User;
use App\Exports\DokumenSptSppdExport;
use Maatwebsite\Excel\Facades\Excel;

class DokumenPerjalananDinasController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user(); // ✅ ambil user login
        $search = $request->input('search');

        // Ambil semua perjalanan dinas dengan status "disetujui"
        $query = PerjalananDinas::with('pegawai')
            ->where('status', 'disetujui');

        // 🔒 Filter berdasarkan role
        if (in_array($user->role, ['super_admin', 'verifikator1', 'verifikator2', 'verifikator3'])) {
            // Super Admin, Verifikator 1, Verifikator 2, Verifikator 3 bisa lihat semua data
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
        }
        elseif (in_array($user->role, [
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
            // Role operator, verifikator, dan atasan hanya bisa lihat data sendiri
            $query->where('operator_id', $user->id);
        }

        // 🔍 Filter berdasarkan bulan dan tahun (tanggal_spt)
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereYear('tanggal_spt', $request->tahun)
                  ->whereMonth('tanggal_spt', $request->bulan);
        } elseif ($request->filled('tahun')) {
            $query->whereYear('tanggal_spt', $request->tahun);
        }
        // 🔥 **Filter Status**
            if ($request->filled('status')) {
                $query->where('status', $request->status);
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

        $perPage = $request->get('per_page', 10);

        // Urutkan data terbaru berdasarkan tanggal_spt
        $dokumens = $query->orderByDesc('tanggal_spt')->paginate($perPage);

        // Update otomatis status 'selesai' jadi 'disetujui' (opsional)
        foreach ($dokumens as $dokumen) {
            if ($dokumen->status === 'selesai') {
                $dokumen->status = 'disetujui';
                $dokumen->save();
            }
        }

        return view('dokumen.index', compact('dokumens', 'perPage'))
            ->with([
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'search' => $search,
                'status' => $request->status,
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

            $last = PerjalananDinas::where('nomor_spt', 'like', "$prefix/%")
                ->orderBy('created_at', 'desc')
                ->first();

            if ($last && preg_match('/(\d+)$/', $last->nomor_spt, $matches)) {
                $lastNumber = (int)$matches[1];
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = $startNumber;
            }

            $perjalanan->nomor_spt = "{$prefix}/{$newNumber}";
        }

        $perjalanan->save();

        return back()->with('success', 'Pengajuan berhasil diperbarui.');
    }

    public function download($id, $type, Request $request)
    {
        Carbon::setLocale('id');

        $dokumen = PerjalananDinas::with('pegawai')->findOrFail($id);

        $pegawaiId = $request->input('pegawai_id');
        $pegawai = $pegawaiId
            ? $dokumen->pegawai->where('id', $pegawaiId)->first()
            : $dokumen->pegawai->first();

        if (!$pegawai) {
            return back()->with('error', 'Data pegawai tidak ditemukan.');
        }

        $isSPT = str_contains($type, 'spt');
        $isSPPD = str_contains($type, 'sppd');

        $templatePath = $isSPT
            ? public_path('templates/template_surat.docx')
            : public_path('templates/template_sppd.docx');

        if (!file_exists($templatePath)) {
            return back()->with('error', 'Template tidak ditemukan di: ' . $templatePath);
        }

        $folderTemp = storage_path('app/temp');
        if (!file_exists($folderTemp)) {
            mkdir($folderTemp, 0775, true);
        }

        $template = new TemplateProcessor($templatePath);

        $lamaHari = Carbon::parse($dokumen->tanggal_mulai)
            ->diffInDays(Carbon::parse($dokumen->tanggal_selesai)) + 1;

        $lamaHariText = $lamaHari . ' (' . $this->terbilang($lamaHari) . ')';

        $pengikutList = $dokumen->pegawai->where('id', '!=', $pegawai->id)->pluck('nama')->toArray();
        $pengikutText = !empty($pengikutList) ? implode(', ', $pengikutList) : '-';

        if ($isSPT) {
            $pegawaiList = $dokumen->pegawai;
            $template->cloneBlock('pegawai_block', $pegawaiList->count(), true, true);

            $no = 1;
            foreach ($pegawaiList as $index => $pg) {
                $i = $index + 1;
                $template->setValue("no#{$i}", $no++);
                $template->setValue("nama_pegawai#{$i}", $pg->nama ?? '-');
                $template->setValue("nip#{$i}", $pg->nip ?? '-');
                $template->setValue("golongan#{$i}", $pg->golongan->nama_golongan ?? '-');
                $template->setValue("jabatan#{$i}", $pg->jabatan->nama_jabatan ?? '-');
                $template->setValue("jabatan_struktural#{$i}", $pg->jabatan_struktural ?? '-');
                $template->setValue("pangkat_golongan#{$i}", $pg->pangkat_golongan ?? '-');
            }
            $template->setValue('dasar_spt', $dokumen->dasar_spt ?? '-');
            $template->setValue('uraian_spt', $dokumen->uraian_spt ?? '-');
            $template->setValue('surat_tujuan', $dokumen->tujuan_spt ?? '-');
            $template->setValue('lama_hari', $lamaHariText);
            $template->setValue('tanggal_mulai', Carbon::parse($dokumen->tanggal_mulai)->translatedFormat('d F Y'));
            $template->setValue('tanggal_selesai', Carbon::parse($dokumen->tanggal_selesai)->translatedFormat('d F Y'));
            $template->setValue('tanggal_spt', Carbon::parse($dokumen->tanggal_spt)->translatedFormat('d F Y'));
            $template->setValue('nomor_spt', $dokumen->nomor_spt ?? '-');
        }

        if ($isSPPD) {
            $template->setValue('nama_pegawai', $pegawai->nama ?? '-');
            $template->setValue('golongan', $pegawai->golongan->nama_golongan ?? '-');
            $template->setValue('jabatan', $pegawai->jabatan->nama_jabatan ?? '-');
            $template->setValue('tingkat', $pegawai->tingkat ?? '-');
            $template->setValue('uraian_spt', $dokumen->uraian_spt ?? '-');
            $template->setValue('alat_angkut', $dokumen->alat_angkut ?? '-');
            $template->setValue('tujuan_spt', $dokumen->tujuan_spt ?? '-');
            $template->setValue('provinsi_tujuan_id', $dokumen->provinsi_tujuan_id ?? '-');
            $template->setValue('kecamatan_spt', $dokumen->kecamatan_spt ?? '-');
            $template->setValue('desa_spt', $dokumen->desa_spt ?? '-');
            $template->setValue('kota_tujuan_id', $dokumen->kota_tujuan_id ?? '-');
            $template->setValue('lama_hari', $lamaHariText);
            $template->setValue('tanggal_mulai', Carbon::parse($dokumen->tanggal_mulai)->translatedFormat('d F Y'));
            $template->setValue('tanggal_selesai', Carbon::parse($dokumen->tanggal_selesai)->translatedFormat('d F Y'));
            $template->setValue('tanggal_spt', Carbon::parse($dokumen->tanggal_spt)->translatedFormat('d F Y'));
            $template->setValue('nomor_spt', $dokumen->nomor_spt ?? '-');
            $template->setValue('pengikut', $pengikutText);
            $template->setValue('jabatan_struktural', $pegawai->jabatan_struktural ?? '-');
            $template->setValue('pangkat_golongan', $pegawai->pangkat_golongan ?? '-');

            // Jika tujuan = Siak, hapus bagian tertentu
            if ($dokumen->kota_tujuan_id == 'Siak') {
                $template->setValue('tujuan_spt', '');
                $template->setValue('kota_tujuan_id', '');
                $template->setValue('provinsi_tujuan_id', '');
            }
        }

        $namaFile = strtoupper($isSPT ? 'SPT' : 'SPPD') . '_' .
            str_replace('/', '-', $dokumen->nomor_spt) . '_' .
            str_replace(' ', '_', $pegawai->nama);

        $tempWord = "{$folderTemp}/{$namaFile}.docx";
        $template->saveAs($tempWord);

        if (str_contains($type, 'word')) {
            return response()->download($tempWord, "{$namaFile}.docx")->deleteFileAfterSend(true);
        }

        $phpWord = \PhpOffice\PhpWord\IOFactory::load($tempWord);
        $htmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
        $htmlPath = "{$folderTemp}/{$namaFile}.html";
        $htmlWriter->save($htmlPath);

        $pdf = Pdf::loadHTML(file_get_contents($htmlPath))->setPaper('A4');
        return $pdf->download("{$namaFile}.pdf");
    }

    private function terbilang($angka)
    {
        $angka = abs($angka);
        $baca = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];

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
    public function exportExcel(Request $request)
{
    $user = auth()->user();

    // ambil query yang sama dengan index()
    $query = PerjalananDinas::with('pegawai')
        ->where('status', 'disetujui');

    // Role filter
    if (in_array($user->role, ['super_admin', 'verifikator1', 'verifikator2', 'verifikator3'])) {
        // all allowed
    } elseif ($user->role === 'admin_bidang') {
        $relatedRoles = [
            'umum_kepegawaian','sekretariat','sekretariat_keuangan','sekretariat_perencanaan',
            'bidang_ikps','bidang_tik','verifikator1','verifikator2','verifikator3','atasan'
        ];
        $operatorIds = User::whereIn('role', $relatedRoles)->pluck('id');
        $operatorIds->push($user->id);
        $query->whereIn('operator_id', $operatorIds);
    } else {
        $query->where('operator_id', $user->id);
    }

    // Filter bulan, tahun, status, search
    if ($request->filled('bulan') && $request->filled('tahun')) {
        $query->whereYear('tanggal_spt', $request->tahun)
              ->whereMonth('tanggal_spt', $request->bulan);
    } elseif ($request->filled('tahun')) {
        $query->whereYear('tanggal_spt', $request->tahun);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('nomor_spt', 'like', "%{$search}%")
              ->orWhere('tujuan_spt', 'like', "%{$search}%")
              ->orWhereHas('pegawai', function ($q2) use ($search) {
                  $q2->where('nama', 'like', "%{$search}%");
              });
        });
    }

    $data = $query->orderByDesc('tanggal_spt')->get();

    return Excel::download(new DokumenSptSppdExport($data), 'dokumen-spt-sppd.xlsx');
}
public function exportPdf(Request $request)
{
    $user = auth()->user();

    // Query sama seperti index()
    $query = PerjalananDinas::with('pegawai')
        ->where('status', 'disetujui');

    if (in_array($user->role, ['super_admin', 'verifikator1', 'verifikator2', 'verifikator3'])) {
        //
    } elseif ($user->role === 'admin_bidang') {
        $relatedRoles = [
            'umum_kepegawaian','sekretariat','sekretariat_keuangan','sekretariat_perencanaan',
            'bidang_ikps','bidang_tik','verifikator1','verifikator2','verifikator3','atasan'
        ];
        $operatorIds = User::whereIn('role', $relatedRoles)->pluck('id');
        $operatorIds->push($user->id);
        $query->whereIn('operator_id', $operatorIds);
    } else {
        $query->where('operator_id', $user->id);
    }

    // Filter bulan, tahun, status, search
    if ($request->filled('bulan') && $request->filled('tahun')) {
        $query->whereYear('tanggal_spt', $request->tahun)
              ->whereMonth('tanggal_spt', $request->bulan);
    } elseif ($request->filled('tahun')) {
        $query->whereYear('tanggal_spt', $request->tahun);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('nomor_spt', 'like', "%{$search}%")
              ->orWhere('tujuan_spt', 'like', "%{$search}%")
              ->orWhereHas('pegawai', function ($q2) use ($search) {
                  $q2->where('nama', 'like', "%{$search}%");
              });
        });
    }

    $data = $query->orderByDesc('tanggal_spt')->get();

    // Load view PDF
    $pdf = Pdf::loadView('pdf.export_pdf', compact('data'))
        ->setPaper('A4', 'landscape');

    return $pdf->download('dokumen-spt-sppd.pdf');
}



}
