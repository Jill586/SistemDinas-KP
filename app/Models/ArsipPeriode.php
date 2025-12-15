<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipPeriode extends Model
{
    use HasFactory;

    protected $table = 'arsip_periode';

    protected $fillable = [
        'nama_periode',
        'total_spt',
        'total_siak',
        'total_dalam_riau',
        'total_luar_riau',
        'total_anggaran',
        'batas_anggaran',
        'is_active',
    ];
}
