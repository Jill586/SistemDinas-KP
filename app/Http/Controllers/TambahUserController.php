<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TambahUserController extends Controller
{
    /**
     * Tampilkan daftar user
     */
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.tambah-user', compact('users'));
    }

    /**
     * Tambah user baru
     */
    public function store(Request $request)
    {

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:3',
            'role'     => 'required',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password, // auto hash
            'role'     => $request->role,
            'email_verified_at' => now(),
        ]);

        return back()->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Update user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);


        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => "required|email|unique:users,email,$id",
            'role'  => 'required',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role  = $request->role;
        $user->email_verified_at = now();

        if ($request->filled('password')) {
            $user->password = $request->password; // auto hash
        }

        $user->save();

        return back()->with('success', 'User berhasil diperbarui!');
    }

    /**
     * Hapus user
     */
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }
}
