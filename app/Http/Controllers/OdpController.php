<?php

namespace App\Http\Controllers;

use App\Models\Odp;
use App\Models\Site;
use Illuminate\Http\Request;

class OdpController extends Controller
{
    /**
     * Tampilkan data ODP.
     */
    public function index()
    {
        $sites = Site::all();
        $odps = Odp::with(['site', 'parent'])->orderBy('name', 'asc')->get();
        return view('odps.index', compact('odps', 'sites'));
    }

    /**
     * Simpan ODP Baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'site_id'       => 'nullable|exists:sites,id',
            'parent_odp_id' => 'nullable|exists:odps,id',
            'capacity'      => 'required|integer|min:1',
            'splitter_type' => 'nullable|string',
            'latitude'      => 'nullable|numeric',
            'longitude'     => 'nullable|numeric',
        ]);

        Odp::create($request->all());

        return back()->with('success', "ODP '{$request->name}' berhasil ditambahkan!");
    }

    /**
     * Update data ODP.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'site_id'       => 'nullable|exists:sites,id',
            'parent_odp_id' => 'nullable|exists:odps,id',
            'capacity'      => 'required|integer|min:1',
            'splitter_type' => 'nullable|string',
            'latitude'      => 'nullable|numeric',
            'longitude'     => 'nullable|numeric',
        ]);

        $odp = Odp::findOrFail($id);
        $odp->update($request->all());

        return back()->with('success', "Data ODP '{$odp->name}' diperbarui!");
    }

    /**
     * Hapus ODP.
     */
    public function destroy($id)
    {
        $odp = Odp::findOrFail($id);
        $odp->delete();

        return back()->with('success', "ODP '{$odp->name}' telah dihapus!");
    }
}
