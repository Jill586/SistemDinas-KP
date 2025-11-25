<?php

namespace App\Http\Controllers;

use App\Models\PerjalananDinas;
use Illuminate\Http\Request;
use App\Exports\VerifikasiStaffExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class VerifikasiStaffController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->input('search');

        $query = PerjalananDinas::with([
            'pegawai',
            'biaya.sbuItem',
            'biaya.pegawaiTerkait'
        ])
        ->whereNotIn('status', ['revisi_operator']);

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

        return view('admin.verifikasi-staff', compact('perjalanans'))
            ->with([
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'status' => $request->status,
                'perPage' => $perPage,
                'search' => $search,
            ]);
    }

    public function show($id)
    {
        $perjalanan = PerjalananDinas::with([
            'pegawai',
            'biaya.sbuItem',
            'biaya.pegawaiTerkait'
        ])->findOrFail($id);

        return view('admin.verifikasi-staff', compact('perjalanan'));
    }

    public function update(Request $request, $id)
    {
        $perjalanan = PerjalananDinas::findOrFail($id);

        $aksi    = $request->input('aksi_verifikasi');
        $catatan = $request->input('catatan_verifikator');

        if ($aksi === 'verifikasi') {
            $perjalanan->status = 'diproses';
        } elseif ($aksi === 'tolak') {
            $perjalanan->status = 'ditolak';
        } elseif ($aksi === 'revisi_operator') {
            $perjalanan->status = 'revisi_operator';
        }

        $perjalanan->catatan_verifikator = $catatan;
        $perjalanan->save();

        if ($aksi === 'verifikasi') {
            return redirect()->route('verifikasi-staff.index')
                ->with('success', 'Pengajuan telah disetujui dan dikirim ke verifikator pengajuan.');
        }

        if ($request->aksi_verifikasi == 'update_nomor_spt') {
            $pd = PerjalananDinas::findOrFail($id);
            $pd->nomor_spt = $request->nomor_spt;
            $pd->save();

            return back()->with('success', 'Nomor SPT berhasil diperbarui!');
        }

        return redirect()->route('verifikasi-staff.index')->with('success', 'Status pengajuan berhasil diperbarui.');
    }

    public function exportExcel(Request $request)
    {
        $query = PerjalananDinas::with(['pegawai', 'operator'])
            ->whereNotIn('status', ['revisi_operator']);

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
                  ->orWhereHas('pegawai', function ($q2) use ($search) {
                      $q2->where('nama', 'like', "%{$search}%");
                  })
                  ->orWhereHas('operator', function ($q3) use ($search) {
                      $q3->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $data = $query->orderByDesc('tanggal_spt')->get();

        return Excel::download(new VerifikasiStaffExport($data), 'verifikasi-staff.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $query = PerjalananDinas::with(['pegawai', 'operator'])
            ->whereNotIn('status', ['revisi_operator']);

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
                  ->orWhereHas('pegawai', function ($q2) use ($search) {
                      $q2->where('nama', 'like', "%{$search}%");
                  })
                  ->orWhereHas('operator', function ($q3) use ($search) {
                      $q3->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $data = $query->orderByDesc('tanggal_spt')->get();

        $pdf = Pdf::loadView('pdf.verifikasi-staff', compact('data'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('verifikasi-staff.pdf');
    }
}
