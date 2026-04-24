@extends('layouts.app')

@section('title', 'Penyewaan - Pilih Unit')
@section('page_title', 'Pilih Unit PlayStation')

@section('content')

<div class="max-w-5xl mx-auto">

    <div class="bg-gray-800 shadow-xl rounded-xl p-8 text-gray-100">

        <h2 class="text-2xl font-bold mb-2 text-center">
            Langkah 2: Pilih Unit PlayStation
        </h2>

        <p class="mb-6 text-gray-400 text-center text-sm">
            Maksimal 10 unit & 30 hari
        </p>

        <p class="mb-6 text-gray-300 text-sm">
            Pelanggan:
            <span class="font-semibold text-white">
                {{ $customer->name }}
            </span>
            ({{ $customer->email }})
        </p>

        <form action="{{ route('petugas.rental.confirm') }}" method="POST">
            @csrf

            <div class="overflow-x-auto rounded-lg border border-gray-700">

                <table class="min-w-full text-sm">

                    <thead class="bg-gray-700 text-gray-300 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-3 text-left">Unit</th>
                            <th class="px-4 py-3 text-left">Harga</th>
                            <th class="px-4 py-3 text-left">Stok</th>
                            <th class="px-4 py-3 text-left">Pilih</th>
                            <th class="px-4 py-3 text-center">Qty</th>
                            <th class="px-4 py-3 text-center">Durasi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($units as $unit)
                        <tr class="border-b border-gray-700 hover:bg-gray-700 transition">

                            {{-- UNIT --}}
                            <td class="px-4 py-3">
                                <div class="font-semibold text-white">
                                    {{ $unit->name }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $unit->type }} • {{ $unit->condition }}
                                </div>
                            </td>

                            {{-- HARGA --}}
                            <td class="px-4 py-3 text-green-400 font-semibold">
                                Rp {{ number_format($unit->price_per_day, 0, ',', '.') }}
                            </td>

                            {{-- STOK --}}
                            <td class="px-4 py-3">
                                {{ $unit->stock_available }}
                            </td>

                            {{-- RADIO --}}
                            <td class="px-4 py-3">
                                <input type="radio"
                                    name="unit_id"
                                    value="{{ $unit->id }}"
                                    class="unit-radio accent-blue-500"
                                    data-index="{{ $loop->index }}">
                            </td>

                            {{-- QTY --}}
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">

                                    <button type="button"
                                        class="btn-minus bg-gray-600 px-2 rounded"
                                        data-type="qty"
                                        data-index="{{ $loop->index }}">-</button>

                                    <span class="qty-value font-bold">1</span>

                                    <button type="button"
                                        class="btn-plus bg-gray-600 px-2 rounded"
                                        data-type="qty"
                                        data-max="{{ min($unit->stock_available, 10) }}"
                                        data-index="{{ $loop->index }}">+</button>

                                </div>

                                <input type="hidden" name="quantity" value="1" class="qty-input">
                            </td>

                            {{-- DURASI --}}
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">

                                    <button type="button"
                                        class="btn-minus bg-gray-600 px-2 rounded"
                                        data-type="durasi"
                                        data-index="{{ $loop->index }}">-</button>

                                    <span class="durasi-value font-bold">1</span>

                                    <button type="button"
                                        class="btn-plus bg-gray-600 px-2 rounded"
                                        data-type="durasi"
                                        data-max="30"
                                        data-index="{{ $loop->index }}">+</button>

                                </div>

                                <input type="hidden" name="duration_days" value="1" class="durasi-input">
                            </td>

                        </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

            <div class="flex justify-between mt-6">

                <a href="{{ route('petugas.rental.form') }}"
                    class="bg-gray-600 hover:bg-gray-700 px-5 py-2 rounded text-sm">
                    Kembali
                </a>

                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded font-semibold">
                    Lanjut ke Konfirmasi
                </button>

            </div>

        </form>

    </div>

</div>

<script>

// disable semua input dulu
function resetInputs() {
    document.querySelectorAll('.qty-input, .durasi-input').forEach(i => i.disabled = true);
}

resetInputs();

// aktifkan sesuai radio
document.querySelectorAll('.unit-radio').forEach((radio, i) => {
    radio.addEventListener('change', () => {
        resetInputs();

        document.querySelectorAll('.qty-input')[i].disabled = false;
        document.querySelectorAll('.durasi-input')[i].disabled = false;
    });
});

// ================= PLUS =================
document.querySelectorAll('.btn-plus').forEach(btn => {
    btn.addEventListener('click', () => {

        let index = btn.dataset.index;
        let type = btn.dataset.type;
        let max = parseInt(btn.dataset.max);

        if (type === 'qty') {
            let input = document.querySelectorAll('.qty-input')[index];
            let display = document.querySelectorAll('.qty-value')[index];

            let val = parseInt(input.value);
            if (val < max) val++;

            input.value = val;
            display.innerText = val;
        }

        if (type === 'durasi') {
            let input = document.querySelectorAll('.durasi-input')[index];
            let display = document.querySelectorAll('.durasi-value')[index];

            let val = parseInt(input.value);
            if (val < max) val++;

            input.value = val;
            display.innerText = val;
        }

    });
});

// ================= MINUS =================
document.querySelectorAll('.btn-minus').forEach(btn => {
    btn.addEventListener('click', () => {

        let index = btn.dataset.index;
        let type = btn.dataset.type;

        if (type === 'qty') {
            let input = document.querySelectorAll('.qty-input')[index];
            let display = document.querySelectorAll('.qty-value')[index];

            let val = parseInt(input.value);
            if (val > 1) val--;

            input.value = val;
            display.innerText = val;
        }

        if (type === 'durasi') {
            let input = document.querySelectorAll('.durasi-input')[index];
            let display = document.querySelectorAll('.durasi-value')[index];

            let val = parseInt(input.value);
            if (val > 1) val--;

            input.value = val;
            display.innerText = val;
        }

    });
});

</script>

@endsection