<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\SbuItemController;
use App\Http\Controllers\PerjalananDinasController;
use App\Http\Controllers\PersetujuanAtasanController;
use App\Http\Controllers\VerifikasiPengajuanController;
use App\Http\Controllers\DokumenPerjalananDinasController;
use App\Http\Controllers\LaporanPerjalananDinasController;
use App\Http\Controllers\VerifikasiLaporanPerjalananDinasController;

Route::get('/data-pegawai', [PegawaiController::class, 'index'])->name('data-pegawai');
Route::get('/pegawai/create', [PegawaiController::class, 'create'])->name('pegawai.create');
Route::post('/pegawai/store', [PegawaiController::class, 'store'])->name('pegawai.store');
Route::delete('/pegawai/delete/{id}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');
Route::put('/pegawai/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');

Route::get('/perjalanan-dinas', [PerjalananDinasController::class, 'index'])->name('perjalanan-dinas.index');
Route::get('/perjalanan-dinas/create', [PerjalananDinasController::class, 'create'])->name('perjalanan-dinas.create');
Route::get('/perjalanan-dinas/{id}/edit', [PerjalananDinasController::class, 'edit'])->name('perjalanan-dinas.edit');
Route::put('/perjalanan-dinas/{id}', [PerjalananDinasController::class, 'update'])->name('perjalanan-dinas.update');
Route::post('/perjalanan-dinas/store', [PerjalananDinasController::class, 'store'])->name('perjalanan-dinas.store');
Route::get('perjalanan-dinas/show/{id}', [PerjalananDinasController::class, 'show'])->name('perjalanan-dinas.show');
Route::delete('perjalanan-dinas/delete/{id}', [PerjalananDinasController::class, 'destroy'])->name('perjalanan-dinas.destroy');

Route::get('/sbu-item', [SbuItemController::class, 'index'])->name('sbu-item.index');

Route::get('/verifikasi-pengajuan', [VerifikasiPengajuanController::class, 'index'])->name('verifikasi-pengajuan.index');
Route::put('/verifikasi-pengajuan/{id}', [VerifikasiPengajuanController::class, 'update'])->name('verifikasi-pengajuan.update');

// Persetujuan Atasan
Route::get('/persetujuan-atasan', [PersetujuanAtasanController::class, 'index'])->name('persetujuan-atasan.index');
Route::post('/persetujuan-atasan/{id}/setujui', [PersetujuanAtasanController::class, 'setujui'])->name('persetujuan-atasan.setujui');
Route::post('/persetujuan-atasan/{id}/tolak', [PersetujuanAtasanController::class, 'tolak'])->name('persetujuan-atasan.tolak');
Route::post('/persetujuan-atasan/{id}/revisi', [PersetujuanAtasanController::class, 'revisi'])->name('persetujuan-atasan.revisi');
Route::put('/persetujuan-atasan/{id}', [PersetujuanAtasanController::class, 'update'])->name('persetujuan-atasan.update');


// Dokumen Perjalanan Dinas
Route::get('/dokumen-perjalanan-dinas', [DokumenPerjalananDinasController::class, 'index'])->name('dokumen-perjalanan-dinas.index');
Route::get('/dokumen/download/{id}/{type}', [DokumenPerjalananDinasController::class, 'download'])->name('dokumen.download');

//Laporan Perjalanan Dinas
Route::get('/laporan-perjalanan-dinas', [LaporanPerjalananDinasController::class, 'index'])->name('laporan.index');
Route::get('/laporan-perjalanan-dinas/{id}/edit', [LaporanPerjalananDinasController::class, 'edit'])->name('laporan.edit');
Route::post('/laporan/{id}/update', [LaporanPerjalananDinasController::class, 'update'])->name('laporan.update');

Route::get('/verifikasi-laporan-perjalanan-dinas', [VerifikasiLaporanPerjalananDinasController::class, 'index'])->name('verifikasi-laporan.index');
Route::put('/verifikasi-laporan/{id}/update', [VerifikasiLaporanPerjalananDinasController::class, 'update'])->name('verifikasi-laporan.update');