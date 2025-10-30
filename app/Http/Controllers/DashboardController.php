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

        // Hitung jumlah perjalanan berdasarkan tanggal_spt bulan & tahun ini
        $jumlahPerjalanan = PerjalananDinas::whereMonth('tanggal_spt', Carbon::now()->month)
            ->whereYear('tanggal_spt', Carbon::now()->year)
            ->count();

        // Jumlah perjalanan hari ini
        $perjalananHariIni = PerjalananDinas::whereDate('tanggal_mulai', Carbon::today())->count();
        
       // Ambil semua pegawai dengan jumlah perjalanan masing-masing
       $topPelapor = Pegawai::select('pegawai.*', DB::raw('COUNT(perjalanan_dinas_user.perjalanan_dinas_id) as total_perjalanan'))
        ->leftJoin('perjalanan_dinas_user', 'perjalanan_dinas_user.pegawai_id', '=', 'pegawai.id')
        ->groupBy('pegawai.id')
        ->orderByDesc('total_perjalanan')
        ->get();


        # Hitung total biaya dari semua perjalanan dinas
        $totalBiaya = DB::table('perjalanan_dinas_biaya')->sum('subtotal_biaya');
        $totalBaru = DB::table('perjalanan_dinas_biaya')
            ->join('perjalanan_dinas', 'perjalanan_dinas_biaya.perjalanan_dinas_id', '=', 'perjalanan_dinas.id')
            ->where('perjalanan_dinas.status_laporan', 'diproses')
            ->sum('perjalanan_dinas_biaya.subtotal_biaya');

        // Total seluruh biaya
        $totalBiaya = DB::table('perjalanan_dinas_biaya')->sum('subtotal_biaya');

        // Ambil record terakhir berdasarkan waktu input (pengajuan terbaru)
        $lastRecord = DB::table('perjalanan_dinas_biaya')
            ->orderBy('created_at', 'desc')
            ->first();

        // Ambil subtotal dari record terbaru
        $totalBaru = $lastRecord ? $lastRecord->subtotal_biaya : 0;

        // Hitung jumlah tiap status laporan (DONUT CHART)
        $statusCounts = PerjalananDinas::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // Hitung jumlah per jenis_spt (BARCHART)
        $jenisSptCounts = PerjalananDinas::selectRaw('jenis_spt, COUNT(*) as total')
            ->groupBy('jenis_spt')
            ->pluck('total', 'jenis_spt');

        // Hitung jumlah tujuan terbanyak (PIECHART)
        $topDestinasi = PerjalananDinas::select('kota_tujuan_id', DB::raw('COUNT(*) as total'))
            ->groupBy('kota_tujuan_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        return view('dashboard', compact('jumlahPegawai', 'jumlahPerjalanan', 'topPelapor', 'perjalananHariIni', 'totalBiaya', 'totalBaru', 'statusCounts', 'jenisSptCounts', 'topDestinasi'));
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
