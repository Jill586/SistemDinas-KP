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
        'total_siak'        => 'required|numeric|min:0',
        'total_dalam_riau'  => 'required|numeric|min:0',
        'total_luar_riau'   => 'required|numeric|min:0',
        'batas_anggaran'    => 'required|numeric|min:1',
    ]);

    $tahun = now()->year;

    // NONAKTIFKAN PERIODE SEBELUMNYA
    ArsipPeriode::query()->update(['is_active' => 0]);

    // TOTAL ANGGARAN = JUMLAH PER KATEGORI
    $totalAnggaran =
        $request->total_siak +
        $request->total_dalam_riau +
        $request->total_luar_riau;

    ArsipPeriode::create([
        'nama_periode'       => $tahun,
        'total_spt'          => PerjalananDinas::count(),

        // Batas per kategori
        'total_siak'         => $request->total_siak,
        'total_dalam_riau'   => $request->total_dalam_riau,
        'total_luar_riau'    => $request->total_luar_riau,

        // Total realisasi/plafon kategori
        'total_anggaran'     => $totalAnggaran,

        // 🔥 PLAFON MANUAL DARI USER
        'batas_anggaran'     => $request->batas_anggaran,

        'is_active'          => 1,
    ]);

    return back()->with('success', 'Periode berhasil diarsipkan');
}
    public function destroy($id)
    {
        ArsipPeriode::findOrFail($id)->delete();
        return back()->with('success', 'Arsip berhasil dihapus!');
    }
}
