<?php

namespace App\Http\Controllers;

use App\Models\PerjalananDinas;
use Illuminate\Http\Request;

class VerifikasiPengajuanController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->input('search');

        $query = PerjalananDinas::with([
            'pegawai',
            'biaya.sbuItem',
            'biaya.pegawaiTerkait'
        ])->whereNotIn('status', ['ditolak', 'revisi_operator', 'draft']);

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_spt', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_spt', $request->tahun);
        }

        // Searching
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

        $perjalanans = $query->orderByDesc('tanggal_spt')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.verifikasi-pengajuan', compact('perjalanans'))
            ->with([
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'perPage' => $perPage,
                'search' => $search,
            ]);
    }


    public function update(Request $request, $id)
    {
        $perjalanan = PerjalananDinas::findOrFail($id);

        $aksi = $request->input('aksi_verifikasi');
        $catatan = $request->input('catatan_verifikator');

        // ================
        //  UPDATE NOMOR SPT
        // ================
        if ($aksi === 'update_nomor_spt') {

            $request->validate([
                'nomor_spt' => 'required|string'
            ]);

            $perjalanan->nomor_spt = $request->nomor_spt;
            $perjalanan->save();

            return back()->with('success', 'Nomor SPT berhasil diperbarui!');
        }

        // ======================
        //   UPDATE STATUS BIASA
        // ======================
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

        $perjalanan->catatan_verifikator = $catatan;
        $perjalanan->verifikator_id = auth()->id();
        $perjalanan->save();

        return back()->with('success', 'Pengajuan berhasil diperbarui.');
    }
}
