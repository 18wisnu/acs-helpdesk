<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // Buka gembok agar semua kolom bisa diisi
    protected $guarded = []; 

    // Relasi: 1 Pelanggan punya 1 Perangkat (Modem)
    public function device()
    {
        return $this->hasOne(Device::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}