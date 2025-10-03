<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Golongan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_golongan',
        'nama_golongan',
        'deskripsi',
        'aktif'
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    // Relasi ke User
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Scope untuk golongan aktif
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }
}