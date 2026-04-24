@extends('layouts.app')

@section('title', 'Penyewaan - Konfirmasi')
@section('page_title', 'Konfirmasi Detail Penyewaan')

@section('content')

<div class="max-w-4xl mx-auto">

    <div id="card" class="bg-gray-800 shadow-xl rounded-xl p-8 text-gray-100 opacity-0 translate-y-6 transition duration-700">

        {{-- TITLE --}}
        <h2 class="text-2xl font-bold text-center mb-8">
            Langkah 3: Konfirmasi Penyewaan
        </h2>

        {{-- GRID --}}
        <div class="grid md:grid-cols-2 gap-6">

            {{-- CUSTOMER --}}
            <div class="bg-gray-700 rounded-lg p-5">
                <h3 class="text-lg font-semibold mb-4 border-b border-gray-600 pb-2">
                    Data Pelanggan
                </h3>

                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-400">Nama</span><br>
                        <span class="font-semibold">{{ $customer->name }}</span>
                    </p>

                    <p><span class="text-gray-400">Alamat</span><br>
                        {{ $customer->address }}
                    </p>

                    <p><span class="text-gray-400">Telepon</span><br>
                        {{ $customer->phone }}
                    </p>

                    <p><span class="text-gray-400">Email</span><br>
                        {{ $customer->email }}
                    </p>
                </div>
            </div>

            {{-- UNIT --}}
            <div class="bg-gray-700 rounded-lg p-5">
                <h3 class="text-lg font-semibold mb-4 border-b border-gray-600 pb-2">
                    Detail Unit
                </h3>

                <div class="space-y-2 text-sm">

                    <p>
                        <span class="font-semibold text-white">
                            {{ $unit->name }}
                        </span>
                        <span class="text-gray-400">({{ $unit->type }})</span>
                    </p>

                    <p class="text-gray-400 text-xs">
                        Kode: {{ $unit->code }}
                    </p>

                    <p>
                        <span class="px-2 py-1 text-xs rounded bg-green-600/20 text-green-400">
                            {{ $unit->condition }}
                        </span>
                    </p>

                    <p>
                        Harga / Hari:<br>
                        <span class="text-green-400 font-semibold">
                            Rp {{ number_format($unit->price_per_day, 0, ',', '.') }}
                        </span>
                    </p>

                    {{-- DISPLAY ONLY --}}
                    <div class="grid grid-cols-2 gap-3 mt-4">

                        <div class="bg-gray-800 rounded p-4 text-center">
                            <div class="text-xs text-gray-400">Jumlah</div>
                            <div class="text-xl font-bold">
                                {{ $validated['quantity'] }}
                            </div>
                        </div>

                        <div class="bg-gray-800 rounded p-4 text-center">
                        <div class="text-xs text-gray-400">Durasi</div>
                        <div class="text-xl font-bold">
                            {{ $validated['duration_days'] }} hari
                        </div>
                    </div>

                    <div class="mt-4 text-sm">
                    <span class="text-gray-400">Tanggal Sewa:</span><br>
                    <span class="text-white font-semibold">
                        {{ $rentalDate->format('d F Y, H:i') }}
                    </span>
                </div>

                    </div>

                    {{-- ESTIMASI --}}
                    <div class="mt-2 text-sm">
                    <span class="text-gray-400">Estimasi Kembali:</span><br>
                    <span class="text-blue-400 font-semibold">
                        {{ $returnDate->format('d F Y, H:i') }}
                    </span>
                </div>

                </div>
            </div>

        </div>

        {{-- TOTAL --}}
        <div class="mt-8 bg-gray-700 rounded-lg p-5">

            <h3 class="text-lg font-semibold mb-4 border-b border-gray-600 pb-2">
                Ringkasan Pembayaran
            </h3>

            <div class="flex justify-between text-sm mb-2">
                <span class="text-gray-400">Subtotal</span>
                <span>
                    Rp {{ number_format($rentalDetails['subtotal_price'], 0, ',', '.') }}
                </span>
            </div>

            <div class="flex justify-between text-lg font-bold mt-3">
                <span>Total</span>
                <span class="text-green-400">
                    Rp {{ number_format($rentalDetails['total_price'], 0, ',', '.') }}
                </span>
            </div>

        </div>

        {{-- BUTTON --}}
        <div class="flex justify-between items-center mt-8">

            <a href="{{ route('petugas.rental.select-unit') }}"
                class="bg-gray-600 hover:bg-gray-700 px-5 py-2 rounded text-sm">
                Kembali
            </a>

            <a href="{{ route('petugas.rental.payment') }}"
                class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded font-semibold">
                Lanjut ke Pembayaran
            </a>

        </div>

    </div>

</div>

{{-- ANIMASI --}}
<script>
window.addEventListener('load', () => {
    const card = document.getElementById('card');
    card.classList.remove('opacity-0', 'translate-y-6');
});
</script>

@endsection