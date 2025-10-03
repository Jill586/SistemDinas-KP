<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::paginate(10);
        return view('admin.data-pegawai', compact('pegawai'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'email'     => 'required|email|unique:pegawai',
            'nomor_hp'  => 'required|string|max:20',
            'nip'       => 'required|string|max:50|unique:pegawai',
            'golongan'  => 'required|string|max:255',
            'jabatan'   => 'required|string|max:255',
        ]);
        Pegawai::create($request->all());
        return redirect()->back()->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $request->validate([
            'nama'      => 'required|string|max:255',
            'email' => 'required|email|unique:pegawai,email,'.$id,
            'nomor_hp'  => 'required|string|max:20',
            'nip'   => 'required|string|max:50|unique:pegawai,nip,'.$id,
            'golongan'  => 'required|string|max:255',
            'jabatan'   => 'required|string|max:255',
        ]);
        $pegawai->update($request->all());
        return redirect()->back()->with('success', 'Pegawai berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->delete();
        return redirect()->back()->with('success', 'Pegawai berhasil dihapus.');
    }
}