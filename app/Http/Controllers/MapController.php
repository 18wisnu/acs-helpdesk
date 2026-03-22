<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Device;
use Illuminate\Http\Request;

class MapController extends Controller
{
    /**
     * Tampilkan peta lokasi pelanggan (berdasarkan site dan ONT).
     */
    public function index()
    {
        // 1. Ambil data Site (Server/OLT)
        $sites = Site::where('latitude', '!=', null)
                    ->get();
                    
        // 2. Ambil data ODP (Splitter)
        $odps = \App\Models\Odp::with(['site', 'parent'])->get();

        // 3. Ambil data Device/Customer yang punya koordinat + relasi ke Site/ODP
        $devices = Device::with(['customer', 'site', 'odp'])
                    ->whereHas('customer', function($q) {
                        $q->where('latitude', '!=', null);
                    })
                    ->get();
                    
        return view('map.index', compact('sites', 'odps', 'devices'));
    }
}
