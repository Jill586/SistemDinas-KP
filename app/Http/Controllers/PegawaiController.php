<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $golongans = \App\Models\Golongan::all();
        $jabatans = \App\Models\Jabatan::all();
        $query = Pegawai::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nomor_hp', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhereHas('golongan', fn($q) => 
                        $q->where('nama_golongan', 'like', "%{$search}%")
                    )
                    ->orWhereHas('jabatan', fn($q) =>
                        $q->where('nama_jabatan', 'like', "%{$search}%")
                    );
            });
        }

        // 🔹 Ambil nilai "show entries" (default 10)
        $perPage = $request->get('per_page', 10);
        $pegawai = $query->paginate($perPage);

        // 🔹 Jika request dari AJAX
        if ($request->ajax()) {
            $html = '';

            // Hitung nomor awal berdasarkan pagination
            $page = $pegawai->currentPage();
            $startNumber = ($pegawai->currentPage() - 1) * $pegawai->perPage();

            foreach ($pegawai as $index => $row) {
                $html .= '
                    <tr>
                        <td class="text-center">' . ($startNumber + $index + 1) . '</td>
                        <td>' . e($row->nama) . '</td>
                        <td>' . e($row->email) . '</td>
                        <td>' . e($row->nomor_hp) . '</td>
                        <td>' . e($row->nip) . '</td>
                        <td>' . e($row->jabatan->nama_jabatan ?? '-') . '</td>
                        <td>
                            ' . e($row->golongan->nama_golongan ?? '-') . '<br>
                            <small class="text-muted">' . e($row->pangkat_golongan ?? '-') . '</small>
                        </td>
                        <td>' . e($row->jabatan_struktural ?? '-') . '</td>
                        <td class="d-flex gap-2">
                            <button type="button" 
                                class="btn btn-warning btn-sm btn-edit"
                                data-id="' . $row->id . '"
                                data-nama="' . e($row->nama) . '"
                                data-email="' . e($row->email) . '"
                                data-nomor_hp="' . e($row->nomor_hp) . '"
                                data-nip="' . e($row->nip) . '"
                                data-golongan_id="' . e($row->golongan_id) . '"
                                data-jabatan_id="' . e($row->jabatan_id) . '"
                                data-jabatan_struktural="' . e($row->jabatan_struktural) . '"
                                data-pangkat_golongan="' . e($row->pangkat_golongan) . '"
                            >
                                <i class="bx bx-edit"></i>
                            </button>

                            <form action="' . route('pegawai.destroy', $row->id) . '" 
                                method="POST" 
                                onsubmit="return confirm(\'Yakin ingin menghapus data ini?\')"
                                class="d-inline">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>';
            }

            if ($pegawai->isEmpty()) {
                $html = '<tr><td colspan="9" class="text-center">Data tidak ditemukan</td></tr>';
            }

            return response()->json(['html' => $html]);
        }

        // 🔹 Jika bukan AJAX (render halaman utama)
        return view('admin.data-pegawai', compact('pegawai', 'perPage', 'golongans', 'jabatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'email'     => 'required|email|unique:pegawai',
            'nomor_hp'  => 'required|string|max:20',
            'nip'       => 'required|string|max:50|unique:pegawai',
            'golongan_id' => 'required',
            'jabatan_id' => 'required',
            'jabatan_struktural' => 'nullable|string|max:150',
            'pangkat_golongan' => 'nullable|string|max:100',
        ]);

        Pegawai::create($request->all());
        return redirect()->back()->with('success', 'Pegawai berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $request->validate([
            'nama'      => 'required|string|max:255',
            'email'     => 'required|email|unique:pegawai,email,' . $id,
            'nomor_hp'  => 'required|string|max:20',
            'nip'       => 'required|string|max:50|unique:pegawai,nip,' . $id,
            'golongan_id' => 'required',
            'jabatan_id' => 'required',
            'jabatan_struktural' => 'nullable|string|max:150',
            'pangkat_golongan' => 'nullable|string|max:100',
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

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx|max:2048'
        ]);

        try {
            // ✅ Simpan file ke storage
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/excel', $filename, 'public');

            // ✅ Load file Excel pakai PhpSpreadsheet
            $spreadsheet = IOFactory::load(storage_path('app/public/' . $filePath));
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            $count = 0;
            foreach (array_slice($rows, 1) as $row) {
                if (empty($row[0]) || empty($row[3])) continue; // skip baris kosong / tanpa NIP

                Pegawai::updateOrCreate(
                    ['nip' => $row[3]], // cari berdasarkan NIP
                    [
                        'nama'      => $row[0],
                        'email'     => $row[1],
                        'nomor_hp'  => $row[2],
                        'golongan'  => $row[4],
                        'jabatan'   => $row[5],
                    ]
                );

                $count++;
            }

            return redirect()->back()->with('success', "Berhasil import {$count} data pegawai. File disimpan di storage/uploads/excel/");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimport file: ' . $e->getMessage());
        }
    }
}
