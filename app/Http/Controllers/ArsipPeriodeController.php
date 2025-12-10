<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipPeriode;
use App\Models\PerjalananDinas;
use App\Models\PerjalananDinasBiayaRiil;
use App\Models\PerjalananDinasBiaya;

class ArsipPeriodeController extends Controller
{
    public function index()
    {
        $arsip = ArsipPeriode::orderBy('id', 'desc')->get();
        return view('admin.arsip', compact('arsip'));
    }
public function store(Request $request)
{
    // Pakai tahun berjalan
    $request->merge([
        'nama_periode' => date('Y')
    ]);

    $request->validate([
        'nama_periode' => 'required',
        'total_spt' => 'required|integer',
        'total_luar_riau' => 'required|integer',
        'total_dalam_riau' => 'required|integer',
        'total_siak' => 'required|integer',
        'total_anggaran' => 'required|integer',
    ]);

    // Nonaktifkan periode sebelumnya
    ArsipPeriode::where('is_active', 1)->update(['is_active' => 0]);

    // Set periode baru menjadi aktif
    ArsipPeriode::create([
        'nama_periode'        => $request->nama_periode,
        'total_spt'           => $request->total_spt,
        'total_luar_riau'     => $request->total_luar_riau,
        'total_dalam_riau'    => $request->total_dalam_riau,
        'total_siak'          => $request->total_siak,
        'total_anggaran'      => $request->total_anggaran,

        'total_pengeluaran'   => $request->total_pengeluaran ?? 0,
        'total_realcost'      => $request->total_realcost ?? 0,
        'perjalanan_hari_ini' => $request->perjalanan_hari_ini ?? 0,
        'perjalanan_bulan_ini'=> $request->perjalanan_bulan_ini ?? 0,

        'is_active'           => 1, // WAJIB
    ]);

    return redirect()->back()->with('success', 'Periode berhasil diarsipkan!');
}

public function update(Request $request, $id)
{
    $arsip = ArsipPeriode::findOrFail($id);

    $arsip->update([
        'total_spt' => $request->total_spt,
        'total_luar_riau' => $request->total_luar_riau,
        'total_dalam_riau' => $request->total_dalam_riau,
        'total_siak' => $request->total_siak,
        'total_anggaran' => $request->total_anggaran,
    ]);

    return back()->with('success', 'Arsip berhasil diperbarui!');
}

    public function destroy($id)
    {
        ArsipPeriode::findOrFail($id)->delete();
        return back()->with('success', 'Arsip berhasil dihapus!');
    }
}
