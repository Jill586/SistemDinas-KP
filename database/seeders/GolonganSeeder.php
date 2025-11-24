<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Golongan;
use Carbon\Carbon;

class GolonganSeeder extends Seeder
{
    public function run()
    {
        $golongans = [
            ['kode_golongan' => 'I', 'nama_golongan' => 'Golongan I', 'deskripsi' => 'Golongan I'],
            ['kode_golongan' => 'II', 'nama_golongan' => 'Golongan II', 'deskripsi' => 'Golongan II'],
            ['kode_golongan' => 'III', 'nama_golongan' => 'Golongan III', 'deskripsi' => 'Golongan III'],
            ['kode_golongan' => 'IV', 'nama_golongan' => 'Golongan IV', 'deskripsi' => 'Golongan IV'],
        ];

        foreach ($golongans as $golongan) {
            Golongan::create(array_merge($golongan, [
                'aktif' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]));
        }
    }
}