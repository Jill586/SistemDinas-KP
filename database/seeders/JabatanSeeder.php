<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jabatan;
use Carbon\Carbon;

class JabatanSeeder extends Seeder
{
    public function run()
    {
        $jabatans = [
            ['nama_jabatan' => 'Kepala Daerah', 'kategori_jabatan' => 'Struktural'],
            ['nama_jabatan' => 'Pimpinan DPRD', 'kategori_jabatan' => 'Struktural'],
            ['nama_jabatan' => 'Pejabat Eselon I', 'kategori_jabatan' => 'Struktural'],
            ['nama_jabatan' => 'Pejabat Eselon II', 'kategori_jabatan' => 'Struktural'],
            ['nama_jabatan' => 'Pejabat Eselon III', 'kategori_jabatan' => 'Struktural'],
            ['nama_jabatan' => 'Pejabat Eselon IV', 'kategori_jabatan' => 'Struktural'],
            ['nama_jabatan' => 'Staff Pelaksana', 'kategori_jabatan' => 'Pelaksana'],
        ];

        foreach ($jabatans as $jabatan) {
            Jabatan::create(array_merge($jabatan, [
                'aktif' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]));
        }
    }
}