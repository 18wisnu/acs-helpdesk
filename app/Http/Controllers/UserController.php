<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Tampilkan daftar user (Admin & Staff saja)
     */
    public function index()
    {
        // Hanya tampilkan yang bukan client untuk manajemen admin/staff
        $users = User::where('role', '!=', 'client')->with('site')->get();
        $sites = Site::all();
        
        return view('users.index', compact('users', 'sites'));
    }

    /**
     * Simpan user baru (Admin/Staff)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'phone'    => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role'     => 'required|in:admin,staff',
            'site_id'  => 'nullable|exists:sites,id',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'site_id'  => $request->site_id,
        ]);

        return back()->with('success', "User '{$request->name}' sebagai {$request->role} berhasil didaftarkan!");
    }

    /**
     * Update data user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|string|email|max:255|unique:users,email,'.$id,
            'role'    => 'required|in:admin,staff',
            'site_id' => 'nullable|exists:sites,id',
        ]);

        $user->update([
            'name'    => $request->name,
            'email'   => $request->email,
            'role'    => $request->role,
            'site_id' => $request->site_id,
        ]);

        return back()->with('success', "Data user '{$user->name}' berhasil diperbarui!");
    }

    /**
     * Hapus user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Jangan biarkan hapus diri sendiri
        if (auth()->id() == $id) {
            return back()->with('error', "Anda tidak bisa menghapus akun Anda sendiri!");
        }

        $user->delete();
        return back()->with('success', "User '{$user->name}' telah dihapus.");
    }
}
