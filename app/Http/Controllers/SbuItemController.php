<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SbuItem;
use App\Exports\SbuItemExport;
use App\Imports\SbuItemImport;
use Maatwebsite\Excel\Facades\Excel;

class SbuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $kategoriBiaya = $request->input('kategori_biaya');
        $provinsiTujuan = $request->input('provinsi_tujuan');
        $search = $request->input('search');

        $query = SbuItem::query();

        if (!empty($kategoriBiaya)) {
            $query->where('kategori_biaya', $kategoriBiaya);
        }

        if (!empty($provinsiTujuan)) {
            $query->where('provinsi_tujuan', $provinsiTujuan);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('kategori_biaya', 'LIKE', "%$search%")
                    ->orWhere('uraian_biaya', 'LIKE', "%$search%")
                    ->orWhere('provinsi_tujuan', 'LIKE', "%$search%")
                    ->orWhere('satuan', 'LIKE', "%$search%")
                    ->orWhere('besaran_biaya', 'LIKE', "%$search%");
            });
        }

        if ($perPage === 'ALL') {
            $items = $query->orderBy('id', 'asc')->get();
        } else {
            $items = $query->orderBy('id', 'asc')->paginate((int)$perPage);
        }

        $items->appends([
            'per_page' => $perPage,
            'search' => $search,
            'kategori_biaya' => $kategoriBiaya,
            'provinsi_tujuan' => $provinsiTujuan
        ]);

        $provinsiList = SbuItem::select('provinsi_tujuan')
            ->distinct()
            ->orderBy('provinsi_tujuan', 'asc')
            ->pluck('provinsi_tujuan');

        $satuanList = \App\Models\SBUItem::select('satuan')
            ->distinct()
            ->orderBy('satuan', 'ASC')
            ->pluck('satuan');
        
        $golonganList = \App\Models\SBUItem::select('tingkat_pejabat_atau_golongan')
            ->distinct()
            ->orderBy('tingkat_pejabat_atau_golongan', 'ASC')
            ->pluck('tingkat_pejabat_atau_golongan');

        $tipePerjalananList = \App\Models\SBUItem::select('tipe_perjalanan')
            ->distinct()
            ->orderBy('tipe_perjalanan', 'ASC')
            ->pluck('tipe_perjalanan');

        return view('admin.sbu-item', compact('items', 'perPage', 'provinsiList', 'kategoriBiaya', 'provinsiTujuan', 'search', 'satuanList', 'golonganList', 'tipePerjalananList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kategori_biaya' => 'required',
            'uraian_biaya' => 'required',
            'provinsi_tujuan' => 'required',
            'satuan' => 'required',
            'besaran_biaya' => 'required|numeric',
            'tingkat_pejabat_atau_golongan' => 'required',
            'tipe_perjalanan' => 'required',
        ]);

        SBUItem::create([
            'kategori_biaya' => $request->kategori_biaya,
            'uraian_biaya' => $request->uraian_biaya,
            'provinsi_tujuan' => $request->provinsi_tujuan,
            'satuan' => $request->satuan,
            'tingkat_pejabat_atau_golongan' => $request->tingkat_pejabat_atau_golongan,
            'tipe_perjalanan' => $request->tipe_perjalanan,
            'besaran_biaya' => $request->besaran_biaya,
        ]);

        return redirect()->route('sbu-item.index')->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kategori_biaya' => 'required|string',
            'uraian_biaya' => 'required|string',
            'provinsi_tujuan' => 'required|string',
            'satuan' => 'required|string',
            'besaran_biaya' => 'required|numeric',
        ]);

        $sbuItem = SbuItem::findOrFail($id);
        $sbuItem->update($validated);

        return redirect()->route('sbu-item.index')->with('success', 'Data SBU berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $items = SbuItem::findOrFail($id);
        $items->delete();

        return redirect()->back()->with('success', 'Pegawai berhasil dihapus.');
    }

    public function export()
    {
        return Excel::download(new SbuItemExport, 'sbu_items.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new SbuItemImport, $request->file('file'));

        return back()->with('success', 'Import SBU Item berhasil!');
    }
}
