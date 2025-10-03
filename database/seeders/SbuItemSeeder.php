<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SbuItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SbuItemSeeder extends Seeder
{
        /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama jika ada (menggunakan delete instead of truncate)
        SbuItem::query()->delete();

        $defaultKeys = [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'kota_tujuan' => null,
            'kecamatan_tujuan' => null,
            'desa_tujuan' => null,
            'keterangan' => null,
            'jarak_km_min' => null,
            'jarak_km_max' => null,
        ];

        $sbuData = [
            // ================================================================
            // 1. SATUAN BIAYA UANG HARIAN PERJALANAN DINAS DALAM NEGERI LUAR KABUPATEN (Halaman 1)
            // ================================================================
            
            // ACEH
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Aceh',
                'provinsi_tujuan' => 'ACEH',
                'satuan' => 'OH',
                'besaran_biaya' => 360000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Aceh',
                'provinsi_tujuan' => 'ACEH',
                'satuan' => 'OH',
                'besaran_biaya' => 110000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // SUMATERA UTARA
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Sumatera Utara',
                'provinsi_tujuan' => 'SUMATERA UTARA',
                'satuan' => 'OH',
                'besaran_biaya' => 370000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Sumatera Utara',
                'provinsi_tujuan' => 'SUMATERA UTARA',
                'satuan' => 'OH',
                'besaran_biaya' => 110000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // RIAU
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Riau',
                'provinsi_tujuan' => 'RIAU',
                'satuan' => 'OH',
                'besaran_biaya' => 370000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Riau',
                'provinsi_tujuan' => 'RIAU',
                'satuan' => 'OH',
                'besaran_biaya' => 110000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // KEPULAUAN RIAU
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Kepulauan Riau',
                'provinsi_tujuan' => 'KEPULAUAN RIAU',
                'satuan' => 'OH',
                'besaran_biaya' => 370000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Kepulauan Riau',
                'provinsi_tujuan' => 'KEPULAUAN RIAU',
                'satuan' => 'OH',
                'besaran_biaya' => 110000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // JAMBI
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Jambi',
                'provinsi_tujuan' => 'JAMBI',
                'satuan' => 'OH',
                'besaran_biaya' => 370000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Jambi',
                'provinsi_tujuan' => 'JAMBI',
                'satuan' => 'OH',
                'besaran_biaya' => 110000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // SUMATERA BARAT
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Sumatera Barat',
                'provinsi_tujuan' => 'SUMATERA BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 380000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Sumatera Barat',
                'provinsi_tujuan' => 'SUMATERA BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 110000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // SUMATERA SELATAN
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Sumatera Selatan',
                'provinsi_tujuan' => 'SUMATERA SELATAN',
                'satuan' => 'OH',
                'besaran_biaya' => 380000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Sumatera Selatan',
                'provinsi_tujuan' => 'SUMATERA SELATAN',
                'satuan' => 'OH',
                'besaran_biaya' => 110000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // LAMPUNG
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Lampung',
                'provinsi_tujuan' => 'LAMPUNG',
                'satuan' => 'OH',
                'besaran_biaya' => 380000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Lampung',
                'provinsi_tujuan' => 'LAMPUNG',
                'satuan' => 'OH',
                'besaran_biaya' => 110000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // BENGKULU
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Bengkulu',
                'provinsi_tujuan' => 'BENGKULU',
                'satuan' => 'OH',
                'besaran_biaya' => 380000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Bengkulu',
                'provinsi_tujuan' => 'BENGKULU',
                'satuan' => 'OH',
                'besaran_biaya' => 110000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // BANGKA BELITUNG
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Bangka Belitung',
                'provinsi_tujuan' => 'BANGKA BELITUNG',
                'satuan' => 'OH',
                'besaran_biaya' => 410000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Bangka Belitung',
                'provinsi_tujuan' => 'BANGKA BELITUNG',
                'satuan' => 'OH',
                'besaran_biaya' => 120000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // BANTEN
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Banten',
                'provinsi_tujuan' => 'BANTEN',
                'satuan' => 'OH',
                'besaran_biaya' => 370000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Banten',
                'provinsi_tujuan' => 'BANTEN',
                'satuan' => 'OH',
                'besaran_biaya' => 110000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // JAWA BARAT
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Jawa Barat',
                'provinsi_tujuan' => 'JAWA BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 430000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Jawa Barat',
                'provinsi_tujuan' => 'JAWA BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 130000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // D.K.I. JAKARTA
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten D.K.I. Jakarta',
                'provinsi_tujuan' => 'D.K.I. JAKARTA',
                'satuan' => 'OH',
                'besaran_biaya' => 530000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat D.K.I. Jakarta',
                'provinsi_tujuan' => 'D.K.I. JAKARTA',
                'satuan' => 'OH',
                'besaran_biaya' => 160000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // JAWA TENGAH
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Jawa Tengah',
                'provinsi_tujuan' => 'JAWA TENGAH',
                'satuan' => 'OH',
                'besaran_biaya' => 370000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Jawa Tengah',
                'provinsi_tujuan' => 'JAWA TENGAH',
                'satuan' => 'OH',
                'besaran_biaya' => 110000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // D.I. YOGYAKARTA
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten D.I. Yogyakarta',
                'provinsi_tujuan' => 'D.I. YOGYAKARTA',
                'satuan' => 'OH',
                'besaran_biaya' => 420000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat D.I. Yogyakarta',
                'provinsi_tujuan' => 'D.I. YOGYAKARTA',
                'satuan' => 'OH',
                'besaran_biaya' => 130000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // JAWA TIMUR
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Jawa Timur',
                'provinsi_tujuan' => 'JAWA TIMUR',
                'satuan' => 'OH',
                'besaran_biaya' => 410000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Jawa Timur',
                'provinsi_tujuan' => 'JAWA TIMUR',
                'satuan' => 'OH',
                'besaran_biaya' => 120000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // BALI
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Bali',
                'provinsi_tujuan' => 'BALI',
                'satuan' => 'OH',
                'besaran_biaya' => 480000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Bali',
                'provinsi_tujuan' => 'BALI',
                'satuan' => 'OH',
                'besaran_biaya' => 140000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // NUSA TENGGARA BARAT
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Nusa Tenggara Barat',
                'provinsi_tujuan' => 'NUSA TENGGARA BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 440000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Nusa Tenggara Barat',
                'provinsi_tujuan' => 'NUSA TENGGARA BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 130000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // NUSA TENGGARA TIMUR
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Nusa Tenggara Timur',
                'provinsi_tujuan' => 'NUSA TENGGARA TIMUR',
                'satuan' => 'OH',
                'besaran_biaya' => 430000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Nusa Tenggara Timur',
                'provinsi_tujuan' => 'NUSA TENGGARA TIMUR',
                'satuan' => 'OH',
                'besaran_biaya' => 130000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // KALIMANTAN BARAT
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Kalimantan Barat',
                'provinsi_tujuan' => 'KALIMANTAN BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 380000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Kalimantan Barat',
                'provinsi_tujuan' => 'KALIMANTAN BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 110000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // KALIMANTAN TENGAH
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Kalimantan Tengah',
                'provinsi_tujuan' => 'KALIMANTAN TENGAH',
                'satuan' => 'OH',
                'besaran_biaya' => 360000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Kalimantan Tengah',
                'provinsi_tujuan' => 'KALIMANTAN TENGAH',
                'satuan' => 'OH',
                'besaran_biaya' => 110000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // KALIMANTAN SELATAN
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Kalimantan Selatan',
                'provinsi_tujuan' => 'KALIMANTAN SELATAN',
                'satuan' => 'OH',
                'besaran_biaya' => 380000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Kalimantan Selatan',
                'provinsi_tujuan' => 'KALIMANTAN SELATAN',
                'satuan' => 'OH',
                'besaran_biaya' => 110000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // KALIMANTAN TIMUR
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Kalimantan Timur',
                'provinsi_tujuan' => 'KALIMANTAN TIMUR',
                'satuan' => 'OH',
                'besaran_biaya' => 430000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Kalimantan Timur',
                'provinsi_tujuan' => 'KALIMANTAN TIMUR',
                'satuan' => 'OH',
                'besaran_biaya' => 130000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // KALIMANTAN UTARA
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Kalimantan Utara',
                'provinsi_tujuan' => 'KALIMANTAN UTARA',
                'satuan' => 'OH',
                'besaran_biaya' => 430000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Kalimantan Utara',
                'provinsi_tujuan' => 'KALIMANTAN UTARA',
                'satuan' => 'OH',
                'besaran_biaya' => 130000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // SULAWESI UTARA
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Sulawesi Utara',
                'provinsi_tujuan' => 'SULAWESI UTARA',
                'satuan' => 'OH',
                'besaran_biaya' => 370000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Sulawesi Utara',
                'provinsi_tujuan' => 'SULAWESI UTARA',
                'satuan' => 'OH',
                'besaran_biaya' => 110000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // GORONTALO
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Gorontalo',
                'provinsi_tujuan' => 'GORONTALO',
                'satuan' => 'OH',
                'besaran_biaya' => 370000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Gorontalo',
                'provinsi_tujuan' => 'GORONTALO',
                'satuan' => 'OH',
                'besaran_biaya' => 110000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // SULAWESI BARAT
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Sulawesi Barat',
                'provinsi_tujuan' => 'SULAWESI BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 410000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Sulawesi Barat',
                'provinsi_tujuan' => 'SULAWESI BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 120000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // SULAWESI SELATAN
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Sulawesi Selatan',
                'provinsi_tujuan' => 'SULAWESI SELATAN',
                'satuan' => 'OH',
                'besaran_biaya' => 430000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Sulawesi Selatan',
                'provinsi_tujuan' => 'SULAWESI SELATAN',
                'satuan' => 'OH',
                'besaran_biaya' => 130000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // SULAWESI TENGAH
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Sulawesi Tengah',
                'provinsi_tujuan' => 'SULAWESI TENGAH',
                'satuan' => 'OH',
                'besaran_biaya' => 370000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Sulawesi Tengah',
                'provinsi_tujuan' => 'SULAWESI TENGAH',
                'satuan' => 'OH',
                'besaran_biaya' => 110000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // SULAWESI TENGGARA
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Sulawesi Tenggara',
                'provinsi_tujuan' => 'SULAWESI TENGGARA',
                'satuan' => 'OH',
                'besaran_biaya' => 380000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Sulawesi Tenggara',
                'provinsi_tujuan' => 'SULAWESI TENGGARA',
                'satuan' => 'OH',
                'besaran_biaya' => 110000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // MALUKU
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Maluku',
                'provinsi_tujuan' => 'MALUKU',
                'satuan' => 'OH',
                'besaran_biaya' => 380000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Maluku',
                'provinsi_tujuan' => 'MALUKU',
                'satuan' => 'OH',
                'besaran_biaya' => 110000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // MALUKU UTARA
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Maluku Utara',
                'provinsi_tujuan' => 'MALUKU UTARA',
                'satuan' => 'OH',
                'besaran_biaya' => 430000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Maluku Utara',
                'provinsi_tujuan' => 'MALUKU UTARA',
                'satuan' => 'OH',
                'besaran_biaya' => 130000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // PAPUA
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Papua',
                'provinsi_tujuan' => 'PAPUA',
                'satuan' => 'OH',
                'besaran_biaya' => 580000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Papua',
                'provinsi_tujuan' => 'PAPUA',
                'satuan' => 'OH',
                'besaran_biaya' => 170000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // PAPUA BARAT
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Luar Kabupaten Papua Barat',
                'provinsi_tujuan' => 'PAPUA BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 480000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Diklat Papua Barat',
                'provinsi_tujuan' => 'PAPUA BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 140000,
                'tipe_perjalanan' => 'DIKLAT',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // ================================================================
            // 2. UANG HARIAN DALAM KABUPATEN > 8 JAM (Halaman 1 bawah)
            // ================================================================
            array_merge($defaultKeys, [
                'kategori_biaya' => 'UANG_HARIAN',
                'uraian_biaya' => 'Dalam Kabupaten Riau > 8 Jam',
                'provinsi_tujuan' => 'RIAU',
                'kota_tujuan' => 'SIAK',
                'satuan' => 'OH',
                'besaran_biaya' => 150000,
                'tipe_perjalanan' => 'DALAM_KABUPATEN_LEBIH_8_JAM',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // ================================================================
            // 3. UANG REPRESENTASI (Halaman 2)
            // ================================================================
            array_merge($defaultKeys, [
                'kategori_biaya' => 'REPRESENTASI',
                'uraian_biaya' => 'Pejabat Negara/Daerah Luar Kabupaten',
                'satuan' => 'OH',
                'besaran_biaya' => 250000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_PIMPINAN_DPRD'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'REPRESENTASI',
                'uraian_biaya' => 'Pejabat Negara/Daerah Dalam Kabupaten',
                'satuan' => 'OH',
                'besaran_biaya' => 125000,
                'tipe_perjalanan' => 'DALAM_KABUPATEN_LEBIH_8_JAM',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_PIMPINAN_DPRD'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'REPRESENTASI',
                'uraian_biaya' => 'Pejabat Eselon I Luar Kabupaten',
                'satuan' => 'OH',
                'besaran_biaya' => 200000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'REPRESENTASI',
                'uraian_biaya' => 'Pejabat Eselon I Dalam Kabupaten',
                'satuan' => 'OH',
                'besaran_biaya' => 100000,
                'tipe_perjalanan' => 'DALAM_KABUPATEN_LEBIH_8_JAM',
                'tingkat_pejabat_atau_golongan' => 'ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'REPRESENTASI',
                'uraian_biaya' => 'Pejabat Eselon II Luar Kabupaten',
                'satuan' => 'OH',
                'besaran_biaya' => 150000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'REPRESENTASI',
                'uraian_biaya' => 'Pejabat Eselon II Dalam Kabupaten',
                'satuan' => 'OH',
                'besaran_biaya' => 75000,
                'tipe_perjalanan' => 'DALAM_KABUPATEN_LEBIH_8_JAM',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            
            // ================================================================
            // 4. TARIF HOTEL LUAR DAERAH LUAR KABUPATEN (Halaman 3 & 4)
            // ================================================================
            
            // ACEH
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Aceh Kepala Daerah/Ketua DPRD/Pejabat Eselon I',
                'provinsi_tujuan' => 'ACEH',
                'satuan' => 'OH',
                'besaran_biaya' => 4420000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Aceh Anggota DPRD/Pejabat Eselon II',
                'provinsi_tujuan' => 'ACEH',
                'satuan' => 'OH',
                'besaran_biaya' => 3526000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Aceh Pejabat Eselon III/Golongan IV',
                'provinsi_tujuan' => 'ACEH',
                'satuan' => 'OH',
                'besaran_biaya' => 1294000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Aceh Pejabat Eselon IV/Golongan III',
                'provinsi_tujuan' => 'ACEH',
                'satuan' => 'OH',
                'besaran_biaya' => 556000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Aceh Golongan II/I',
                'provinsi_tujuan' => 'ACEH',
                'satuan' => 'OH',
                'besaran_biaya' => 556000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I'
            ]),
            
            // ================================================================
            // 5. SATUAN BIAYA TRANSPORTASI DARAT DALAM NEGERI (Halaman 5)
            // ================================================================
            
            // ACEH
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Aceh',
                'provinsi_tujuan' => 'ACEH',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 133000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // SUMATERA UTARA
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Sumatera Utara',
                'provinsi_tujuan' => 'SUMATERA UTARA',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 233000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // RIAU
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Riau',
                'provinsi_tujuan' => 'RIAU',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 94000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // KEPULAUAN RIAU
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Kepulauan Riau',
                'provinsi_tujuan' => 'KEPULAUAN RIAU',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 137000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // JAMBI
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Jambi',
                'provinsi_tujuan' => 'JAMBI',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 147000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // SUMATERA BARAT
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Sumatera Barat',
                'provinsi_tujuan' => 'SUMATERA BARAT',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 100000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // SUMATERA SELATAN
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Sumatera Selatan',
                'provinsi_tujuan' => 'SUMATERA SELATAN',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 128000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // LAMPUNG
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Lampung',
                'provinsi_tujuan' => 'LAMPUNG',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 167000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // BENGKULU
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Bengkulu',
                'provinsi_tujuan' => 'BENGKULU',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 109000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // BANGKA BELITUNG
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Bangka Belitung',
                'provinsi_tujuan' => 'BANGKA BELITUNG',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 90000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // BANTEN
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Banten',
                'provinsi_tujuan' => 'BANTEN',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 100000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // JAWA BARAT
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Jawa Barat',
                'provinsi_tujuan' => 'JAWA BARAT',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 166000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // D.K.I. JAKARTA
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi D.K.I. Jakarta',
                'provinsi_tujuan' => 'D.K.I. JAKARTA',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 256000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // JAWA TENGAH
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Jawa Tengah',
                'provinsi_tujuan' => 'JAWA TENGAH',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 75000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // D.I. YOGYAKARTA
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi D.I. Yogyakarta',
                'provinsi_tujuan' => 'D.I. YOGYAKARTA',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 118000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // JAWA TIMUR
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Jawa Timur',
                'provinsi_tujuan' => 'JAWA TIMUR',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 194000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // BALI
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Bali',
                'provinsi_tujuan' => 'BALI',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 159000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // NUSA TENGGARA BARAT
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Nusa Tenggara Barat',
                'provinsi_tujuan' => 'NUSA TENGGARA BARAT',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 231000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // NUSA TENGGARA TIMUR
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Nusa Tenggara Timur',
                'provinsi_tujuan' => 'NUSA TENGGARA TIMUR',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 108000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // KALIMANTAN BARAT
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Kalimantan Barat',
                'provinsi_tujuan' => 'KALIMANTAN BARAT',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 135000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // KALIMANTAN TENGAH
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Kalimantan Tengah',
                'provinsi_tujuan' => 'KALIMANTAN TENGAH',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 111000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // KALIMANTAN SELATAN
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Kalimantan Selatan',
                'provinsi_tujuan' => 'KALIMANTAN SELATAN',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 150000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // KALIMANTAN TIMUR
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Kalimantan Timur',
                'provinsi_tujuan' => 'KALIMANTAN TIMUR',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 450000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // KALIMANTAN UTARA
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Kalimantan Utara',
                'provinsi_tujuan' => 'KALIMANTAN UTARA',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 102000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // SULAWESI UTARA
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Sulawesi Utara',
                'provinsi_tujuan' => 'SULAWESI UTARA',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 138000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // GORONTALO
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Gorontalo',
                'provinsi_tujuan' => 'GORONTALO',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 240000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // SULAWESI BARAT
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Sulawesi Barat',
                'provinsi_tujuan' => 'SULAWESI BARAT',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 313000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // SULAWESI SELATAN
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Sulawesi Selatan',
                'provinsi_tujuan' => 'SULAWESI SELATAN',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 145000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // SULAWESI TENGAH
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Sulawesi Tengah',
                'provinsi_tujuan' => 'SULAWESI TENGAH',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 165000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // SULAWESI TENGGARA
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Sulawesi Tenggara',
                'provinsi_tujuan' => 'SULAWESI TENGGARA',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 171000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // MALUKU
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Maluku',
                'provinsi_tujuan' => 'MALUKU',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 135000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // MALUKU UTARA
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Maluku Utara',
                'provinsi_tujuan' => 'MALUKU UTARA',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 135000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // PAPUA
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Papua',
                'provinsi_tujuan' => 'PAPUA',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 135000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // PAPUA BARAT
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT_TAKSI',
                'uraian_biaya' => 'Taksi Papua Barat',
                'provinsi_tujuan' => 'PAPUA BARAT',
                'satuan' => 'Orang/Kali',
                'besaran_biaya' => 135000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'Semua'
            ]),
            
            // ================================================================
            // DATA SBU TAMBAHAN YANG BELUM ADA
            // ================================================================
            
            // PENGINAPAN DKI JAKARTA
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel DKI Jakarta Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'D.K.I. JAKARTA',
                'satuan' => 'OH',
                'besaran_biaya' => 1200000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel DKI Jakarta Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'D.K.I. JAKARTA',
                'satuan' => 'OH',
                'besaran_biaya' => 1000000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel DKI Jakarta Eselon III/Golongan IV',
                'provinsi_tujuan' => 'D.K.I. JAKARTA',
                'satuan' => 'OH',
                'besaran_biaya' => 800000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel DKI Jakarta Eselon IV/Golongan III',
                'provinsi_tujuan' => 'D.K.I. JAKARTA',
                'satuan' => 'OH',
                'besaran_biaya' => 600000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel DKI Jakarta Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'D.K.I. JAKARTA',
                'satuan' => 'OH',
                'besaran_biaya' => 500000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // REPRESENTASI UNTUK TINGKAT JABATAN YANG LEBIH RENDAH
            array_merge($defaultKeys, [
                'kategori_biaya' => 'REPRESENTASI',
                'uraian_biaya' => 'Pejabat Eselon III/Golongan IV Luar Kabupaten',
                'satuan' => 'OH',
                'besaran_biaya' => 100000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'REPRESENTASI',
                'uraian_biaya' => 'Pejabat Eselon III/Golongan IV Dalam Kabupaten',
                'satuan' => 'OH',
                'besaran_biaya' => 50000,
                'tipe_perjalanan' => 'DALAM_KABUPATEN_LEBIH_8_JAM',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'REPRESENTASI',
                'uraian_biaya' => 'Pejabat Eselon IV/Golongan III Luar Kabupaten',
                'satuan' => 'OH',
                'besaran_biaya' => 75000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'REPRESENTASI',
                'uraian_biaya' => 'Pejabat Eselon IV/Golongan III Dalam Kabupaten',
                'satuan' => 'OH',
                'besaran_biaya' => 40000,
                'tipe_perjalanan' => 'DALAM_KABUPATEN_LEBIH_8_JAM',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'REPRESENTASI',
                'uraian_biaya' => 'Golongan II/I & Non ASN Luar Kabupaten',
                'satuan' => 'OH',
                'besaran_biaya' => 50000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'REPRESENTASI',
                'uraian_biaya' => 'Golongan II/I & Non ASN Dalam Kabupaten',
                'satuan' => 'OH',
                'besaran_biaya' => 25000,
                'tipe_perjalanan' => 'DALAM_KABUPATEN_LEBIH_8_JAM',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // ================================================================
            // DATA PENGINAPAN UNTUK SEMUA PROVINSI YANG BELUM ADA
            // ================================================================
            
            // SUMATERA UTARA
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sumatera Utara Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'SUMATERA UTARA',
                'satuan' => 'OH',
                'besaran_biaya' => 1000000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sumatera Utara Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'SUMATERA UTARA',
                'satuan' => 'OH',
                'besaran_biaya' => 800000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sumatera Utara Eselon III/Golongan IV',
                'provinsi_tujuan' => 'SUMATERA UTARA',
                'satuan' => 'OH',
                'besaran_biaya' => 600000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sumatera Utara Eselon IV/Golongan III',
                'provinsi_tujuan' => 'SUMATERA UTARA',
                'satuan' => 'OH',
                'besaran_biaya' => 450000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sumatera Utara Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'SUMATERA UTARA',
                'satuan' => 'OH',
                'besaran_biaya' => 350000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // RIAU
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Riau Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'RIAU',
                'satuan' => 'OH',
                'besaran_biaya' => 1000000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Riau Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'RIAU',
                'satuan' => 'OH',
                'besaran_biaya' => 800000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Riau Eselon III/Golongan IV',
                'provinsi_tujuan' => 'RIAU',
                'satuan' => 'OH',
                'besaran_biaya' => 600000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Riau Eselon IV/Golongan III',
                'provinsi_tujuan' => 'RIAU',
                'satuan' => 'OH',
                'besaran_biaya' => 450000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Riau Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'RIAU',
                'satuan' => 'OH',
                'besaran_biaya' => 350000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // KEPULAUAN RIAU
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kepulauan Riau Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'KEPULAUAN RIAU',
                'satuan' => 'OH',
                'besaran_biaya' => 1000000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kepulauan Riau Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'KEPULAUAN RIAU',
                'satuan' => 'OH',
                'besaran_biaya' => 800000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kepulauan Riau Eselon III/Golongan IV',
                'provinsi_tujuan' => 'KEPULAUAN RIAU',
                'satuan' => 'OH',
                'besaran_biaya' => 600000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kepulauan Riau Eselon IV/Golongan III',
                'provinsi_tujuan' => 'KEPULAUAN RIAU',
                'satuan' => 'OH',
                'besaran_biaya' => 450000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kepulauan Riau Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'KEPULAUAN RIAU',
                'satuan' => 'OH',
                'besaran_biaya' => 350000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // JAMBI
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Jambi Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'JAMBI',
                'satuan' => 'OH',
                'besaran_biaya' => 1000000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Jambi Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'JAMBI',
                'satuan' => 'OH',
                'besaran_biaya' => 800000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Jambi Eselon III/Golongan IV',
                'provinsi_tujuan' => 'JAMBI',
                'satuan' => 'OH',
                'besaran_biaya' => 600000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Jambi Eselon IV/Golongan III',
                'provinsi_tujuan' => 'JAMBI',
                'satuan' => 'OH',
                'besaran_biaya' => 450000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Jambi Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'JAMBI',
                'satuan' => 'OH',
                'besaran_biaya' => 350000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // SUMATERA BARAT
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sumatera Barat Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'SUMATERA BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 1200000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sumatera Barat Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'SUMATERA BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 900000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sumatera Barat Eselon III/Golongan IV',
                'provinsi_tujuan' => 'SUMATERA BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 650000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sumatera Barat Eselon IV/Golongan III',
                'provinsi_tujuan' => 'SUMATERA BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 500000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sumatera Barat Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'SUMATERA BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 400000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // SUMATERA SELATAN
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sumatera Selatan Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'SUMATERA SELATAN',
                'satuan' => 'OH',
                'besaran_biaya' => 1400000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sumatera Selatan Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'SUMATERA SELATAN',
                'satuan' => 'OH',
                'besaran_biaya' => 1000000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sumatera Selatan Eselon III/Golongan IV',
                'provinsi_tujuan' => 'SUMATERA SELATAN',
                'satuan' => 'OH',
                'besaran_biaya' => 750000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sumatera Selatan Eselon IV/Golongan III',
                'provinsi_tujuan' => 'SUMATERA SELATAN',
                'satuan' => 'OH',
                'besaran_biaya' => 600000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sumatera Selatan Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'SUMATERA SELATAN',
                'satuan' => 'OH',
                'besaran_biaya' => 500000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // LAMPUNG
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Lampung Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'LAMPUNG',
                'satuan' => 'OH',
                'besaran_biaya' => 1100000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Lampung Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'LAMPUNG',
                'satuan' => 'OH',
                'besaran_biaya' => 850000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Lampung Eselon III/Golongan IV',
                'provinsi_tujuan' => 'LAMPUNG',
                'satuan' => 'OH',
                'besaran_biaya' => 650000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Lampung Eselon IV/Golongan III',
                'provinsi_tujuan' => 'LAMPUNG',
                'satuan' => 'OH',
                'besaran_biaya' => 500000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Lampung Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'LAMPUNG',
                'satuan' => 'OH',
                'besaran_biaya' => 400000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // BENGKULU
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Bengkulu Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'BENGKULU',
                'satuan' => 'OH',
                'besaran_biaya' => 950000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Bengkulu Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'BENGKULU',
                'satuan' => 'OH',
                'besaran_biaya' => 750000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Bengkulu Eselon III/Golongan IV',
                'provinsi_tujuan' => 'BENGKULU',
                'satuan' => 'OH',
                'besaran_biaya' => 600000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Bengkulu Eselon IV/Golongan III',
                'provinsi_tujuan' => 'BENGKULU',
                'satuan' => 'OH',
                'besaran_biaya' => 450000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Bengkulu Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'BENGKULU',
                'satuan' => 'OH',
                'besaran_biaya' => 350000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // BANGKA BELITUNG
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Bangka Belitung Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'BANGKA BELITUNG',
                'satuan' => 'OH',
                'besaran_biaya' => 1500000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Bangka Belitung Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'BANGKA BELITUNG',
                'satuan' => 'OH',
                'besaran_biaya' => 1100000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Bangka Belitung Eselon III/Golongan IV',
                'provinsi_tujuan' => 'BANGKA BELITUNG',
                'satuan' => 'OH',
                'besaran_biaya' => 850000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Bangka Belitung Eselon IV/Golongan III',
                'provinsi_tujuan' => 'BANGKA BELITUNG',
                'satuan' => 'OH',
                'besaran_biaya' => 650000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Bangka Belitung Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'BANGKA BELITUNG',
                'satuan' => 'OH',
                'besaran_biaya' => 500000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // BANTEN
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Banten Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'BANTEN',
                'satuan' => 'OH',
                'besaran_biaya' => 1700000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Banten Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'BANTEN',
                'satuan' => 'OH',
                'besaran_biaya' => 1300000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Banten Eselon III/Golongan IV',
                'provinsi_tujuan' => 'BANTEN',
                'satuan' => 'OH',
                'besaran_biaya' => 1000000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Banten Eselon IV/Golongan III',
                'provinsi_tujuan' => 'BANTEN',
                'satuan' => 'OH',
                'besaran_biaya' => 800000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Banten Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'BANTEN',
                'satuan' => 'OH',
                'besaran_biaya' => 650000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // JAWA BARAT
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Jawa Barat Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'JAWA BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 1600000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Jawa Barat Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'JAWA BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 1200000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Jawa Barat Eselon III/Golongan IV',
                'provinsi_tujuan' => 'JAWA BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 950000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Jawa Barat Eselon IV/Golongan III',
                'provinsi_tujuan' => 'JAWA BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 750000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Jawa Barat Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'JAWA BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 600000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // JAWA TENGAH
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Jawa Tengah Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'JAWA TENGAH',
                'satuan' => 'OH',
                'besaran_biaya' => 1400000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Jawa Tengah Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'JAWA TENGAH',
                'satuan' => 'OH',
                'besaran_biaya' => 1050000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Jawa Tengah Eselon III/Golongan IV',
                'provinsi_tujuan' => 'JAWA TENGAH',
                'satuan' => 'OH',
                'besaran_biaya' => 800000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Jawa Tengah Eselon IV/Golongan III',
                'provinsi_tujuan' => 'JAWA TENGAH',
                'satuan' => 'OH',
                'besaran_biaya' => 650000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Jawa Tengah Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'JAWA TENGAH',
                'satuan' => 'OH',
                'besaran_biaya' => 500000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // D.I. YOGYAKARTA
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel D.I. Yogyakarta Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'D.I. YOGYAKARTA',
                'satuan' => 'OH',
                'besaran_biaya' => 1500000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel D.I. Yogyakarta Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'D.I. YOGYAKARTA',
                'satuan' => 'OH',
                'besaran_biaya' => 1150000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel D.I. Yogyakarta Eselon III/Golongan IV',
                'provinsi_tujuan' => 'D.I. YOGYAKARTA',
                'satuan' => 'OH',
                'besaran_biaya' => 900000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel D.I. Yogyakarta Eselon IV/Golongan III',
                'provinsi_tujuan' => 'D.I. YOGYAKARTA',
                'satuan' => 'OH',
                'besaran_biaya' => 700000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel D.I. Yogyakarta Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'D.I. YOGYAKARTA',
                'satuan' => 'OH',
                'besaran_biaya' => 550000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // JAWA TIMUR
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Jawa Timur Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'JAWA TIMUR',
                'satuan' => 'OH',
                'besaran_biaya' => 1300000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Jawa Timur Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'JAWA TIMUR',
                'satuan' => 'OH',
                'besaran_biaya' => 1000000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Jawa Timur Eselon III/Golongan IV',
                'provinsi_tujuan' => 'JAWA TIMUR',
                'satuan' => 'OH',
                'besaran_biaya' => 750000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Jawa Timur Eselon IV/Golongan III',
                'provinsi_tujuan' => 'JAWA TIMUR',
                'satuan' => 'OH',
                'besaran_biaya' => 600000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Jawa Timur Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'JAWA TIMUR',
                'satuan' => 'OH',
                'besaran_biaya' => 450000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // BALI
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Bali Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'BALI',
                'satuan' => 'OH',
                'besaran_biaya' => 1800000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Bali Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'BALI',
                'satuan' => 'OH',
                'besaran_biaya' => 1350000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Bali Eselon III/Golongan IV',
                'provinsi_tujuan' => 'BALI',
                'satuan' => 'OH',
                'besaran_biaya' => 1050000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Bali Eselon IV/Golongan III',
                'provinsi_tujuan' => 'BALI',
                'satuan' => 'OH',
                'besaran_biaya' => 850000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Bali Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'BALI',
                'satuan' => 'OH',
                'besaran_biaya' => 650000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // NUSA TENGGARA BARAT
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Nusa Tenggara Barat Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'NUSA TENGGARA BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 1200000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Nusa Tenggara Barat Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'NUSA TENGGARA BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 900000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Nusa Tenggara Barat Eselon III/Golongan IV',
                'provinsi_tujuan' => 'NUSA TENGGARA BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 700000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Nusa Tenggara Barat Eselon IV/Golongan III',
                'provinsi_tujuan' => 'NUSA TENGGARA BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 550000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Nusa Tenggara Barat Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'NUSA TENGGARA BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 450000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // NUSA TENGGARA TIMUR
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Nusa Tenggara Timur Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'NUSA TENGGARA TIMUR',
                'satuan' => 'OH',
                'besaran_biaya' => 1100000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Nusa Tenggara Timur Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'NUSA TENGGARA TIMUR',
                'satuan' => 'OH',
                'besaran_biaya' => 850000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Nusa Tenggara Timur Eselon III/Golongan IV',
                'provinsi_tujuan' => 'NUSA TENGGARA TIMUR',
                'satuan' => 'OH',
                'besaran_biaya' => 650000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Nusa Tenggara Timur Eselon IV/Golongan III',
                'provinsi_tujuan' => 'NUSA TENGGARA TIMUR',
                'satuan' => 'OH',
                'besaran_biaya' => 500000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Nusa Tenggara Timur Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'NUSA TENGGARA TIMUR',
                'satuan' => 'OH',
                'besaran_biaya' => 400000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // KALIMANTAN BARAT
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kalimantan Barat Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'KALIMANTAN BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 1000000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kalimantan Barat Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'KALIMANTAN BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 750000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kalimantan Barat Eselon III/Golongan IV',
                'provinsi_tujuan' => 'KALIMANTAN BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 600000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kalimantan Barat Eselon IV/Golongan III',
                'provinsi_tujuan' => 'KALIMANTAN BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 450000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kalimantan Barat Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'KALIMANTAN BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 350000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // KALIMANTAN TENGAH
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kalimantan Tengah Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'KALIMANTAN TENGAH',
                'satuan' => 'OH',
                'besaran_biaya' => 1150000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kalimantan Tengah Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'KALIMANTAN TENGAH',
                'satuan' => 'OH',
                'besaran_biaya' => 850000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kalimantan Tengah Eselon III/Golongan IV',
                'provinsi_tujuan' => 'KALIMANTAN TENGAH',
                'satuan' => 'OH',
                'besaran_biaya' => 650000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kalimantan Tengah Eselon IV/Golongan III',
                'provinsi_tujuan' => 'KALIMANTAN TENGAH',
                'satuan' => 'OH',
                'besaran_biaya' => 500000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kalimantan Tengah Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'KALIMANTAN TENGAH',
                'satuan' => 'OH',
                'besaran_biaya' => 400000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // KALIMANTAN SELATAN
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kalimantan Selatan Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'KALIMANTAN SELATAN',
                'satuan' => 'OH',
                'besaran_biaya' => 1100000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kalimantan Selatan Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'KALIMANTAN SELATAN',
                'satuan' => 'OH',
                'besaran_biaya' => 800000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kalimantan Selatan Eselon III/Golongan IV',
                'provinsi_tujuan' => 'KALIMANTAN SELATAN',
                'satuan' => 'OH',
                'besaran_biaya' => 600000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kalimantan Selatan Eselon IV/Golongan III',
                'provinsi_tujuan' => 'KALIMANTAN SELATAN',
                'satuan' => 'OH',
                'besaran_biaya' => 450000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kalimantan Selatan Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'KALIMANTAN SELATAN',
                'satuan' => 'OH',
                'besaran_biaya' => 350000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // KALIMANTAN TIMUR
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kalimantan Timur Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'KALIMANTAN TIMUR',
                'satuan' => 'OH',
                'besaran_biaya' => 1300000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kalimantan Timur Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'KALIMANTAN TIMUR',
                'satuan' => 'OH',
                'besaran_biaya' => 1000000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kalimantan Timur Eselon III/Golongan IV',
                'provinsi_tujuan' => 'KALIMANTAN TIMUR',
                'satuan' => 'OH',
                'besaran_biaya' => 750000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kalimantan Timur Eselon IV/Golongan III',
                'provinsi_tujuan' => 'KALIMANTAN TIMUR',
                'satuan' => 'OH',
                'besaran_biaya' => 600000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kalimantan Timur Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'KALIMANTAN TIMUR',
                'satuan' => 'OH',
                'besaran_biaya' => 450000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // KALIMANTAN UTARA
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kalimantan Utara Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'KALIMANTAN UTARA',
                'satuan' => 'OH',
                'besaran_biaya' => 1300000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kalimantan Utara Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'KALIMANTAN UTARA',
                'satuan' => 'OH',
                'besaran_biaya' => 1000000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kalimantan Utara Eselon III/Golongan IV',
                'provinsi_tujuan' => 'KALIMANTAN UTARA',
                'satuan' => 'OH',
                'besaran_biaya' => 750000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kalimantan Utara Eselon IV/Golongan III',
                'provinsi_tujuan' => 'KALIMANTAN UTARA',
                'satuan' => 'OH',
                'besaran_biaya' => 600000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Kalimantan Utara Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'KALIMANTAN UTARA',
                'satuan' => 'OH',
                'besaran_biaya' => 450000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // SULAWESI UTARA
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sulawesi Utara Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'SULAWESI UTARA',
                'satuan' => 'OH',
                'besaran_biaya' => 1000000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sulawesi Utara Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'SULAWESI UTARA',
                'satuan' => 'OH',
                'besaran_biaya' => 750000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sulawesi Utara Eselon III/Golongan IV',
                'provinsi_tujuan' => 'SULAWESI UTARA',
                'satuan' => 'OH',
                'besaran_biaya' => 600000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sulawesi Utara Eselon IV/Golongan III',
                'provinsi_tujuan' => 'SULAWESI UTARA',
                'satuan' => 'OH',
                'besaran_biaya' => 450000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sulawesi Utara Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'SULAWESI UTARA',
                'satuan' => 'OH',
                'besaran_biaya' => 350000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // GORONTALO
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Gorontalo Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'GORONTALO',
                'satuan' => 'OH',
                'besaran_biaya' => 1000000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Gorontalo Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'GORONTALO',
                'satuan' => 'OH',
                'besaran_biaya' => 750000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Gorontalo Eselon III/Golongan IV',
                'provinsi_tujuan' => 'GORONTALO',
                'satuan' => 'OH',
                'besaran_biaya' => 600000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Gorontalo Eselon IV/Golongan III',
                'provinsi_tujuan' => 'GORONTALO',
                'satuan' => 'OH',
                'besaran_biaya' => 450000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Gorontalo Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'GORONTALO',
                'satuan' => 'OH',
                'besaran_biaya' => 350000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // SULAWESI BARAT
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sulawesi Barat Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'SULAWESI BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 1200000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sulawesi Barat Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'SULAWESI BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 900000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sulawesi Barat Eselon III/Golongan IV',
                'provinsi_tujuan' => 'SULAWESI BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 700000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sulawesi Barat Eselon IV/Golongan III',
                'provinsi_tujuan' => 'SULAWESI BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 550000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sulawesi Barat Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'SULAWESI BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 450000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // SULAWESI SELATAN
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sulawesi Selatan Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'SULAWESI SELATAN',
                'satuan' => 'OH',
                'besaran_biaya' => 1300000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sulawesi Selatan Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'SULAWESI SELATAN',
                'satuan' => 'OH',
                'besaran_biaya' => 1000000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sulawesi Selatan Eselon III/Golongan IV',
                'provinsi_tujuan' => 'SULAWESI SELATAN',
                'satuan' => 'OH',
                'besaran_biaya' => 750000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sulawesi Selatan Eselon IV/Golongan III',
                'provinsi_tujuan' => 'SULAWESI SELATAN',
                'satuan' => 'OH',
                'besaran_biaya' => 600000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sulawesi Selatan Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'SULAWESI SELATAN',
                'satuan' => 'OH',
                'besaran_biaya' => 450000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // SULAWESI TENGAH
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sulawesi Tengah Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'SULAWESI TENGAH',
                'satuan' => 'OH',
                'besaran_biaya' => 1000000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sulawesi Tengah Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'SULAWESI TENGAH',
                'satuan' => 'OH',
                'besaran_biaya' => 750000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sulawesi Tengah Eselon III/Golongan IV',
                'provinsi_tujuan' => 'SULAWESI TENGAH',
                'satuan' => 'OH',
                'besaran_biaya' => 600000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sulawesi Tengah Eselon IV/Golongan III',
                'provinsi_tujuan' => 'SULAWESI TENGAH',
                'satuan' => 'OH',
                'besaran_biaya' => 450000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sulawesi Tengah Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'SULAWESI TENGAH',
                'satuan' => 'OH',
                'besaran_biaya' => 350000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // SULAWESI TENGGARA
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sulawesi Tenggara Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'SULAWESI TENGGARA',
                'satuan' => 'OH',
                'besaran_biaya' => 1000000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sulawesi Tenggara Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'SULAWESI TENGGARA',
                'satuan' => 'OH',
                'besaran_biaya' => 750000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sulawesi Tenggara Eselon III/Golongan IV',
                'provinsi_tujuan' => 'SULAWESI TENGGARA',
                'satuan' => 'OH',
                'besaran_biaya' => 600000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sulawesi Tenggara Eselon IV/Golongan III',
                'provinsi_tujuan' => 'SULAWESI TENGGARA',
                'satuan' => 'OH',
                'besaran_biaya' => 450000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Sulawesi Tenggara Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'SULAWESI TENGGARA',
                'satuan' => 'OH',
                'besaran_biaya' => 350000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // MALUKU
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Maluku Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'MALUKU',
                'satuan' => 'OH',
                'besaran_biaya' => 1000000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Maluku Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'MALUKU',
                'satuan' => 'OH',
                'besaran_biaya' => 750000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Maluku Eselon III/Golongan IV',
                'provinsi_tujuan' => 'MALUKU',
                'satuan' => 'OH',
                'besaran_biaya' => 600000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Maluku Eselon IV/Golongan III',
                'provinsi_tujuan' => 'MALUKU',
                'satuan' => 'OH',
                'besaran_biaya' => 450000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Maluku Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'MALUKU',
                'satuan' => 'OH',
                'besaran_biaya' => 350000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // MALUKU UTARA
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Maluku Utara Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'MALUKU UTARA',
                'satuan' => 'OH',
                'besaran_biaya' => 1300000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Maluku Utara Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'MALUKU UTARA',
                'satuan' => 'OH',
                'besaran_biaya' => 1000000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Maluku Utara Eselon III/Golongan IV',
                'provinsi_tujuan' => 'MALUKU UTARA',
                'satuan' => 'OH',
                'besaran_biaya' => 750000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Maluku Utara Eselon IV/Golongan III',
                'provinsi_tujuan' => 'MALUKU UTARA',
                'satuan' => 'OH',
                'besaran_biaya' => 600000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Maluku Utara Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'MALUKU UTARA',
                'satuan' => 'OH',
                'besaran_biaya' => 450000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // PAPUA
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Papua Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'PAPUA',
                'satuan' => 'OH',
                'besaran_biaya' => 1800000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Papua Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'PAPUA',
                'satuan' => 'OH',
                'besaran_biaya' => 1350000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Papua Eselon III/Golongan IV',
                'provinsi_tujuan' => 'PAPUA',
                'satuan' => 'OH',
                'besaran_biaya' => 1050000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Papua Eselon IV/Golongan III',
                'provinsi_tujuan' => 'PAPUA',
                'satuan' => 'OH',
                'besaran_biaya' => 850000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Papua Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'PAPUA',
                'satuan' => 'OH',
                'besaran_biaya' => 650000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // PAPUA BARAT
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Papua Barat Kepala Daerah/Eselon I',
                'provinsi_tujuan' => 'PAPUA BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 1600000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Papua Barat Anggota DPRD/Eselon II',
                'provinsi_tujuan' => 'PAPUA BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 1200000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Papua Barat Eselon III/Golongan IV',
                'provinsi_tujuan' => 'PAPUA BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 950000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_III_GOL_IV'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Papua Barat Eselon IV/Golongan III',
                'provinsi_tujuan' => 'PAPUA BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 750000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'ESELON_IV_GOL_III'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'PENGINAPAN',
                'uraian_biaya' => 'Hotel Papua Barat Golongan II/I & Non ASN',
                'provinsi_tujuan' => 'PAPUA BARAT',
                'satuan' => 'OH',
                'besaran_biaya' => 600000,
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN',
                'tingkat_pejabat_atau_golongan' => 'GOL_II_I_NON_ASN'
            ]),
            
            // ================================================================
            // TRANSPORTASI DARAT SIAK SRI INDRAPURA KE KECAMATAN
            // ================================================================
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT',
                'uraian_biaya' => 'Transportasi Darat Siak Sri Indrapura - Dayun',
                'satuan' => 'Perjalanan',
                'besaran_biaya' => 65000,
                'provinsi_tujuan' => 'RIAU',
                'kota_tujuan' => 'SIAK',
                'tingkat_pejabat_atau_golongan' => 'Semua',
                'tipe_perjalanan' => 'DALAM_KABUPATEN_LEBIH_8_JAM'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT',
                'uraian_biaya' => 'Transportasi Darat Siak Sri Indrapura - Koto Gasib',
                'satuan' => 'Perjalanan',
                'besaran_biaya' => 135000,
                'provinsi_tujuan' => 'RIAU',
                'kota_tujuan' => 'SIAK',
                'tingkat_pejabat_atau_golongan' => 'Semua',
                'tipe_perjalanan' => 'DALAM_KABUPATEN_LEBIH_8_JAM'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT',
                'uraian_biaya' => 'Transportasi Darat Siak Sri Indrapura - Lubuk Dalam',
                'satuan' => 'Perjalanan',
                'besaran_biaya' => 135000,
                'provinsi_tujuan' => 'RIAU',
                'kota_tujuan' => 'SIAK',
                'tingkat_pejabat_atau_golongan' => 'Semua',
                'tipe_perjalanan' => 'DALAM_KABUPATEN_LEBIH_8_JAM'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT',
                'uraian_biaya' => 'Transportasi Darat Siak Sri Indrapura - Kerinci Kanan',
                'satuan' => 'Perjalanan',
                'besaran_biaya' => 165000,
                'provinsi_tujuan' => 'RIAU',
                'kota_tujuan' => 'SIAK',
                'tingkat_pejabat_atau_golongan' => 'Semua',
                'tipe_perjalanan' => 'DALAM_KABUPATEN_LEBIH_8_JAM'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT',
                'uraian_biaya' => 'Transportasi Darat Siak Sri Indrapura - Tualang',
                'satuan' => 'Perjalanan',
                'besaran_biaya' => 165000,
                'provinsi_tujuan' => 'RIAU',
                'kota_tujuan' => 'SIAK',
                'tingkat_pejabat_atau_golongan' => 'Semua',
                'tipe_perjalanan' => 'DALAM_KABUPATEN_LEBIH_8_JAM'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT',
                'uraian_biaya' => 'Transportasi Darat Siak Sri Indrapura - Minas',
                'satuan' => 'Perjalanan',
                'besaran_biaya' => 210000,
                'provinsi_tujuan' => 'RIAU',
                'kota_tujuan' => 'SIAK',
                'tingkat_pejabat_atau_golongan' => 'Semua',
                'tipe_perjalanan' => 'DALAM_KABUPATEN_LEBIH_8_JAM'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT',
                'uraian_biaya' => 'Transportasi Darat Siak Sri Indrapura - Kandis',
                'satuan' => 'Perjalanan',
                'besaran_biaya' => 235000,
                'provinsi_tujuan' => 'RIAU',
                'kota_tujuan' => 'SIAK',
                'tingkat_pejabat_atau_golongan' => 'Semua',
                'tipe_perjalanan' => 'DALAM_KABUPATEN_LEBIH_8_JAM'
            ]),
            
            // ================================================================
            // TRANSPORTASI KECAMATAN KE DESA/KAMPUNG (PULANG PERGI)
            // ================================================================
            // Siak - Kampung Rempak (0-5 Km)
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT',
                'uraian_biaya' => 'Transportasi Kecamatan Siak ke Kampung Rempak (0-5 Km)',
                'satuan' => 'Perjalanan',
                'besaran_biaya' => 50000,
                'provinsi_tujuan' => 'RIAU',
                'kota_tujuan' => 'SIAK',
                'tingkat_pejabat_atau_golongan' => 'Semua',
                'tipe_perjalanan' => 'DALAM_KABUPATEN_LEBIH_8_JAM'
            ]),
            // Siak - Kampung Dalam Langsat (7 Km)
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT',
                'uraian_biaya' => 'Transportasi Kecamatan Siak ke Kampung Dalam Langsat (7 Km)',
                'satuan' => 'Perjalanan',
                'besaran_biaya' => 50000,
                'provinsi_tujuan' => 'RIAU',
                'kota_tujuan' => 'SIAK',
                'tingkat_pejabat_atau_golongan' => 'Semua',
                'tipe_perjalanan' => 'DALAM_KABUPATEN_LEBIH_8_JAM'
            ]),
            // Mempura - Benteng Hilir (6 Km)
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT',
                'uraian_biaya' => 'Transportasi Kecamatan Mempura ke Benteng Hilir (6 Km)',
                'satuan' => 'Perjalanan',
                'besaran_biaya' => 50000,
                'provinsi_tujuan' => 'RIAU',
                'kota_tujuan' => 'SIAK',
                'tingkat_pejabat_atau_golongan' => 'Semua',
                'tipe_perjalanan' => 'DALAM_KABUPATEN_LEBIH_8_JAM'
            ]),
            // Bunga Raya - Bunga Raya Jangkang Permai (7 Km)
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT',
                'uraian_biaya' => 'Transportasi Kecamatan Bunga Raya ke Bunga Raya Jangkang Permai (7 Km)',
                'satuan' => 'Perjalanan',
                'besaran_biaya' => 50000,
                'provinsi_tujuan' => 'RIAU',
                'kota_tujuan' => 'SIAK',
                'tingkat_pejabat_atau_golongan' => 'Semua',
                'tipe_perjalanan' => 'DALAM_KABUPATEN_LEBIH_8_JAM'
            ]),
            
            // ================================================================
            // TRANSPORTASI DARAT DARI SIAK SRI INDRAPURA KE KABUPATEN/KOTA LAIN (PP)
            // ================================================================
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT',
                'uraian_biaya' => 'Transportasi Darat Siak - Pekanbaru (PP)',
                'satuan' => 'OT',
                'besaran_biaya' => 350000,
                'provinsi_tujuan' => 'RIAU',
                'tingkat_pejabat_atau_golongan' => 'Semua',
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT',
                'uraian_biaya' => 'Transportasi Darat Siak - Kampar (PP)',
                'satuan' => 'OT',
                'besaran_biaya' => 550000,
                'provinsi_tujuan' => 'RIAU',
                'tingkat_pejabat_atau_golongan' => 'Semua',
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT',
                'uraian_biaya' => 'Transportasi Darat Siak - Rokan Hulu (PP)',
                'satuan' => 'OT',
                'besaran_biaya' => 672000,
                'provinsi_tujuan' => 'RIAU',
                'tingkat_pejabat_atau_golongan' => 'Semua',
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT',
                'uraian_biaya' => 'Transportasi Darat Siak - Rokan Hilir (PP)',
                'satuan' => 'OT',
                'besaran_biaya' => 700000,
                'provinsi_tujuan' => 'RIAU',
                'tingkat_pejabat_atau_golongan' => 'Semua',
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT',
                'uraian_biaya' => 'Transportasi Darat Siak - Bengkalis (PP)',
                'satuan' => 'OT',
                'besaran_biaya' => 460000,
                'provinsi_tujuan' => 'RIAU',
                'tingkat_pejabat_atau_golongan' => 'Semua',
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT',
                'uraian_biaya' => 'Transportasi Darat Siak - Dumai (PP)',
                'satuan' => 'OT',
                'besaran_biaya' => 725000,
                'provinsi_tujuan' => 'RIAU',
                'tingkat_pejabat_atau_golongan' => 'Semua',
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT',
                'uraian_biaya' => 'Transportasi Darat Siak - Pelalawan (PP)',
                'satuan' => 'OT',
                'besaran_biaya' => 760000,
                'provinsi_tujuan' => 'RIAU',
                'tingkat_pejabat_atau_golongan' => 'Semua',
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT',
                'uraian_biaya' => 'Transportasi Darat Siak - Indragiri Hulu (PP)',
                'satuan' => 'OT',
                'besaran_biaya' => 650000,
                'provinsi_tujuan' => 'RIAU',
                'tingkat_pejabat_atau_golongan' => 'Semua',
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT',
                'uraian_biaya' => 'Transportasi Darat Siak - Indragiri Hilir (PP)',
                'satuan' => 'OT',
                'besaran_biaya' => 565000,
                'provinsi_tujuan' => 'RIAU',
                'tingkat_pejabat_atau_golongan' => 'Semua',
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_DARAT',
                'uraian_biaya' => 'Transportasi Darat Siak - Kuantan Singingi (PP)',
                'satuan' => 'OT',
                'besaran_biaya' => 650000,
                'provinsi_tujuan' => 'RIAU',
                'tingkat_pejabat_atau_golongan' => 'Semua',
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN'
            ]),
            
            // ================================================================
            // TRANSPORTASI PESAWAT DARI PEKANBARU KE IBUKOTA NEGARA DAN PROVINSI LAIN
            // ================================================================
            // Transportasi PP Bisnis
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_UDARA',
                'uraian_biaya' => 'Transportasi Pesawat Pekanbaru - Aceh (PP Bisnis)',
                'satuan' => 'Perjalanan',
                'besaran_biaya' => 6500000,
                'provinsi_tujuan' => 'ACEH',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I',
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_UDARA',
                'uraian_biaya' => 'Transportasi Pesawat Pekanbaru - Sumatera Utara (PP Bisnis)',
                'satuan' => 'Perjalanan',
                'besaran_biaya' => 6610800,
                'provinsi_tujuan' => 'SUMATERA UTARA',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I',
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_UDARA',
                'uraian_biaya' => 'Transportasi Pesawat Pekanbaru - Sumatera Barat (PP Bisnis)',
                'satuan' => 'Perjalanan',
                'besaran_biaya' => 5535000,
                'provinsi_tujuan' => 'SUMATERA BARAT',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I',
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_UDARA',
                'uraian_biaya' => 'Transportasi Pesawat Pekanbaru - Jambi (PP Bisnis)',
                'satuan' => 'Perjalanan',
                'besaran_biaya' => 7000000,
                'provinsi_tujuan' => 'JAMBI',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I',
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_UDARA',
                'uraian_biaya' => 'Transportasi Pesawat Pekanbaru - Jakarta (PP Bisnis)',
                'satuan' => 'Perjalanan',
                'besaran_biaya' => 5583000,
                'provinsi_tujuan' => 'D.K.I. JAKARTA',
                'tingkat_pejabat_atau_golongan' => 'KEPALA_DAERAH_ESELON_I',
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN'
            ]),
            
            // Transportasi PP Ekonomi untuk tingkat jabatan lainnya
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_UDARA',
                'uraian_biaya' => 'Transportasi Pesawat Pekanbaru - Aceh (PP Ekonomi)',
                'satuan' => 'Perjalanan',
                'besaran_biaya' => 3700000,
                'provinsi_tujuan' => 'ACEH',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II',
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_UDARA',
                'uraian_biaya' => 'Transportasi Pesawat Pekanbaru - Sumatera Utara (PP Ekonomi)',
                'satuan' => 'Perjalanan',
                'besaran_biaya' => 3500000,
                'provinsi_tujuan' => 'SUMATERA UTARA',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II',
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN'
            ]),
            array_merge($defaultKeys, [
                'kategori_biaya' => 'TRANSPORTASI_UDARA',
                'uraian_biaya' => 'Transportasi Pesawat Pekanbaru - Jakarta (PP Ekonomi)',
                'satuan' => 'Perjalanan',
                'besaran_biaya' => 3016000,
                'provinsi_tujuan' => 'D.K.I. JAKARTA',
                'tingkat_pejabat_atau_golongan' => 'ESELON_II',
                'tipe_perjalanan' => 'LUAR_DAERAH_LUAR_KABUPATEN'
            ])
        ];

        // Validasi dan insert data satu per satu
        foreach ($sbuData as $index => $item) {
            try {
                SbuItem::create($item);
                echo "✓ Entry " . ($index + 1) . " berhasil: " . $item['uraian_biaya'] . "\n";
            } catch (\Exception $e) {
                echo "✗ Error pada entry " . ($index + 1) . ": " . $item['uraian_biaya'] . "\n";
                echo "  Error: " . $e->getMessage() . "\n";
                // Tampilkan struktur data untuk debugging
                echo "  Data: " . json_encode($item, JSON_PRETTY_PRINT) . "\n";
            }
        }
    }

}
