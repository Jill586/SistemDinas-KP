<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
public function index()
{
   return view('login.login');
}
public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

            switch (trim($user->role)) {
            case 'super_admin':
                return redirect()->route('dashboard');
            case 'admin_bidang':
                return redirect()->route('perjalanan-dinas.create');
            case 'verifikator1':
                return redirect()->route('perjalanan-dinas.index');
            case 'verifikator2':
                return redirect()->route('verifikasi-pengajuan.index');
            case 'verifikator3':
                return redirect()->route('persetujuan-atasan.index');
            default:
                Auth::logout();
                return redirect()->route('login')->with('error', 'Role tidak dikenal.');
        }
    }

    return back()->with('error', 'Email atau password salah');
}
}


