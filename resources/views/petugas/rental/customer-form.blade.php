@extends('layouts.app')

@section('title', 'Penyewaan - Data Pelanggan')
@section('page_title', 'Data Pelanggan')

@section('content')

<div class="bg-gray-800 shadow-xl rounded-xl p-8 max-w-2xl mx-auto text-gray-100">

    <h2 class="text-2xl font-bold mb-6 text-center">
        Langkah 1: Data Pelanggan & Waktu Sewa
    </h2>

    <form action="{{ route('petugas.rental.store-customer') }}" method="POST" id="customerForm">
        @csrf

        {{-- ================= NAMA ================= --}}
        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2">Nama Pelanggan</label>
            <input type="text" name="name" id="name"
                value="{{ old('name') }}"
                required
                minlength="3"
                pattern="[A-Za-z\s]+"
                placeholder="Contoh: Budi Santoso"
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded focus:ring-2 focus:ring-blue-500 outline-none">

            @error('name')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ================= ALAMAT ================= --}}
        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2">Alamat</label>
            <textarea name="address" rows="3"
                required
                minlength="5"
                placeholder="Masukkan alamat lengkap"
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded focus:ring-2 focus:ring-blue-500 outline-none">{{ old('address') }}</textarea>

            @error('address')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ================= TELEPON ================= --}}
        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2">Nomor Telepon</label>
            <input type="text" name="phone" id="phone"
                value="{{ old('phone') }}"
                required
                pattern="08[0-9]{8,11}"
                placeholder="08xxxxxxxxxx"
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded focus:ring-2 focus:ring-blue-500 outline-none">

            @error('phone')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ================= EMAIL ================= --}}
        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2">Email</label>
            <input type="email" name="email"
                value="{{ old('email') }}"
                required
                placeholder="contoh@email.com"
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded focus:ring-2 focus:ring-blue-500 outline-none">

            @error('email')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ================= DATETIME ================= --}}
        <div class="mb-6">
            <label class="block text-sm font-semibold mb-2">Tanggal & Waktu Sewa</label>
            <input type="datetime-local" name="rental_datetime"
                value="{{ old('rental_datetime', now()->format('Y-m-d\TH:i')) }}"
                required
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded focus:ring-2 focus:ring-blue-500 outline-none">

            @error('rental_datetime')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ================= BUTTON ================= --}}
        <div class="flex justify-end">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 px-5 py-2 rounded font-semibold transition">
                Lanjut ke Pilih Unit
            </button>
        </div>

    </form>
</div>

{{-- ================= JS VALIDASI TAMBAHAN ================= --}}
<script>
document.getElementById("customerForm").addEventListener("submit", function(e){

    let name = document.getElementById("name").value.trim();
    let phone = document.getElementById("phone").value.trim();

    // 🔥 Nama minimal 2 kata
    if (name.split(" ").length < 1) {
        alert("Nama harus minimal 1 kata");
        e.preventDefault();
        return;
    }
    // Alamat Valid
    if (str_word_count($validated['address']) < 3) {
    return back()->withErrors([
        'address' => 'Alamat terlalu singkat, isi lebih lengkap'
    ])->withInput();
}

    // 🔥 Bersihin nomor (hapus spasi / simbol)
    phone = phone.replace(/\D/g, '');

    if (!phone.startsWith("08")) {
        alert("Nomor harus diawali 08");
        e.preventDefault();
        return;
    }

});
</script>

@endsection