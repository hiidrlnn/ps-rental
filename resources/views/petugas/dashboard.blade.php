@extends('layouts.app')

@section('title', 'Dashboard Petugas')
@section('page_title', 'Selamat Datang di Rental PS!')

{{--
    @php
        use App\Models\Unit;
        $unitModel = new Unit(); // Ini untuk injeksi manual model Unit
        $units = $unitModel->all(); // Mengambil semua unit untuk ditampilkan
    @endphp
    Jika ada error "Class 'App\Models\Unit' not found", pastikan baris `use App\Models\Unit;` di atas ada.
    Atau jika masih masalah, bisa pakai cara ini juga (tapi pastikan App\Models\Unit; tidak di-use di sini):
    @inject('unitModel', 'App\Models\Unit')
    @php
        $units = $unitModel->all();
    @endphp
--}}

{{-- Menggunakan cara @inject lebih disarankan untuk mendapatkan instance model di Blade --}}
@inject('unitModel', 'App\Models\Unit')
@php
    $units = $unitModel->all();
@endphp


@section('content')
    {{-- Banner "Ayo Main PS!" --}}
    <div class="bg-gray-800 rounded-lg shadow-xl p-8 mb-6 relative overflow-hidden">
        {{-- Background image, pastikan ada di public/images/ps_controller_bg.jpg --}}
        <img src="{{ asset('images/logo-rentalps.png') }}" alt="PS Controller Background" class="absolute inset-0 w-full h-full object-cover opacity-20">
        <div class="relative z-10 text-center py-12">
            <h2 class="text-5xl font-extrabold text-white mb-4 tracking-wide">Ayo Main PS!</h2>
            <p class="text-lg text-gray-300">Pilih konsol favoritmu dan nikmati pengalaman bermain game terbaik.</p>
        </div>
    </div>

    <div class="bg-gray-800 rounded-lg shadow-xl p-6 mb-6">
        <h3 class="text-xl font-bold text-gray-100 mb-4">Unit PlayStation Tersedia</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($units as $unit)
            <div class="bg-gray-700 rounded-lg shadow-md overflow-hidden transform hover:scale-105 transition-transform duration-200 ease-in-out cursor-pointer">
                {{-- Jalur gambar sekarang langsung dari public/images/ --}}
                <img src="{{ asset($unit->image_url ?? 'images/ps_placeholder.png') }}" alt="{{ $unit->name }}" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h4 class="font-semibold text-lg text-white">{{ $unit->name }} {{ $unit->type }}</h4>
                    <p class="text-gray-400 text-sm mb-2">Kode: {{ $unit->code }} | Kondisi: {{ $unit->condition }}</p>
                    <p class="text-blue-400 font-bold">Mulai dari Rp {{ number_format($unit->price_per_day, 0, ',', '.') }}/Hari</p>
                    <p class="text-gray-300 text-sm">Stok: {{ $unit->stock_available }} unit</p>
                </div>
            </div>
            @empty
            <div class="col-span-4 text-center text-gray-400 py-8">Tidak ada unit PlayStation yang terdaftar saat ini.</div>
            @endforelse
        </div>
    </div>
@endsection