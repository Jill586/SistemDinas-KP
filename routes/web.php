<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\SbuItemController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TambahUserController;
use App\Http\Controllers\PerjalananDinasController;
use App\Http\Controllers\VerifikasiStaffController;
use App\Http\Controllers\PersetujuanAtasanController;
use App\Http\Controllers\VerifikasiPengajuanController;
use App\Http\Controllers\DokumenPerjalananDinasController;
use App\Http\Controllers\LaporanPerjalananDinasController;
use App\Http\Controllers\VerifikasiLaporanPerjalananDinasController;
use App\Http\Controllers\ArsipPeriodeController;

/*
|--------------------------------------------------------------------------
| ROUTE LOGIN
|--------------------------------------------------------------------------
*/
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

/*
|--------------------------------------------------------------------------
| ROUTE SUPER ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:super_admin'])->group(function () {
    // Data Pegawai
    Route::controller(PegawaiController::class)->group(function () {
        Route::get('/data-pegawai', 'index')->name('data-pegawai');
        Route::get('/pegawai/create', 'create')->name('pegawai.create');
        Route::post('/pegawai/store', 'store')->name('pegawai.store');
        Route::put('/pegawai/{id}', 'update')->name('pegawai.update');
        Route::delete('/pegawai/delete/{id}', 'destroy')->name('pegawai.destroy');
        });

    // Tambah User
     Route::controller(TambahUserController::class)->group(function () {
      Route::get('/tambah-user',  'index')->name('tambah-user');
      Route::post('/tambah-user',  'store')->name('tambah-user.store');
      Route::put('/tambah-user/{id}',  'update')->name('tambah-user.update');
      Route::delete('/tambah-user/{id}', 'destroy')->name('tambah-user.destroy');
     });



// Arsip Periode
Route::prefix('arsip')->middleware('auth')->group(function () {
    Route::get('/periode', [ArsipPeriodeController::class, 'index'])->name('arsip.periode.index');  // halaman utama
    Route::post('/periode', [ArsipPeriodeController::class, 'store'])->name('arsip.periode.store'); // tambah
    Route::put('/periode/{id}', [ArsipPeriodeController::class, 'update'])->name('arsip.periode.update'); // edit via modal
    Route::delete('/periode/{id}', [ArsipPeriodeController::class, 'destroy'])->name('arsip.periode.destroy'); // hapus
    Route::put('/arsip/periode/{id}', [ArsipPeriodeController::class, 'update'])->name('arsip.periode.update');

});

    Route::post('/pegawai/import', [PegawaiController::class, 'import'])->name('pegawai.import');

    Route::get('/sbu-item', [SbuItemController::class, 'index'])->name('sbu-item.index');
    Route::put('/sbu-item/{id}', [SbuItemController::class, 'update'])->name('sbu-item.update');
    Route::post('/sbu-item', [SbuItemController::class, 'store'])->name('sbu-item.store');
    Route::delete('/sbu-item/delete/{id}', [SbuItemController::class, 'destroy'])->name('sbu-item.destroy');
    Route::get('/sbu-item/export', [SbuItemController::class, 'export'])->name('sbu-item.export');
    Route::post('/sbu-item/import', [SbuItemController::class, 'import'])->name('sbu-item.import');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| ROUTE ADMIN BIDANG
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin_bidang'])->group(function () {

    // --- Perjalanan Dinas ---
    Route::controller(PerjalananDinasController::class)->group(function () {
        Route::get('/perjalanan-dinas', 'index')->name('perjalanan-dinas.index');
        Route::get('/perjalanan-dinas/create', 'create')->name('perjalanan-dinas.create');
        Route::post('/perjalanan-dinas/store', 'store')->name('perjalanan-dinas.store');
        Route::post('/perjalanan-dinas/upload-ttd/{id}', 'uploadTtd')->name('perjalanan-dinas.uploadTtd');
        Route::get('/perjalanan-dinas/download-ttd/{id}', 'downloadTtd')->name('perjalanan-dinas.downloadTtd');
        Route::get('/perjalanan-dinas/export/excel', 'exportExcel')->name('perjalanan-dinas.export.excel');
        Route::get('/perjalanan-dinas/export/pdf', 'exportPdf')->name('perjalanan-dinas.export.pdf');


    });

    // --- Laporan Perjalanan Dinas ---
    Route::controller(LaporanPerjalananDinasController::class)->group(function () {

        Route::get('/laporan-perjalanan-dinas', 'index')->name('laporan.index');
        Route::get('/laporan-perjalanan-dinas/{id}/edit', 'edit')->name('laporan.edit');

        // SIMPAN LAPORAN BARU
        Route::post('/laporan/{id}/update', 'update')->name('laporan.update');

        // UPDATE LAPORAN (EDIT)
        Route::put('/laporan-perjadin/{id}/edit','updateLaporan')->name('laporan.updateLaporan');
        Route::delete('/laporan/biaya/{id}','hapusBiaya')->name('laporan.hapusBiaya');

        Route::get('/laporan/download/{id}/{type}', 'downloadLaporan')->name('laporan.download');
        Route::get('/export-laporanperjalanan-dinas','exportExcel')->name('laporan-perjalanan.export.excel');
        Route::get('/laporan/pdf', 'exportPdf')->name('laporan.export.pdf');



    });
});
/*
|--------------------------------------------------------------------------
| ROUTE VERIFIKATOR LEVEL 1
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:verifikator1|admin_bidang'])->group(function () {
    Route::controller(PerjalananDinasController::class)->group(function () {
        Route::get('/perjalanan-dinas', 'index')->name('perjalanan-dinas.index');
        Route::get('/perjalanan-dinas/{id}/edit', 'edit')->name('perjalanan-dinas.edit');
        Route::put('/perjalanan-dinas/{id}', 'update')->name('perjalanan-dinas.update');
        Route::get('/perjalanan-dinas/show/{id}', 'show')->name('perjalanan-dinas.show');
        Route::delete('/perjalanan-dinas/delete/{id}', 'destroy')->name('perjalanan-dinas.destroy');

    });
     Route::controller(VerifikasiStaffController::class)->group(function () {
        Route::get('/verifikasi-staff', 'index')->name('verifikasi-staff.index');

    // 👁️ Lihat detail (kalau nanti mau buka halaman terpisah)
    Route::get('/verifikasi-staff/{id}', 'show')->name('verifikasi-staff.show');

    // ✅ Update status pengajuan (disetujui / tolak / revisi)
    Route::put('/verifikasi-staff/{id}', 'update')->name('verifikasi-staff.update');

    // Export Excel
   Route::get('/export-verifikasi-staff','exportExcel')->name('verifikasi-staff.exportExcel');
   Route::get('/export-verifikasi-staff/pdf',  'exportPdf')->name('verifikasi-staff.export.pdf');




    // 📎 Upload bukti undangan
   Route::put('/laporan-perjadin/{id}/edit',
    [LaporanPerjalananDinasController::class, 'updateLaporan']
)->name('laporan.updateLaporan');
     });


});

/*
|--------------------------------------------------------------------------
| ROUTE VERIFIKATOR LEVEL 2
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:verifikator2'])->group(function () {
    Route::controller(VerifikasiPengajuanController::class)->group(function () {
        Route::get('/verifikasi-pengajuan', 'index')->name('verifikasi-pengajuan.index');
        Route::get('/verifikasi-pengajuan/{id}', 'show')->name('verifikasi-pengajuan.show');
        Route::put('/verifikasi-pengajuan/{id}', 'update')->name('verifikasi-pengajuan.update');
        Route::post('/verifikasi-pengajuan/{id}/upload-undangan', 'uploadUndangan')->name('verifikasi.uploadUndangan');
        Route::get('/export-verifikasi-pengajuan', 'exportExcel') ->name('verifikasi-pengajuan.export.excel');
        Route::get('/verifikasi-pengajuan/export/pdf', 'exportPdf')->name('verifikasi-pengajuan.export.pdf');


    });

    Route::controller(VerifikasiLaporanPerjalananDinasController::class)->group(function () {
        Route::get('/verifikasi-laporan-perjalanan-dinas', 'index')->name('verifikasi-laporan.index');
        Route::put('/verifikasi-laporan/{id}/update', 'update')->name('verifikasi-laporan.update');
        Route::get('/export-verifikasi-perjalanandinas','exportExcel')->name('verifikasi-laporan.export.excel');
        Route::get('/verifikasi-laporan-perjalanan-dinas/export-excel','exportExcel')->name('verifikasi-laporan.export.excel');
        Route::get('/verifikasi-laporan/export/pdf',  'exportPdf')->name('verifikasi-laporan.export.pdf');

    });
});

/*
|--------------------------------------------------------------------------
| ROUTE VERIFIKATOR LEVEL 3
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:verifikator3'])->group(function () {
    Route::controller(PersetujuanAtasanController::class)->group(function () {
        Route::get('/persetujuan-atasan', 'index')->name('persetujuan-atasan.index');
        Route::post('/persetujuan-atasan/{id}/setujui', 'setujui')->name('persetujuan-atasan.setujui');
        Route::post('/persetujuan-atasan/{id}/tolak', 'tolak')->name('persetujuan-atasan.tolak');
        Route::post('/persetujuan-atasan/{id}/revisi', 'revisi')->name('persetujuan-atasan.revisi');
        Route::put('/persetujuan-atasan/{id}', 'update')->name('persetujuan-atasan.update');
        Route::get('/persetujuan-atasan/export-excel','exportExcel')->name('persetujuan-atasan.export.excel');
        Route::get('/persetujuan-atasan/export/pdf', 'exportPdf')->name('persetujuan-atasan.export.pdf');


    });
});

/*
|--------------------------------------------------------------------------
| ROUTE DOKUMEN PERJALANAN DINAS (multi role)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:super_admin|admin_bidang|verifikator1|verifikator2|verifikator3'])->group(function () {
        Route::controller(DokumenPerjalananDinasController::class)->group(function () {
            Route::get('/dokumen-perjalanan-dinas', 'index')->name('dokumen-perjalanan-dinas.index');
            Route::get('/dokumen/download/{id}/{type}', 'download')->name('dokumen.download');
            Route::get('/dokumen-spt-sppd/export-excel','exportExcel')->name('dokumen-spt-sppd.export.excel');
            Route::get('/dokumen-spt-sppd/export/pdf','exportPdf')->name('dokumen-spt-sppd.export.pdf');

        });
    });

