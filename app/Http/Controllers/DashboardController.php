<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\PerjalananDinas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $tahun = $request->input('tahun');
    $filterTahun = fn ($q) =>
    $request->filled('tahun')
        ? $q->whereYear('tanggal_spt', $tahun)
        : $q;

    $jumlahPegawai = Pegawai::count();

    // Perjalanan bulan ini (status disetujui)
    $jumlahPerjalanan = PerjalananDinas::where('status', 'disetujui')
        ->when($request->filled('tahun'), fn ($q) =>
            $q->whereYear('tanggal_spt', $tahun)
        )
        ->count();

    // Perjalanan hari ini
    $perjalananHariIni = PerjalananDinas::whereDate('tanggal_mulai', Carbon::today())
        ->where('status', 'disetujui')
        ->count();

    // Top pelapor
    $topPelapor = Pegawai::select(
        'pegawai.*',
        DB::raw('COUNT(perjalanan_dinas.id) as total_perjalanan')
    )
    ->leftJoin('perjalanan_dinas_user', 'perjalanan_dinas_user.pegawai_id', '=', 'pegawai.id')
    ->leftJoin('perjalanan_dinas', 'perjalanan_dinas.id', '=', 'perjalanan_dinas_user.perjalanan_dinas_id')
    ->when($tahun, fn ($q) => $q->whereYear('perjalanan_dinas.tanggal_spt', $tahun))
    ->groupBy('pegawai.id')
    ->orderByDesc('total_perjalanan')
    ->get();

    // Total seluruh biaya (SPT)
   $totalBiaya = DB::table('perjalanan_dinas_biaya')
    ->when($request->filled('tahun'), fn ($q) =>
        $q->whereYear('created_at', $tahun)
    )
    ->sum('subtotal_biaya');


    // Total biaya real cost (biaya riil)
    $totalRealCost = DB::table('perjalanan_dinas_biaya_riil')
        ->join('perjalanan_dinas', 'perjalanan_dinas.id', '=', 'perjalanan_dinas_biaya_riil.perjalanan_dinas_id')
        ->when($request->filled('tahun'),
            fn ($q) => $q->whereYear('perjalanan_dinas.tanggal_spt', $tahun)
        )
        ->sum('perjalanan_dinas_biaya_riil.subtotal_biaya');

    $penggunaanPerBidang = DB::table('perjalanan_dinas_biaya_riil')
        ->join('perjalanan_dinas', 'perjalanan_dinas.id', '=', 'perjalanan_dinas_biaya_riil.perjalanan_dinas_id')
        ->join('pegawai', 'pegawai.id', '=', 'perjalanan_dinas_biaya_riil.pegawai_id')
        ->when($request->filled('tahun'),
            fn ($q) => $q->whereYear('perjalanan_dinas.tanggal_spt', $tahun)
        )
        ->select('pegawai.bidang', DB::raw('SUM(perjalanan_dinas_biaya_riil.subtotal_biaya) AS total'))
        ->groupBy('pegawai.bidang')
        ->get();

    // Ambil biaya terbaru (rekaman terakhir)
    $lastRecord = DB::table('perjalanan_dinas_biaya')
        ->orderBy('created_at', 'desc')
        ->first();

    $totalBaru = $lastRecord ? $lastRecord->subtotal_biaya : 0;

    // Donut chart -> status
    $statusCounts = PerjalananDinas::query()
    ->when($request->filled('tahun'), fn ($q) =>
        $q->whereYear('tanggal_spt', $tahun)
    )
    ->selectRaw('status, COUNT(*) as total')
    ->groupBy('status')
    ->pluck('total', 'status');



    // Bar chart -> jenis SPT
    $jenisSptCounts = PerjalananDinas::when($tahun, fn ($q) => $q->whereYear('tanggal_spt', $tahun))
    ->selectRaw('jenis_spt, COUNT(*) as total')
    ->groupBy('jenis_spt')
    ->pluck('total', 'jenis_spt');


    // Pie chart -> destinasi terbanyak
    $topDestinasi = PerjalananDinas::query()
    ->when($request->filled('tahun'), fn ($q) =>
        $q->whereYear('tanggal_spt', $tahun)
    )
    ->select('kota_tujuan_id', DB::raw('COUNT(*) as total'))
    ->groupBy('kota_tujuan_id')
    ->orderByDesc('total')
    ->take(5)
    ->get();

    // Status bayar laporan perjalanan dinas
    $statusBayar = DB::table('laporan_perjalanan_dinas')
    ->whereNotNull('status_bayar')
    ->select('status_bayar', DB::raw('COUNT(*) as total'))
    ->groupBy('status_bayar')
    ->get();

    $periodeAktif = DB::table('arsip_periode')
        ->when($request->filled('tahun'),
            fn ($q) => $q->where('tahun', $tahun),
            fn ($q) => $q->where('is_active', 1)
        )
        ->first();

    $batas_anggaran   = $periodeAktif->batas_anggaran ?? 0;
    $batasSiak        = $periodeAktif->total_siak ?? 0;
    $batasDalamRiau   = $periodeAktif->total_dalam_riau ?? 0;
    $batasLuarDaerah  = $periodeAktif->total_luar_riau ?? 0;

    $total_anggaran = $periodeAktif ? $periodeAktif->total_anggaran : 0;

    $sisaAnggaran = $total_anggaran - $totalRealCost;
    if ($sisaAnggaran < 0) {
        $sisaAnggaran = 0;
    }

    $batas_anggaran = $periodeAktif ? $periodeAktif->batas_anggaran : 0;

    $totalTerpakai = DB::table('perjalanan_dinas_biaya_riil')
    ->sum('subtotal_biaya');

    $persen = ($batas_anggaran > 0)
    ? round(min(100, ($totalRealCost / $batas_anggaran) * 100), 1)
    : 0;

    $sisaAnggaran = max(0, $total_anggaran - $totalRealCost);

    $totalSiakReal = DB::table('perjalanan_dinas_biaya_riil')
        ->join('perjalanan_dinas', 'perjalanan_dinas.id', '=', 'perjalanan_dinas_biaya_riil.perjalanan_dinas_id')
        ->where('perjalanan_dinas.jenis_spt', 'dalam_daerah')
        ->when($request->filled('tahun'),
            fn ($q) => $q->whereYear('perjalanan_dinas.tanggal_spt', $tahun)
        )
        ->sum('perjalanan_dinas_biaya_riil.subtotal_biaya');

        $persenSiak = ($batasSiak > 0)
            ? round(min(100, ($totalSiakReal / $batasSiak) * 100), 1)
            : 0;

    $totalDalamRiauReal = DB::table('perjalanan_dinas_biaya_riil')
        ->join('perjalanan_dinas', 'perjalanan_dinas.id', '=', 'perjalanan_dinas_biaya_riil.perjalanan_dinas_id')
        ->where('perjalanan_dinas.provinsi_tujuan_id', 'RIAU')
        ->where('perjalanan_dinas.kota_tujuan_id', '!=', 'Siak')
         ->when($request->filled('tahun'),
        fn ($q) => $q->whereYear('perjalanan_dinas.tanggal_spt', $tahun)
    )
        ->sum('perjalanan_dinas_biaya_riil.subtotal_biaya');

    $persenDalamRiau = ($batasDalamRiau > 0)
        ? round(min(100, ($totalDalamRiauReal / $batasDalamRiau) * 100), 1)
        : 0;

   $totalLuarDaerahReal = DB::table('perjalanan_dinas_biaya_riil')
    ->join('perjalanan_dinas', 'perjalanan_dinas.id', '=', 'perjalanan_dinas_biaya_riil.perjalanan_dinas_id')
    ->where('perjalanan_dinas.provinsi_tujuan_id', '!=', 'RIAU')
     ->when($request->filled('tahun'),
        fn ($q) => $q->whereYear('perjalanan_dinas.tanggal_spt', $tahun)
    )
    ->sum('perjalanan_dinas_biaya_riil.subtotal_biaya');

    $persenLuarDaerah = ($batasLuarDaerah > 0)
        ? round(min(100, ($totalLuarDaerahReal / $batasLuarDaerah) * 100), 1)
        : 0;


    $batasLuarDalam = $batasDalamRiau + $batasLuarDaerah;
    $totalLuarDalamReal = $totalDalamRiauReal + $totalLuarDaerahReal;

    $persenLuarDalam = ($batasLuarDalam > 0)
        ? round(min(100, ($totalLuarDalamReal / $batasLuarDalam) * 100), 1)
        : 0;

    $uangHarian = DB::table('perjalanan_dinas_biaya')
    ->select(
        'perjalanan_dinas_id',
        DB::raw('SUM(subtotal_biaya) as total_uh')
    )
    ->groupBy('perjalanan_dinas_id');

    $biayaRiil = DB::table('perjalanan_dinas_biaya_riil')
    ->select(
        'perjalanan_dinas_id',
        DB::raw('SUM(subtotal_biaya) as total_riil')
    )
    ->groupBy('perjalanan_dinas_id');

    $ringkasanPembayaran = DB::table('laporan_perjalanan_dinas as l')
    ->join('perjalanan_dinas as pd', 'pd.id', '=', 'l.perjalanan_dinas_id')

    ->leftJoinSub($uangHarian, 'uh', function ($join) {
        $join->on('uh.perjalanan_dinas_id', '=', 'pd.id');
    })

    ->leftJoinSub($biayaRiil, 'br', function ($join) {
        $join->on('br.perjalanan_dinas_id', '=', 'pd.id');
    })

    ->select(
        'l.status_bayar',
        DB::raw('COUNT(DISTINCT l.id) as jumlah_spt'),
        DB::raw('SUM(COALESCE(uh.total_uh,0) + COALESCE(br.total_riil,0)) as total_dibayarkan')
    )

    ->when($request->filled('tahun'), fn ($q) =>
        $q->whereYear('pd.tanggal_spt', $tahun)
    )

    ->groupBy('l.status_bayar')
    ->get();

    // ================================
    // RINGKASAN PEMBAYARAN SPT (FIX)
    // ================================

    $jumlahSudahBayar = 0;
    $jumlahBelumBayar = 0;
    $totalSudahBayar  = 0;
    $totalBelumBayar  = 0;

    // Ambil SPT + relasi (WAJIB eager load)
    $laporansRingkasan = PerjalananDinas::with([
            'pegawai',
            'biaya.sbuItem',
            'biayaRiil',
            'laporan'
        ])
        ->when($request->filled('tahun'), fn ($q) =>
            $q->whereYear('tanggal_spt', $tahun)
        )
        ->get();

    foreach ($laporansRingkasan as $row) {

        // HITUNG TOTAL DIBAYARKAN (SAMA PERSIS DENGAN HALAMAN VERIFIKASI)
        $totalSemuaPegawai = 0;

        foreach ($row->pegawai as $pg) {
            $totalDibayarPegawai = 0;

            // UANG HARIAN
            $uangHarian = $row->biaya
                ->where('pegawai_id_terkait', $pg->id)
                ->filter(fn ($b) =>
                    $b->sbuItem &&
                    $b->sbuItem->kategori_biaya === 'UANG_HARIAN'
                )
                ->first();

            $totalDibayarPegawai += $uangHarian->subtotal_biaya ?? 0;

            // REAL COST
            foreach ($row->biayaRiil->where('pegawai_id', $pg->id) as $riil) {
                $totalDibayarPegawai += $riil->subtotal_biaya;
            }

            $totalSemuaPegawai += $totalDibayarPegawai;
        }

        // STATUS BAYAR
        $statusBayarLaporan = optional($row->laporan)->status_bayar;

        if ($statusBayarLaporan === 'sudah_bayar') {
            $jumlahSudahBayar++;
            $totalSudahBayar += $totalSemuaPegawai;
        } else {
            $jumlahBelumBayar++;
            $totalBelumBayar += $totalSemuaPegawai;
        }
    }

    return view('dashboard', compact(
    'jumlahPegawai',
    'jumlahPerjalanan',
    'topPelapor',
    'perjalananHariIni',
    'totalBiaya',
    'totalBaru',
    'totalRealCost',
    'statusCounts',
    'jenisSptCounts',
    'topDestinasi',
    'periodeAktif',
    'persen',
    'penggunaanPerBidang',

    // TOTAL
    'batas_anggaran',

    // SIAK
    'totalSiakReal',
    'persenSiak',
    'batasSiak',

    // DALAM RIAU
    'totalDalamRiauReal',
    'persenDalamRiau',
    'batasDalamRiau',

    // LUAR
    'totalLuarDaerahReal',
    'persenLuarDaerah',
    'batasLuarDaerah',

    //LUAR DALAM DAERAH
    'totalLuarDalamReal',
    'batasLuarDalam',
    'persenLuarDalam',

    'statusBayar',
    'ringkasanPembayaran',
    'jumlahSudahBayar',
    'jumlahBelumBayar',
    'totalSudahBayar',
    'totalBelumBayar'
));

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
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
