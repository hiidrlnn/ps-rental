<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input username dan password
        $credentials = $request->validate([
            'username' => 'required|string', // <--- UBAH INI KE USERNAME
            'password' => 'required',
        ]);

        // Coba untuk melakukan otentikasi menggunakan username
        // Kita perlu memberitahu Laravel bahwa 'username' adalah kolom yang akan digunakan untuk login
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) { // <--- UBAH INI
            $request->session()->regenerate();

            if (Auth::user()->role === 'petugas') {
                return redirect()->intended('/petugas/dashboard');
            } elseif (Auth::user()->role === 'owner') {
                return redirect()->intended('/owner/dashboard');
            }
        }

        // Jika login gagal, kembali ke halaman login dengan pesan error
        return back()->withErrors([
            'username' => 'Username atau password salah.', // <--- UBAH PESAN ERROR INI
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}