<?php

namespace App\Http\Controllers;

use App\Models\PerjalananDinas;
use Illuminate\Http\Request;
use App\Exports\PersetujuanAtasanExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class PersetujuanAtasanController extends Controller
{
public function index(Request $request)
{
    $perPage  = $request->input('per_page', 10); // default 10
    $search = $request->input('search');

    $query = PerjalananDinas::with(['pegawai', 'biaya'])
        ->whereIn('status', ['verifikasi', 'disetujui']);

    // 🔍 Filter berdasarkan bulan dari tanggal_spt
    if ($request->filled('bulan')) {
        $query->whereMonth('tanggal_spt', $request->bulan);
    }

    // 🔍 Filter berdasarkan tahun dari tanggal_spt
    if ($request->filled('tahun')) {
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
               })
              ->orWhereHas('operator', function ($q3) use ($search) {
                $q3->where('name', 'like', "%{$search}%");
               })
              ->orWhereHas('verifikator', function ($q3) use ($search) {
                $q3->where('name', 'like', "%{$search}%");
            });
        });
    }

    // Urutkan dari tanggal SPT terbaru
    $perjalanans = $query->orderByDesc('tanggal_spt')->get();

    // 🔽 Pagination berdasarkan perPage
    $perjalanans = $query->orderByDesc('tanggal_spt')->paginate($perPage);

    // Pastikan parameter ikut dalam pagination link
    $perjalanans->appends([
        'per_page' => $perPage,
    ]);

    // Kirim juga nilai bulan & tahun agar tetap tampil di dropdown
    return view('admin.persetujuan-atasan', compact('perjalanans', 'perPage'))
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

        $perjalanan->status = 'disetujui';
        $perjalanan->catatan_atasan = 'Disetujui dan diterbitkan surat';
        $perjalanan->atasan_id = auth()->id();

        // ============================================================
        // 🔥 CEK DULU: Jika nomor_spt SUDAH ADA → Jangan generate lagi
        // ============================================================
        if (empty($perjalanan->nomor_spt)) {

            $prefix = '000.1.2.3/SPT';
            $startNumber = 800;

            // Ambil surat terakhir
            $last = PerjalananDinas::where('nomor_spt', 'like', "$prefix/%")
                ->orderBy('created_at', 'desc')
                ->first();

            if ($last && preg_match('/(\d+)$/', $last->nomor_spt, $matches)) {
                $lastNumber = (int)$matches[1];
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = $startNumber;
            }

            // Set nomor baru
            $perjalanan->nomor_spt = "{$prefix}/{$newNumber}";
        }
        // ============================================================
    }


    $perjalanan->save();

    return back()->with('success', 'Pengajuan berhasil diperbarui.');
}
public function exportExcel(Request $request)
{
    return Excel::download(
        new PersetujuanAtasanExport($request),
        'persetujuan-atasan.xlsx'
    );
}
public function exportPdf(Request $request)
{
    // Ambil data sesuai filter
    $query = PerjalananDinas::with(['pegawai', 'biaya'])
        ->whereIn('status', ['verifikasi', 'disetujui']);

    if ($request->filled('bulan')) {
        $query->whereMonth('tanggal_spt', $request->bulan);
    }

    if ($request->filled('tahun')) {
        $query->whereYear('tanggal_spt', $request->tahun);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('nomor_spt', 'like', "%{$search}%")
              ->orWhere('tujuan_spt', 'like', "%{$search}%")
              ->orWhereHas('pegawai', fn($q2) => $q2->where('nama', 'like', "%{$search}%"))
              ->orWhereHas('operator', fn($q3) => $q3->where('name', 'like', "%{$search}%"))
              ->orWhereHas('verifikator', fn($q4) => $q4->where('name', 'like', "%{$search}%"));
        });
    }

    $data = $query->orderByDesc('tanggal_spt')->get();

    // Render PDF
    $pdf = Pdf::loadView('pdf.persetujuan-atasan', compact('data'))
        ->setPaper('A4', 'landscape');

    return $pdf->download('persetujuan-atasan.pdf');
}

}
