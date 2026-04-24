@extends('layouts.app')

@section('title', 'Profile Saya')
@section('page_title', 'Profile Saya')

@section('content')
    <div class="bg-gray-800 shadow-xl rounded-lg p-6 max-w-lg mx-auto text-gray-100">
        <h3 class="text-xl font-semibold mb-4 text-gray-200">Informasi Profile</h3>
        <div class="mb-4 border-b border-gray-700 pb-2">
            <label class="block text-gray-300 text-sm font-bold mb-1">Nama:</label>
            <p class="text-gray-100">{{ $user->name }}</p> {{-- UBAH DARI Auth::user()->name MENJADI $user->name --}}
        </div>
        <div class="mb-4 border-b border-gray-700 pb-2">
            <label class="block text-gray-300 text-sm font-bold mb-1">Username:</label>
            <p class="text-gray-100">{{ $user->username }}</p> {{-- UBAH DARI Auth::user()->username MENJADI $user->username --}}
        </div>
        <div class="mb-4 border-b border-gray-700 pb-2">
            <label class="block text-gray-300 text-sm font-bold mb-1">Peran:</label>
            <p class="text-gray-100">{{ ucfirst($user->role) }}</p> {{-- UBAH DARI Auth::user()->role MENJADI $user->role --}}
        </div>
        {{-- Jika ada nomor telepon atau tanggal bergabung di model User (jika kamu tambahkan migrasi dan seeder untuk ini) --}}
        <div class="mb-4 border-b border-gray-700 pb-2">
            <label class="block text-gray-300 text-sm font-bold mb-1">Email (opsional):</label>
            <p class="text-gray-100">{{ $user->email ?? '-' }}</p>
        </div>
        <div class="mb-4 border-b border-gray-700 pb-2">
            <label class="block text-gray-300 text-sm font-bold mb-1">Tanggal Bergabung:</label>
            <p class="text-gray-100">{{ $user->created_at->format('d F Y') }}</p>
        </div>
    </div>
@endsection