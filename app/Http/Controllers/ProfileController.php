<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan data user yang sedang login

class ProfileController extends Controller
{
    public function show()
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login'); // Atau ke halaman lain jika belum login
        }

        // Ambil data user yang sedang login
        $user = Auth::user();

        // Tampilkan view profile dengan data user
        return view('profile.show', compact('user'));
    }
}