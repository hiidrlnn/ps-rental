@extends('layouts.app')

@section('title', 'Penyewaan - Pembayaran')
@section('page_title', 'Form Pembayaran')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="bg-gray-800 shadow-xl rounded-xl p-8 text-gray-100">

        <h2 class="text-2xl font-bold mb-6 text-center">
            Langkah 4: Pembayaran
        </h2>

        {{-- DETAIL --}}
        <div class="bg-gray-700 rounded-lg p-5 mb-6">

            <h3 class="text-lg font-semibold mb-4 border-b border-gray-600 pb-2">
                Detail Pembayaran
            </h3>

            <div class="space-y-2 text-sm">
                <p><span class="text-gray-400">Pelanggan</span><br>{{ $customer->name }}</p>
                <p><span class="text-gray-400">Unit</span><br>{{ $unit->name }} ({{ $unit->type }}) - {{ $rentalDetails['quantity'] }} unit</p>
                <p><span class="text-gray-400">Durasi</span><br>{{ $rentalDetails['duration_days'] }} hari</p>
            </div>

            <div class="mt-4 text-right">
                <span class="text-gray-400 text-sm">Total</span><br>
                <span class="text-2xl font-bold text-green-400">
                    Rp {{ number_format($rentalDetails['total_price'], 0, ',', '.') }}
                </span>
            </div>

        </div>

        {{-- FORM --}}
        <form id="paymentForm" action="{{ route('petugas.rental.process-payment') }}" method="POST">
            @csrf

            {{-- METODE --}}
            <div class="mb-5">
                <label class="block text-sm font-semibold mb-2">Metode Pembayaran</label>

                <div class="space-y-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" id="manual_payment" name="payment_method" value="manual" checked>
                        <span>Manual (Tunai)</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" id="xendit_payment" name="payment_method" value="xendit">
                        <span>Online (Xendit)</span>
                    </label>
                </div>
            </div>

            {{-- MANUAL --}}
            <div id="manual_input" class="mb-6 p-5 bg-gray-700 rounded-lg">

                <label class="block text-sm mb-2">Nominal Bayar</label>

                <input type="text" id="amount_paid"
                    class="w-full p-3 bg-gray-900 border border-gray-600 rounded focus:ring-2 focus:ring-green-500 outline-none"
                    placeholder="Contoh: 100000">

                <input type="hidden" name="amount_paid" id="amount_paid_raw">

                <label class="block text-sm mt-4">Kembalian</label>

                <input type="text" id="change_amount"
                    class="w-full p-3 bg-gray-800 border border-gray-600 rounded"
                    readonly value="Rp 0">

            </div>

            {{-- XENDIT --}}
            <div id="xendit_info" class="hidden mb-6 p-4 border border-blue-600 rounded bg-gray-700">
                Pembayaran akan dialihkan ke halaman Xendit.
            </div>

            {{-- BUTTON --}}
            <div class="flex justify-between items-center">

                <a href="{{ route('petugas.rental.confirm') }}"
                    class="bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded text-sm">
                    Kembali
                </a>

                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 px-5 py-2 rounded font-semibold">
                    Lanjutkan Pembayaran
                </button>

            </div>

        </form>

    </div>

</div>

<script>
const manual = document.getElementById('manual_payment');
const xendit = document.getElementById('xendit_payment');

const manualInput = document.getElementById('manual_input');
const xenditInfo = document.getElementById('xendit_info');

const input = document.getElementById('amount_paid');
const rawInput = document.getElementById('amount_paid_raw');
const change = document.getElementById('change_amount');

const total = {{ $rentalDetails['total_price'] }};

// ================= TOGGLE =================
function toggle() {
    if (manual.checked) {
        manualInput.style.display = 'block';
        xenditInfo.style.display = 'none';
    } else {
        manualInput.style.display = 'none';
        xenditInfo.style.display = 'block';
    }
}

// ================= FORMAT RUPIAH =================
function formatRupiah(angka) {
    return 'Rp ' + angka.toLocaleString('id-ID');
}

// ================= INPUT ONLY NUMBER =================
input.addEventListener('input', function () {

    // hapus semua selain angka
    let value = this.value.replace(/[^0-9]/g, '');

    let number = parseInt(value || 0);

    // simpan raw
    rawInput.value = number;

    // tampilkan format
    this.value = number ? number.toLocaleString('id-ID') : '';

    // hitung kembalian
    let kembali = number - total;
    if (kembali < 0) kembali = 0;

    change.value = formatRupiah(kembali);
});

// ================= VALIDASI =================
document.getElementById('paymentForm').addEventListener('submit', function (e) {

    if (manual.checked) {
        let bayar = parseInt(rawInput.value || 0);

        if (bayar < total) {
            e.preventDefault();
            alert('Uang tidak cukup');
        }

        if (bayar <= 0) {
            e.preventDefault();
            alert('Masukkan nominal bayar yang valid');
        }
    }
});

manual.addEventListener('change', toggle);
xendit.addEventListener('change', toggle);

toggle();
</script>

@endsection