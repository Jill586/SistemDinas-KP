<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
public function index(Request $request)
{
    $query = Pegawai::query();

    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('nomor_hp', 'like', "%{$search}%")
              ->orWhere('nip', 'like', "%{$search}%")
              ->orWhere('golongan', 'like', "%{$search}%")
              ->orWhere('jabatan', 'like', "%{$search}%");
        });
    }

    $pegawai = $query->get();

if ($request->ajax()) {
    $html = '';
    $no = ($request->get('page', 1) - 1) * 10 + 1;
    foreach ($pegawai as $index => $row) {
        $html .= '
            <tr>
                <td>' . ($index + 1) . '</td>
                <td>' . e($row->nama) . '</td>
                <td>' . e($row->email) . '</td>
                <td>' . e($row->nomor_hp) . '</td>
                <td>' . e($row->nip) . '</td>
                <td>' . e($row->golongan) . '</td>
                <td>' . e($row->jabatan) . '</td>
                <td>
                    <form action="' . route('pegawai.destroy', $row->id) . '" method="POST" onsubmit="return confirm(\'Yakin ingin menghapus data ini?\')">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="bx bx-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>';
    }

    if ($pegawai->isEmpty()) {
        $html = '<tr><td colspan="8" class="text-center">Data tidak ditemukan</td></tr>';
    }

    return response()->json(['html' => $html]); // ✅ penting!
}

    // jika bukan AJAX (halaman utama)
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