<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\SbuItemController;
use App\Http\Controllers\PerjalananDinasController;
use App\Http\Controllers\PersetujuanAtasanController;
use App\Http\Controllers\VerifikasiPengajuanController;
use App\Http\Controllers\DokumenPerjalananDinasController;
use App\Http\Controllers\LaporanPerjalananDinasController;
use App\Http\Controllers\VerifikasiLaporanPerjalananDinasController;
use App\Http\Controllers\DashboardController;

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

    // SBU Item
    Route::get('/sbu-item', [SbuItemController::class, 'index'])->name('sbu-item.index');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| ROUTE ADMIN BIDANG
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin_bidang'])->group(function () {
    // Perjalanan Dinas
    Route::controller(PerjalananDinasController::class)->group(function () {
        Route::get('/perjalanan-dinas', 'index')->name('perjalanan-dinas.index');
        Route::get('/perjalanan-dinas/create', 'create')->name('perjalanan-dinas.create');
        Route::post('/perjalanan-dinas/store', 'store')->name('perjalanan-dinas.store');
    });

    // Laporan Perjalanan Dinas
    Route::controller(LaporanPerjalananDinasController::class)->group(function () {
        Route::get('/laporan-perjalanan-dinas', 'index')->name('laporan.index');
        Route::get('/laporan-perjalanan-dinas/{id}/edit', 'edit')->name('laporan.edit');
        Route::post('/laporan/{id}/update', 'update')->name('laporan.update');

        Route::get('/verifikasi-pengajuan', 'index')->name('verifikasi-pengajuan.index');
        Route::put('/verifikasi-pengajuan/{id}', 'update')->name('verifikasi-pengajuan.update');
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
    });

    Route::controller(VerifikasiLaporanPerjalananDinasController::class)->group(function () {
        Route::get('/verifikasi-laporan-perjalanan-dinas', 'index')->name('verifikasi-laporan.index');
        Route::put('/verifikasi-laporan/{id}/update', 'update')->name('verifikasi-laporan.update');
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
    });
});

/*
|--------------------------------------------------------------------------
| ROUTE DOKUMEN PERJALANAN DINAS (multi role)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:super_admin|admin_bidang|verifikator2|verifikator2|verifikator3'])->group(function () {
        Route::controller(DokumenPerjalananDinasController::class)->group(function () {
            Route::get('/dokumen-perjalanan-dinas', 'index')->name('dokumen-perjalanan-dinas.index');
            Route::get('/dokumen/download/{id}/{type}', 'download')->name('dokumen.download');
        });
    });

