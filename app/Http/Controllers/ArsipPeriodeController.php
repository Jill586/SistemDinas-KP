<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipPeriode;
use App\Models\PerjalananDinas;
use Illuminate\Support\Facades\DB;


class ArsipPeriodeController extends Controller
{
    public function index()
    {
        $arsip = ArsipPeriode::orderByDesc('id')->get();
        $arsipAktif = ArsipPeriode::where('is_active', 1)->first();

        return view('admin.arsip', compact('arsip', 'arsipAktif'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'batas_anggaran' => 'required|numeric|min:1',
        ]);

        $tahun = now()->year;

        // NONAKTIFKAN PERIODE SEBELUMNYA
        ArsipPeriode::query()->update(['is_active' => 0]);

        // ===============================
        // HITUNG REAL COST
        // ===============================

        // SIAK
        $totalSiak = DB::table('perjalanan_dinas_biaya_riil')
            ->join('perjalanan_dinas', 'perjalanan_dinas.id', '=', 'perjalanan_dinas_biaya_riil.perjalanan_dinas_id')
            ->where('perjalanan_dinas.jenis_spt', 'dalam_daerah')
            ->sum('perjalanan_dinas_biaya_riil.subtotal_biaya');

        // DALAM RIAU (NON SIAK)
        $totalDalamRiau = DB::table('perjalanan_dinas_biaya_riil')
            ->join('perjalanan_dinas', 'perjalanan_dinas.id', '=', 'perjalanan_dinas_biaya_riil.perjalanan_dinas_id')
            ->where('perjalanan_dinas.jenis_spt', 'luar_daerah_dalam_provinsi')
            ->sum('perjalanan_dinas_biaya_riil.subtotal_biaya');

        // LUAR RIAU
        $totalLuarRiau = DB::table('perjalanan_dinas_biaya_riil')
            ->join('perjalanan_dinas', 'perjalanan_dinas.id', '=', 'perjalanan_dinas_biaya_riil.perjalanan_dinas_id')
            ->where('perjalanan_dinas.jenis_spt', 'luar_daerah_luar_provinsi')
            ->sum('perjalanan_dinas_biaya_riil.subtotal_biaya');

       $totalAnggaranReal =
        $totalSiak +
        $totalDalamRiau +
        $totalLuarRiau;

    ArsipPeriode::create([
        'nama_periode'       => $tahun,
        'total_spt'          => PerjalananDinas::count(),

        'total_siak'         => $totalSiak,
        'total_dalam_riau'   => $totalDalamRiau,
        'total_luar_riau'    => $totalLuarRiau,

        'total_anggaran'     => $totalAnggaranReal,   // ✅ REALISASI
        'batas_anggaran'     => $request->batas_anggaran, // ✅ PLAFON
        'is_active'          => 1,
    ]);

        return back()->with('success', 'Periode berhasil diarsipkan & diaktifkan');
    }

    public function destroy($id)
    {
        ArsipPeriode::findOrFail($id)->delete();
        return back()->with('success', 'Arsip berhasil dihapus!');
    }
}
