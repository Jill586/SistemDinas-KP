<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipPeriode extends Model
{
    protected $table = 'arsip_periode';

    protected $fillable = [
        'nama_periode',
        'total_spt',
        'total_luar_riau',
        'total_dalam_riau',
        'total_siak',
        'total_anggaran',
    ];
}




