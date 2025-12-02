<?php

namespace App\Http\Controllers;

use App\Models\PerjalananDinas;
use App\Models\Pegawai;
use App\Models\SbuItem;
use App\Models\PerjalananDinasBiaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;
use App\Exports\PerjalananDinasExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;



class PerjalananDinasController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

         // 🔍 Ambil input filter
        $search = $request->input('search');
        $perPage = $request->get('per_page', 10);
        $data = PerjalananDinas::with('pegawai')->latest()->get();

        $query = PerjalananDinas::with(['pegawai', 'biaya']) // <— tambahkan eager load relasi pegawai & biaya
            ->orderBy('id', 'asc'); // urutan default berdasarkan ID

        // 🔒 Filter berdasarkan role
        if (in_array($user->role, ['super_admin', 'verifikator1'])) {
            // Super Admin bisa lihat semua data

        } elseif ($user->role === 'admin_bidang') {
            // Admin bidang bisa lihat semua operator bidang + dirinya sendiri + verifikator + atasan
            $relatedRoles = [
                'umum_kepegawaian',
                'sekretariat',
                'sekretariat_keuangan',
                'sekretariat_perencanaan',
                'bidang_ikps',
                'bidang_tik',
                'verifikator1',
                'verifikator2',
                'verifikator3',
                'atasan'
            ];

            $operatorIds = User::whereIn('role', $relatedRoles)->pluck('id');

            // Tambahkan ID admin bidang sendiri juga
            $operatorIds->push($user->id);

            $query->whereIn('operator_id', $operatorIds);

        } elseif (in_array($user->role, [
            'umum_kepegawaian',
            'sekretariat',
            'sekretariat_keuangan',
            'sekretariat_perencanaan',
            'bidang_ikps',
            'bidang_tik',
            'verifikator1',
            'verifikator2',
            'verifikator3',
            'atasan'
        ])) {
            // Operator bidang, verifikator, dan atasan hanya bisa lihat data sendiri
            $query->where('operator_id', $user->id);
        }

        // 🗓️ Filter bulan & tahun (kalau ada)
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_spt', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_spt', $request->tahun);
        }
        // 🟩 Filter berdasarkan status (jika diisi)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }


        // 🔍 Filter pencarian manual
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('tujuan_spt', 'like', "%{$search}%")
                ->orWhere('jenis_spt', 'like', "%{$search}%")
                ->orWhere('jenis_kegiatan', 'like', "%{$search}%")
                ->orWhereHas('pegawai', function ($pegawaiQuery) use ($search) {
                    $pegawaiQuery->where('nama', 'like', "%{$search}%");
                });
            });
        }

        // 📄 Pagination
        $perPage = $request->get('per_page', 10);
        $perjalanans = $query->paginate($perPage);

        // ✅ Tambahkan ini supaya $pegawai dikenali di view
        $pegawai = Pegawai::all();

        return view('admin.perjalanan-dinas', compact('perjalanans', 'perPage', 'pegawai'))
            ->with([
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'search' => $search,
            ]);
    }
   public function create()
{
    $pegawai = Pegawai::all();

    // Ambil hanya perjalanan dinas yang masih aktif DAN statusnya bukan ditolak / selesai
    $perjalananAktif = PerjalananDinas::whereIn('status', ['Disetujui', 'Berjalan'])
        ->whereDate('tanggal_mulai', '<=', now())
        ->whereDate('tanggal_selesai', '>=', now())
        ->get();

    // Ambil semua pegawai yang sedang dinas aktif
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

        // Jika jenis SPT dalam daerah, otomatis isi provinsi dan kota
        if ($request->jenis_spt === 'dalam_daerah') {
            $data['provinsi_tujuan_id'] = 'RIAU';
            $data['kota_tujuan_id'] = 'Siak';
        }

        // Tambahkan operator_id, verifikator_id, atasan_id otomatis
        $data['operator_id'] = auth()->id();

        // Set status default menjadi "draft"
        $data['status'] = 'draft';

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
           if ($request->has('pegawai_ids')) {
           $perjalanan->pegawai()->sync($request->pegawai_ids ?? []);
           $perjalanan->load('pegawai');

           }

            // Hapus dan hitung ulang biaya
            $perjalanan->biaya()->delete();
            $this->hitungEstimasiBiaya($perjalanan, $request);

            return redirect()->route('perjalanan-dinas.create')
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
    private function hitungEstimasiBiaya($perjalanan, $request)
    {
        $pegawaiIds = $request->pegawai_ids ?? [];
        $totalEstimasiKeseluruhan = 0;

        // Hitung lama hari
        $jumlahHari = Carbon::parse($request->tanggal_mulai)
            ->diffInDays(Carbon::parse($request->tanggal_selesai)) + 1;

        // Tentukan kategori biaya berdasarkan jenis SPT
        if (strtolower($perjalanan->jenis_spt) === 'dalam_daerah') {
            // Jika dalam daerah → hanya uang harian
            $kategoriList = ['UANG_HARIAN'];
        } else {
            // Selain itu
            $kategoriList = ['UANG_HARIAN', 'TRANSPORTASI_DARAT', 'PENGINAPAN', 'TRANSPORTASI_DARAT_TAKSI'];
        }

        // Loop setiap pegawai
        foreach ($pegawaiIds as $pegawaiId) {
            $pegawai = Pegawai::find($pegawaiId);
            if (!$pegawai) continue;

            foreach ($kategoriList as $kategori) {

                // Ambil item SBU yang cocok
                $sbuItemQuery = SbuItem::where('kategori_biaya', $kategori)
                    ->where('provinsi_tujuan', $perjalanan->provinsi_tujuan_id);

                if ($kategori !== 'UANG_HARIAN') {
                    // Untuk transportasi, sesuaikan kota
                    $sbuItemQuery->where(function ($q) use ($perjalanan) {
                        $q->where('kota_tujuan', $perjalanan->kota_tujuan_id)
                        ->orWhereNull('kota_tujuan');
                    });
                }

                // Filter khusus Taksi berdasarkan provinsi tujuan
                if ($kategori === 'TRANSPORTASI_DARAT_TAKSI') {
                    // Untuk transportasi, sesuaikan kota
                    $sbuItemQuery->where(function ($q) use ($perjalanan) {
                        $q->where('kota_tujuan', $perjalanan->kota_tujuan_id)
                        ->orWhereNull('kota_tujuan');
                    });
                }

                // Filter khusus Taksi berdasarkan provinsi tujuan
                if ($kategori === 'TRANSPORTASI_DARAT') {
                    // Untuk transportasi, sesuaikan kota
                    $sbuItemQuery->where(function ($q) use ($perjalanan) {
                        $q->where('kota_tujuan', $perjalanan->kota_tujuan_id)
                        ->orWhereNull('kota_tujuan');
                    });
                }
                
                if ($kategori === 'PENGINAPAN') {
                    $golongan = $pegawai->golongan->nama_golongan ?? null;
                    $eselon = $pegawai->jabatan->eselon ?? null;
                    $romanLevel = null;

                    // Ekstrak angka romawi dari golongan
                    if ($golongan) {
                        if (preg_match('/(III|IV|II|V|I)/', $golongan, $matches)) {
                            $romanLevel = $matches[1];
                        }
                    }

                    // Tentukan value pencarian berdasarkan eselon jika tersedia
                    if ($eselon) {
                        $searchLevel = 'ESELON_' . strtoupper($eselon);
                    } elseif ($romanLevel) {
                        $searchLevel = 'GOL_' . strtoupper($romanLevel);
                    } else {
                        $searchLevel = null;
                    }

                    // Apply filter ke query SBU
                    if ($searchLevel) {
                        $sbuItemQuery->where('tingkat_pejabat_atau_golongan', 'like', "%$searchLevel%");
                    }
                }

                $sbuItem = $sbuItemQuery->first();

                // Jika dalam daerah → pakai uraian khusus
                if (strtolower($perjalanan->jenis_spt) === 'dalam_daerah' && $kategori === 'UANG_HARIAN') {
                    $sbuItem = SbuItem::where('kategori_biaya', 'UANG_HARIAN')
                        ->where('uraian_biaya', 'like', '%Dalam Kabupaten Riau > 8 Jam%')
                        ->first();
                }

                if (!$sbuItem) continue;

                // Hitung jumlah unit dan subtotal
                if ($kategori === 'UANG_HARIAN') {
                    $jumlahUnit = $jumlahHari; // contoh: 6 hari
                } elseif ($kategori === 'PENGINAPAN') {
                    $jumlahUnit = max($jumlahHari - 1, 1); // contoh: 6 hari → 5 malam
                } else {
                    $jumlahUnit = 1; // transportasi
                }
                $hargaSatuan = $sbuItem->besaran_biaya ?? 0;
                $subtotal = $hargaSatuan * $jumlahUnit;

                // Simpan ke tabel perjalanan_dinas_biaya
                PerjalananDinasBiaya::create([
                    'perjalanan_dinas_id'      => $perjalanan->id,
                    'sbu_item_id'              => $sbuItem->id,
                    'pegawai_id_terkait'       => $pegawai->id,
                    'deskripsi_biaya'          => $sbuItem->uraian_biaya,
                    'jumlah_personil_terkait'  => 1,
                    'jumlah_hari_terkait'      => $jumlahHari,
                    'jumlah_unit'              => $jumlahUnit,
                    'harga_satuan'             => $hargaSatuan,
                    'subtotal_biaya'           => $subtotal,
                    'keterangan_tambahan'      => 'Estimasi otomatis kategori ' . $kategori,
                ]);

                $totalEstimasiKeseluruhan += $subtotal;
            }
        }

        // Update total estimasi biaya di tabel perjalanan dinas
        $perjalanan->update(['total_estimasi_biaya' => $totalEstimasiKeseluruhan]);
    }

    private function getSbuTingkatUntukPersonil(Pegawai $pegawai)
    {
        if ($pegawai->golongan) return $pegawai->golongan;
        if ($pegawai->jabatan) return $pegawai->jabatan;
        return 'Semua';
    }

        public function uploadTtd(Request $request, $id)
    {
        $request->validate([
            'spt_ttd' => 'required|file|mimes:pdf,jpg,jpeg,png|max:20480',
        ]);

        $perjalanan = PerjalananDinas::findOrFail($id);

        if ($request->hasFile('spt_ttd')) {
            $file = $request->file('spt_ttd');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/spt_ttd'), $filename);
            $perjalanan->spt_ttd = $filename;
            $perjalanan->save();
        }

        return back()->with('success', 'SPT TTD berhasil diupload!');
    }

    public function downloadTtd($id)
    {
        $data = PerjalananDinas::findOrFail($id);

        if (!$data->spt_ttd) {
            abort(404, 'File tidak ditemukan.');
        }

        $filepath = public_path('uploads/spt_ttd/' . $data->spt_ttd);

        if (!file_exists($filepath)) {
            abort(404, 'File tidak ada di server.');
        }

        return response()->download($filepath);
    }
   public function exportExcel(Request $request)
{
    // Copy logika filter dari index()
    $user = Auth::user();
    $search = $request->input('search');

    $query = PerjalananDinas::with(['pegawai', 'biaya'])
        ->orderBy('id', 'asc');

    // Filter role (copas dari index)
    if (in_array($user->role, ['super_admin', 'verifikator1'])) {
        //
    } elseif ($user->role === 'admin_bidang') {
        $relatedRoles = [
            'umum_kepegawaian','sekretariat','sekretariat_keuangan',
            'sekretariat_perencanaan','bidang_ikps','bidang_tik',
            'verifikator1','verifikator2','verifikator3','atasan'
        ];

        $operatorIds = User::whereIn('role', $relatedRoles)->pluck('id');
        $operatorIds->push($user->id);

        $query->whereIn('operator_id', $operatorIds);
    } else {
        $query->where('operator_id', $user->id);
    }

    // Filter bulan, tahun, status, search (sama seperti index)
    if ($request->filled('bulan')) {
        $query->whereMonth('tanggal_spt', $request->bulan);
    }

    if ($request->filled('tahun')) {
        $query->whereYear('tanggal_spt', $request->tahun);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('tujuan_spt', 'like', "%{$search}%")
              ->orWhere('jenis_spt', 'like', "%{$search}%")
              ->orWhere('jenis_kegiatan', 'like', "%{$search}%")
              ->orWhereHas('pegawai', function ($pegawai) use ($search) {
                  $pegawai->where('nama', 'like', "%{$search}%");
              });
        });
    }

    // Ambil hasil tanpa pagination
    $data = $query->get();

    return Excel::download(new PerjalananDinasExport($data), 'perjalanan_dinas.xlsx');
}
public function exportPdf(Request $request)
{
    $user = Auth::user();

    $query = PerjalananDinas::with(['pegawai', 'biaya'])
        ->orderBy('id', 'asc');

    // 🔁 Filter bulan
    if ($request->filled('bulan')) {
        $query->whereMonth('tanggal_spt', $request->bulan);
    }

    // 🔁 Filter tahun
    if ($request->filled('tahun')) {
        $query->whereYear('tanggal_spt', $request->tahun);
    }

    // 🔁 Filter status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // 🔁 Filter text search
    if ($request->search) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('tujuan_spt', 'like', "%$search%")
              ->orWhere('jenis_spt', 'like', "%$search%")
              ->orWhere('jenis_kegiatan', 'like', "%$search%")
              ->orWhereHas('pegawai', function ($pg) use ($search) {
                  $pg->where('nama', 'like', "%$search%");
              });
        });
    }

    $perjalanans = $query->get();

    // Render PDF view
   $pdf = Pdf::loadView('pdf.perjalanan-dinas-pdf', [

        'perjalanans' => $perjalanans
    ])->setPaper('A4', 'landscape');

    return $pdf->download('data-perjalanan-dinas.pdf');
}


}
