@extends('layouts.app')

@section('title', 'Penyewaan - Pilih Unit')
@section('page_title', 'Pilih Unit PlayStation')

@section('content')

<div class="max-w-5xl mx-auto">

    <div class="bg-gray-800 shadow-xl rounded-xl p-8 text-gray-100">

        <h2 class="text-2xl font-bold mb-2 text-center">
            Langkah 2: Pilih Unit PlayStation
        </h2>

        <p class="mb-2 text-gray-400 text-center text-sm">
            Maksimal total 10 unit (semua PS) & 30 hari
        </p>

        <!-- 🔥 TOTAL UNIT -->
        <p class="mb-6 text-center text-sm">
            Total Dipilih:
            <span id="totalQty" class="font-bold text-blue-400">0</span> / 10 unit
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
                            <th class="px-4 py-3 text-center">Pilih</th>
                            <th class="px-4 py-3 text-center">Qty</th>
                            <th class="px-4 py-3 text-center">Durasi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($units as $unit)
                        <tr class="border-b border-gray-700 hover:bg-gray-700 transition">

                            <!-- UNIT -->
                            <td class="px-4 py-3">
                                <div class="font-semibold text-white">
                                    {{ $unit->name }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $unit->type }} • {{ $unit->condition }}
                                </div>
                            </td>

                            <!-- HARGA -->
                            <td class="px-4 py-3 text-green-400 font-semibold">
                                Rp {{ number_format($unit->price_per_day, 0, ',', '.') }}
                            </td>

                            <!-- STOK -->
                            <td class="px-4 py-3">
                                {{ $unit->stock_available }}
                            </td>

                            <!-- CHECKBOX -->
                            <td class="px-4 py-3 text-center">
                                <input type="checkbox"
                                    name="unit_ids[]"
                                    value="{{ $unit->id }}"
                                    class="unit-checkbox accent-blue-500"
                                    data-index="{{ $loop->index }}">
                            </td>

                            <!-- QTY -->
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
                                        data-max="{{ $unit->stock_available }}"
                                        data-index="{{ $loop->index }}">+</button>

                                </div>

                                <input type="hidden"
                                    name="quantities[{{ $unit->id }}]"
                                    value="1"
                                    class="qty-input">
                            </td>

                            <!-- DURASI -->
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
                                        data-index="{{ $loop->index }}">+</button>

                                </div>

                                <input type="hidden"
                                    name="durations[{{ $unit->id }}]"
                                    value="1"
                                    class="durasi-input">
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

// ================= INIT =================
const qtyInputs = document.querySelectorAll('.qty-input');
const durasiInputs = document.querySelectorAll('.durasi-input');

// disable semua dulu
qtyInputs.forEach(i => i.disabled = true);
durasiInputs.forEach(i => i.disabled = true);

// ================= TOTAL =================
function updateTotal() {
    let total = 0;

    qtyInputs.forEach(input => {
        if (!input.disabled) {
            total += parseInt(input.value) || 0;
        }
    });

    document.getElementById('totalQty').innerText = total;
    return total;
}

// ================= CHECKBOX =================
document.querySelectorAll('.unit-checkbox').forEach((cb, i) => {
    cb.addEventListener('change', () => {

        if (cb.checked) {
            qtyInputs[i].disabled = false;
            durasiInputs[i].disabled = false;
        } else {
            qtyInputs[i].disabled = true;
            durasiInputs[i].disabled = true;

            qtyInputs[i].value = 1;
            durasiInputs[i].value = 1;

            document.querySelectorAll('.qty-value')[i].innerText = 1;
            document.querySelectorAll('.durasi-value')[i].innerText = 1;
        }

        updateTotal();
    });
});

// ================= PLUS =================
document.querySelectorAll('.btn-plus').forEach(btn => {
    btn.addEventListener('click', () => {

        let i = btn.dataset.index;
        let type = btn.dataset.type;

        if (type === 'qty') {

            if (qtyInputs[i].disabled) return;

            let val = parseInt(qtyInputs[i].value);
            let max = parseInt(btn.dataset.max);
            let total = updateTotal();

            if (val < max && total < 10) {
                val++;
            }

            qtyInputs[i].value = val;
            document.querySelectorAll('.qty-value')[i].innerText = val;
        }

        if (type === 'durasi') {

            if (durasiInputs[i].disabled) return;

            let val = parseInt(durasiInputs[i].value);

            if (val < 30) val++;

            durasiInputs[i].value = val;
            document.querySelectorAll('.durasi-value')[i].innerText = val;
        }

        updateTotal();
    });
});

// ================= MINUS =================
document.querySelectorAll('.btn-minus').forEach(btn => {
    btn.addEventListener('click', () => {

        let i = btn.dataset.index;
        let type = btn.dataset.type;

        if (type === 'qty') {

            if (qtyInputs[i].disabled) return;

            let val = parseInt(qtyInputs[i].value);

            if (val > 1) val--;

            qtyInputs[i].value = val;
            document.querySelectorAll('.qty-value')[i].innerText = val;
        }

        if (type === 'durasi') {

            if (durasiInputs[i].disabled) return;

            let val = parseInt(durasiInputs[i].value);

            if (val > 1) val--;

            durasiInputs[i].value = val;
            document.querySelectorAll('.durasi-value')[i].innerText = val;
        }

        updateTotal();
    });
});

</script>

@endsection