<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lapangan',
        'jenis_lapangan',
        'status',
        'deskripsi',
        'harga_per_jam',
    ];

    public function reservasis()
    {
        return $this->hasMany(Reservasi::class);
    }

    public function isTersedia(): bool
    {
        return $this->status === 'tersedia';
    }
}
