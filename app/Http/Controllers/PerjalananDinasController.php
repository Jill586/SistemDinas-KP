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
public function index(Request $request)
{
    $perPage = $request->get('per_page', 10);

    $query = PerjalananDinas::with(['pegawai', 'biaya'])
        ->orderByDesc('tanggal_spt');

    // 🔍 Search global
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('nomor_spt', 'like', "%{$search}%")
              ->orWhere('tujuan_spt', 'like', "%{$search}%")
              ->orWhere('jenis_kegiatan', 'like', "%{$search}%")
              ->orWhere('status', 'like', "%{$search}%")
              ->orWhereHas('pegawai', function ($sub) use ($search) {
                  $sub->where('nama', 'like', "%{$search}%");
              });
        });
    }

    // 🔍 Filter bulan & tahun
    if ($request->filled('bulan')) {
        $query->whereMonth('tanggal_spt', $request->bulan);
    }
    if ($request->filled('tahun')) {
        $query->whereYear('tanggal_spt', $request->tahun);
    }

    $perjalanans = $query->paginate($perPage)->withQueryString();
    $pegawai = Pegawai::all();

    // 🔄 Jika AJAX (untuk real-time search)
    if ($request->ajax()) {
        $html = '';
        $start = ($perjalanans->currentPage() - 1) * $perjalanans->perPage();

        foreach ($perjalanans as $i => $row) {
            $html .= '
                <tr>
                    <td class="text-center">'.($start + $i + 1).'</td>
                    <td>'.e($row->nomor_spt).'</td>
                    <td>'.e(\Carbon\Carbon::parse($row->tanggal_spt)->format('d M Y')).'</td>
                    <td>'.e($row->tujuan_spt).'</td>
                    <td>'.e($row->pegawai->pluck('nama')->join(', ')).'</td>
                    <td>'.e(\Carbon\Carbon::parse($row->tanggal_mulai)->format('d M Y').' s/d '.\Carbon\Carbon::parse($row->tanggal_selesai)->format('d M Y')).'</td>
                    <td class="text-center">'.
                        ($row->status == "disetujui" ? '<span class="badge bg-label-success">SELESAI</span>' :
                        ($row->status == "ditolak" ? '<span class="badge bg-label-danger">DITOLAK</span>' :
                        ($row->status == "revisi_operator" ? '<span class="badge bg-label-warning">REVISI OPERATOR</span>' :
                        ($row->status == "verifikasi" ? '<span class="badge bg-label-warning">VERIFIKASI</span>' :
                        '<span class="badge bg-label-primary">PROSES</span>')))).'
                    </td>
                    <td class="d-flex gap-2">
                        '.
                        // Tombol Edit (jika revisi_operator)
                        ($row->status == "revisi_operator" ? '
                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit'.$row->id.'">
                            <i class="bx bx-edit"></i>
                        </button>' : '')
                        .'
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalShow'.$row->id.'">
                            <i class="bx bx-show"></i>
                        </button>

                        <form action="'.route('perjalanan-dinas.destroy', $row->id).'" method="POST" onsubmit="return confirm(\'Yakin ingin menghapus data ini?\')" style="display:inline;">
                            '.csrf_field().method_field('DELETE').'
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="bx bx-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Show -->
                <div class="modal fade" id="modalShow'.$row->id.'" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">Detail Perjalanan Dinas</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>No SPT:</strong> '.e($row->nomor_spt).'</p>
                                <p><strong>Tujuan:</strong> '.e($row->tujuan_spt).'</p>
                                <p><strong>Personil:</strong> '.e($row->pegawai->pluck("nama")->join(", ")).'</p>
                                <p><strong>Tanggal:</strong> '.e($row->tanggal_mulai).' s/d '.e($row->tanggal_selesai).'</p>
                                <p><strong>Status:</strong> '.e(strtoupper($row->status)).'</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Edit -->
                '.($row->status == "revisi_operator" ? '
                <div class="modal fade" id="modalEdit'.$row->id.'" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-warning text-dark">
                                <h5 class="modal-title">Edit Perjalanan Dinas</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form action="'.route('perjalanan-dinas.update', $row->id).'" method="POST">
                                    '.csrf_field().method_field('PUT').'
                                    <div class="mb-3">
                                        <label>Tujuan</label>
                                        <input type="text" name="tujuan_spt" class="form-control" value="'.e($row->tujuan_spt).'">
                                    </div>
                                    <div class="mb-3 text-end">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>' : '')
            ;
        }

        if ($perjalanans->isEmpty()) {
            $html = '<tr><td colspan="8" class="text-center">Data tidak ditemukan</td></tr>';
        }

        return response()->json(['html' => $html]);
    }

    return view('admin.perjalanan-dinas', compact('perjalanans', 'pegawai', 'perPage'))
        ->with([
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
        ]);
}

public function create()
    {
        $pegawai = Pegawai::all();

         // Ambil semua perjalanan dinas yang masih aktif hari ini
        $perjalananAktif = PerjalananDinas::whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->get();

        // Ambil semua pegawai yang sedang dinas
        $pegawaiSedangDinas = [];
        foreach ($perjalananAktif as $p) {
            foreach ($p->pegawai as $pg) {
                $pegawaiSedangDinas[] = $pg->id;
            }
        }

        return view('admin.form-pengajuan', compact('pegawai', 'pegawaiSedangDinas'));
    }

    public function store(Request $request)
    {
        $this->validasiForm($request);

        try {
            $data = $request->except(['pegawai_ids', 'bukti_undangan']);

             // 👉 Tambahkan operator_id, verifikator_id, atasan_id otomatis
            $data['operator_id'] = auth()->id();

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
