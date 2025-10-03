<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_jabatan',
        'kategori_jabatan',
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

    // Scope untuk jabatan aktif
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }
}