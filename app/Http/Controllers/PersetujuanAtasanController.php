<?php

namespace App\Http\Controllers;

use App\Models\PerjalananDinas;
use Illuminate\Http\Request;

class PersetujuanAtasanController extends Controller
{
    public function index()
{
   $perjalanans = PerjalananDinas::where('status', 'verifikasi')
    ->with(['pegawai','biaya'])
    ->latest()
    ->get();

return view('admin.persetujuan-atasan', compact('perjalanans'));
}



    public function setujui(Request $request, $id)
    {
        $perjalanan = PerjalananDinas::findOrFail($id);

        if ($perjalanan->status !== 'disetujui_verifikator') {
            return back()->with('error', 'Pengajuan belum diverifikasi.');
        }

        $perjalanan->status = 'disetujui_atasan';
        $perjalanan->catatan_atasan = $request->input('catatan', 'Disetujui oleh Atasan');
        $perjalanan->save();

        return back()->with('success', 'Pengajuan disetujui atasan.');
    }

    public function tolak(Request $request, $id)
    {
        $perjalanan = PerjalananDinas::findOrFail($id);
        $perjalanan->status = 'ditolak';
        $perjalanan->catatan_atasan = $request->input('catatan', 'Ditolak oleh Atasan');
        $perjalanan->save();

        return back()->with('success', 'Pengajuan ditolak atasan.');
    }

    public function revisi(Request $request, $id)
    {
        $perjalanan = PerjalananDinas::findOrFail($id);
        $perjalanan->status = 'revisi_operator';
        $perjalanan->catatan_atasan = $request->input('catatan', 'Revisi dari Atasan');
        $perjalanan->save();

        return back()->with('success', 'Pengajuan dikembalikan untuk revisi.');
    }
}
