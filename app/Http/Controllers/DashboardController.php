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
public function index()
{
    $jumlahPegawai = Pegawai::count();

    // Perjalanan bulan ini (status disetujui)
    $jumlahPerjalanan = PerjalananDinas::whereMonth('tanggal_spt', Carbon::now()->month)
        ->whereYear('tanggal_spt', Carbon::now()->year)
        ->where('status', 'disetujui')
        ->count();

    // Perjalanan hari ini
    $perjalananHariIni = PerjalananDinas::whereDate('tanggal_mulai', Carbon::today())
        ->where('status', 'disetujui')
        ->count();

    // Top pelapor
    $topPelapor = Pegawai::select('pegawai.*', DB::raw('COUNT(perjalanan_dinas_user.perjalanan_dinas_id) as total_perjalanan'))
        ->leftJoin('perjalanan_dinas_user', 'perjalanan_dinas_user.pegawai_id', '=', 'pegawai.id')
        ->groupBy('pegawai.id')
        ->orderByDesc('total_perjalanan')
        ->get();

    // Total seluruh biaya (SPT)
    $totalBiaya = DB::table('perjalanan_dinas_biaya')->sum('subtotal_biaya');

    // Total biaya real cost (biaya riil)
    $totalRealCost = DB::table('perjalanan_dinas_biaya_riil')->sum('subtotal_biaya');

   $penggunaanPerBidang = DB::table('perjalanan_dinas_biaya_riil')
    ->join('pegawai', 'perjalanan_dinas_biaya_riil.pegawai_id', '=', 'pegawai.id')
    ->select('pegawai.bidang', DB::raw('SUM(perjalanan_dinas_biaya_riil.subtotal_biaya) AS total'))
    ->groupBy('pegawai.bidang')
    ->get();

    // Ambil biaya terbaru (rekaman terakhir)
    $lastRecord = DB::table('perjalanan_dinas_biaya')
        ->orderBy('created_at', 'desc')
        ->first();

    $totalBaru = $lastRecord ? $lastRecord->subtotal_biaya : 0;

    // Donut chart -> status
    $statusCounts = PerjalananDinas::selectRaw('status, COUNT(*) as total')
        ->groupBy('status')
        ->pluck('total', 'status');

    // Bar chart -> jenis SPT
    $jenisSptCounts = PerjalananDinas::selectRaw('jenis_spt, COUNT(*) as total')
        ->groupBy('jenis_spt')
        ->pluck('total', 'jenis_spt');

    // Pie chart -> destinasi terbanyak
    $topDestinasi = PerjalananDinas::select('kota_tujuan_id', DB::raw('COUNT(*) as total'))
        ->groupBy('kota_tujuan_id')
        ->orderByDesc('total')
        ->take(5)
        ->get();

    $periodeAktif = DB::table('arsip_periode')->where('is_active', 1)->first();


    $total_anggaran = $periodeAktif ? $periodeAktif->total_anggaran : 0;

    $sisaAnggaran = $total_anggaran - $totalRealCost;
    if ($sisaAnggaran < 0) {
        $sisaAnggaran = 0;
    }

    $periodeAktif = DB::table('arsip_periode')
    ->where('is_active', 1)
    ->first();

   $batas_anggaran = $periodeAktif ? $periodeAktif->batas_anggaran : 0;

   $totalTerpakai = DB::table('perjalanan_dinas_biaya_riil')->sum('subtotal_biaya');

    $persen = ($batas_anggaran > 0)
        ? min(100, ($totalTerpakai / $batas_anggaran) * 100)
        : 0;






    $sisaAnggaran = max(0, $total_anggaran - $totalRealCost);



$totalSiakReal = DB::table('perjalanan_dinas_biaya_riil')
    ->join('perjalanan_dinas', 'perjalanan_dinas.id', '=', 'perjalanan_dinas_biaya_riil.perjalanan_dinas_id')
    ->where('perjalanan_dinas.jenis_spt', 'dalam_daerah')
    ->sum('perjalanan_dinas_biaya_riil.subtotal_biaya');
$persenSiak = ($batas_anggaran > 0)
    ? min(100, ($totalSiakReal / $batas_anggaran) * 100)
    : 0;





    $totalDalamRiauReal = DB::table('perjalanan_dinas_biaya_riil')
    ->join('perjalanan_dinas', 'perjalanan_dinas_biaya_riil.perjalanan_dinas_id', '=', 'perjalanan_dinas.id')
    ->where('perjalanan_dinas.provinsi_tujuan_id', 'RIAU')
    ->where('perjalanan_dinas.kota_tujuan_id', '!=', 'Siak')
    ->sum('perjalanan_dinas_biaya_riil.subtotal_biaya');




   $persenDalamRiau = ($batas_anggaran > 0)
    ? min(100, ($totalDalamRiauReal / $batas_anggaran) * 100)
    : 0;





    $totalLuarDaerahReal = DB::table('perjalanan_dinas_biaya_riil')
    ->join('perjalanan_dinas', 'perjalanan_dinas_biaya_riil.perjalanan_dinas_id', '=', 'perjalanan_dinas.id')
    ->where('perjalanan_dinas.provinsi_tujuan_id', '!=', 'RIAU')
    ->sum('perjalanan_dinas_biaya_riil.subtotal_biaya');

$persenLuarDaerah = ($batas_anggaran > 0)
    ? min(100, ($totalLuarDaerahReal / $batas_anggaran) * 100)
    : 0;






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
        'total_anggaran',
        'sisaAnggaran',
        'periodeAktif',
        'persen',
        'penggunaanPerBidang',
        'totalSiakReal',
        'totalLuarDaerahReal',
        'persenSiak',
        'persenLuarDaerah',
        'totalDalamRiauReal',
        'persenDalamRiau',
        'batas_anggaran',



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
