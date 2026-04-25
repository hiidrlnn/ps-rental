@extends('layouts.app')

@section('title', 'Penyewaan - Data Pelanggan')
@section('page_title', 'Data Pelanggan')

@section('content')

<div class="bg-gray-800 shadow-xl rounded-xl p-8 max-w-2xl mx-auto text-gray-100">

    <h2 class="text-2xl font-bold mb-6 text-center">
        Langkah 1: Data Pelanggan & Waktu Sewa
    </h2>

    {{-- 🔥 ERROR GLOBAL --}}
    @if ($errors->any())
        <div class="mb-6 bg-red-600 text-white p-4 rounded-lg">
            <div class="font-semibold mb-2">
                Oops! Ada beberapa masalah dengan input Anda:
            </div>
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('petugas.rental.store-customer') }}" method="POST">
        @csrf

        {{-- ================= NAMA ================= --}}
        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2">Nama Pelanggan</label>
            <input type="text" name="name"
                value="{{ old('name') }}"
                required
                placeholder="Contoh: Budi Santoso"
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded outline-none
                @error('name') border-red-500 @enderror">

            @error('name')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ================= ALAMAT ================= --}}
        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2">Alamat</label>
            <textarea name="address" rows="3"
                required
                placeholder="Masukkan alamat lengkap"
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded outline-none
                @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>

            @error('address')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ================= TELEPON ================= --}}
        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2">Nomor Telepon</label>
            <input type="tel"
                id="phone"
                name="phone"
                value="{{ old('phone') }}"
                inputmode="numeric"
                placeholder="Contoh: 081234567890"
                required
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded outline-none
                @error('phone') border-red-500 @enderror">

            @error('phone')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ================= EMAIL ================= --}}
        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2">Email</label>
            <input type="text" name="email"
                value="{{ old('email') }}"
                required
                placeholder="contoh@email.com"
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded outline-none
                @error('email') border-red-500 @enderror">

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
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded outline-none
                @error('rental_datetime') border-red-500 @enderror">

            @error('rental_datetime')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- ================= BUTTON ================= --}}
        <div class="flex justify-end">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 px-5 py-2 rounded font-semibold">
                Lanjut ke Pilih Unit
            </button>
        </div>

    </form>
</div>

<script>
const form = document.getElementById("customerForm");
const phoneInput = document.getElementById("phone");

// ================== FILTER ANGKA ==================
phoneInput.addEventListener('input', function () {
    this.value = this.value.replace(/[^0-9]/g, '');
});

// ================== AUTO SAVE ==================
form.addEventListener("input", () => {
    const data = {
        name: form.name.value,
        address: form.address.value,
        phone: form.phone.value,
        email: form.email.value,
        rental_datetime: form.rental_datetime.value,
    };

    sessionStorage.setItem("customer_form", JSON.stringify(data));
});

// ================== FIX BACK BUTTON ==================
window.addEventListener("pageshow", function () {

    const data = JSON.parse(sessionStorage.getItem("customer_form"));

    if (data) {
        form.name.value = data.name || "";
        form.address.value = data.address || "";
        form.phone.value = data.phone || "";
        form.email.value = data.email || "";
        form.rental_datetime.value = data.rental_datetime || "";
    }

});

// ================== CLEAR SAAT SUBMIT ==================
form.addEventListener("submit", () => {
    sessionStorage.removeItem("customer_form");
});
</script>

@endsection