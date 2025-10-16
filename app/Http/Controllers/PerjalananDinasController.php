<?php

namespace App\Http\Controllers;

use App\Models\PerjalananDinas;
use App\Models\Pegawai;
use App\Models\SbuItem;
use App\Models\PerjalananDinasBiaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PerjalananDinasController extends Controller
{
    public function index()
    {
        $perjalanans = PerjalananDinas::with([
            'pegawai.golongan',
            'pegawai.jabatan',
            'biaya.sbuItem',
            'biaya.pegawaiTerkait',
            'biaya.userTerkait',
        ])->latest()->get();

        $pegawai = Pegawai::all();

        return view('admin.perjalanan-dinas', compact('perjalanans', 'pegawai'));
    }

    public function create()
    {
        $pegawai = Pegawai::all();
        return view('admin.form-pengajuan', compact('pegawai'));
    }

    public function store(Request $request)
    {
        $this->validasiForm($request);

        try {
            $data = $request->except(['pegawai_ids', 'bukti_undangan']);

            // Upload file bukti undangan
            if ($request->hasFile('bukti_undangan')) {
                $data['bukti_undangan'] = $request->file('bukti_undangan')->store('undangan', 'public');
            }

            // Simpan perjalanan dinas
            $perjalanan = PerjalananDinas::create($data);

            // Relasi pegawai
            $perjalanan->pegawai()->sync($request->pegawai_ids);

            // Hitung biaya
            $this->hitungEstimasiBiaya($perjalanan, $request);

            return redirect()->route('perjalanan-dinas.create')
                             ->with('success', '✅ Pengajuan berhasil disimpan dengan estimasi biaya!');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', '❌ Data gagal dikirim: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $perjalanan = PerjalananDinas::with('pegawai', 'biaya')->findOrFail($id);
        return view('admin.detail-perjalanan', compact('perjalanan'));
    }

    public function edit($id)
    {
        $perjalanan = PerjalananDinas::with('pegawai')->findOrFail($id);
        $pegawai = Pegawai::all();
        return view('admin.form-pengajuan', compact('perjalanan', 'pegawai'));
    }

    public function update(Request $request, $id)
    {
        $this->validasiForm($request);

        try {
            $perjalanan = PerjalananDinas::findOrFail($id);

            $data = $request->only([
                'tanggal_spt',
                'jenis_spt',
                'jenis_kegiatan',
                'tujuan_spt',
                'provinsi_tujuan_id',
                'kota_tujuan_id',
                'dasar_spt',
                'uraian_spt',
                'alat_angkut',
                'alat_angkut_lainnya',
                'tanggal_mulai',
                'tanggal_selesai',
            ]);

            // Status otomatis PROSES
            $perjalanan->status = 'diproses';

            // Ganti file bukti jika ada
            if ($request->hasFile('bukti_undangan')) {
                if ($perjalanan->bukti_undangan && Storage::disk('public')->exists($perjalanan->bukti_undangan)) {
                    Storage::disk('public')->delete($perjalanan->bukti_undangan);
                }
                $data['bukti_undangan'] = $request->file('bukti_undangan')->store('undangan', 'public');
            }

            $perjalanan->update($data);

            // Update relasi pegawai
            if ($request->filled('pegawai_ids')) {
                $perjalanan->pegawai()->sync($request->pegawai_ids);
            }

            // Hapus dan hitung ulang biaya
            $perjalanan->biaya()->delete();
            $this->hitungEstimasiBiaya($perjalanan, $request);

            return redirect()->route('perjalanan-dinas.index')
                             ->with('success', '✅ Pengajuan berhasil diperbarui! Status otomatis jadi PROSES.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', '❌ Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $perjalanan = PerjalananDinas::findOrFail($id);

            if ($perjalanan->bukti_undangan && Storage::disk('public')->exists($perjalanan->bukti_undangan)) {
                Storage::disk('public')->delete($perjalanan->bukti_undangan);
            }

            $perjalanan->pegawai()->detach();
            $perjalanan->biaya()->delete();
            $perjalanan->delete();

            return redirect()->route('perjalanan-dinas.index')
                             ->with('success', '✅ Data perjalanan dinas berhasil dihapus!');
        } catch (\Throwable $e) {
            return back()->with('error', '❌ Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Validasi form input
     */
    private function validasiForm(Request $request)
    {
        $request->validate([
            'tanggal_spt'      => 'required|date',
            'jenis_spt'        => 'required',
            'jenis_kegiatan'   => 'required|string',
            'tujuan_spt'       => 'required|string',
            'dasar_spt'        => 'required|string',
            'uraian_spt'       => 'required|string',
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'required|date|after_or_equal:tanggal_mulai',
            'pegawai_ids'      => 'required|array',
            'bukti_undangan'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);
    }

    /**
     * Hitung estimasi biaya perjalanan dinas
     */
    private function hitungEstimasiBiaya(PerjalananDinas $perjalanan, Request $request)
    {
        $totalEstimasiKeseluruhan = 0;
        $detailBiayaUntukSimpan   = [];

        $lama_hari = Carbon::parse($request->tanggal_mulai)
                        ->diffInDays(Carbon::parse($request->tanggal_selesai)) + 1;

        $provinsiTujuanUntukSimpan = strtoupper($request->provinsi_tujuan_id ?? 'RIAU');

        $personils = Pegawai::with(['jabatan', 'golongan'])
            ->whereIn('id', $request->pegawai_ids)
            ->get();

        foreach ($personils as $personil) {
            $tingkatSbu = $this->getSbuTingkatUntukPersonil($personil);
            $isDiklat   = strtolower($request->jenis_kegiatan) == 'diklat';

            $kategoriList = ['UANG_HARIAN', 'TRANSPORT', 'PENGINAPAN', 'REPRESENTASI'];

            foreach ($kategoriList as $kategori) {
                $query = SbuItem::where('kategori_biaya', $kategori)
                    ->where('provinsi_tujuan', $provinsiTujuanUntukSimpan);

                if ($isDiklat) {
                    $query->where('uraian_biaya', 'like', '%Diklat%');
                } else {
                    $query->where('uraian_biaya', 'not like', '%Diklat%');
                }

                if ($request->jenis_spt == 'dalam_daerah') {
                    $query->where('tipe_perjalanan', 'DALAM_KABUPATEN_LEBIH_8_JAM');
                } else {
                    $query->where('tipe_perjalanan', 'LUAR_DAERAH_LUAR_KABUPATEN');
                }

                $sbuItems = $query->where(function ($q) use ($tingkatSbu) {
                    $q->where('tingkat_pejabat_atau_golongan', $tingkatSbu)
                      ->orWhere('tingkat_pejabat_atau_golongan', 'Semua');
                })->get();

                foreach ($sbuItems as $sbuItem) {
                    if ($kategori == 'UANG_HARIAN') {
                        $jumlahUnit = $lama_hari;
                    } elseif ($kategori == 'PENGINAPAN') {
                        $jumlahUnit = max($lama_hari - 1, 1);
                    } else {
                        $jumlahUnit = 1;
                    }

                    $subtotal = $sbuItem->besaran_biaya * $jumlahUnit;

                    $detailBiayaUntukSimpan[] = new PerjalananDinasBiaya([
                        'sbu_item_id'             => $sbuItem->id,
                        'pegawai_id_terkait'      => $personil->id,
                        'deskripsi_biaya'         => $sbuItem->uraian_biaya,
                        'jumlah_personil_terkait' => 1,
                        'jumlah_hari_terkait'     => $lama_hari,
                        'jumlah_unit'             => $jumlahUnit,
                        'harga_satuan'            => $sbuItem->besaran_biaya,
                        'subtotal_biaya'          => $subtotal,
                    ]);

                    $totalEstimasiKeseluruhan += $subtotal;
                }
            }
        }

        if (!empty($detailBiayaUntukSimpan)) {
            foreach ($detailBiayaUntukSimpan as $biaya) {
                $biaya->perjalanan_dinas_id = $perjalanan->id;
            }
            $perjalanan->biaya()->saveMany($detailBiayaUntukSimpan);
        }

        $perjalanan->total_estimasi_biaya = $totalEstimasiKeseluruhan;
        $perjalanan->save();
    }

    private function getSbuTingkatUntukPersonil(Pegawai $pegawai)
    {
        if ($pegawai->golongan) {
            return $pegawai->golongan;
        }
        if ($pegawai->jabatan) {
            return $pegawai->jabatan;
        }
        return 'Semua';
    }
}
