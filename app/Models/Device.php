<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relasi balik ke Pelanggan
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi balik ke Site
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    // New ODP mapping
    public function odp()
    {
        return $this->belongsTo(Odp::class);
    }
}