@extends('layouts.app')

@section('title', 'Pembayaran Denda')
@section('page_title', 'Pembayaran Denda Keterlambatan')

@section('content')
<div class="bg-gray-800 shadow-xl rounded-lg p-6 max-w-2xl mx-auto text-gray-100">

    <h2 class="text-2xl font-bold mb-6 text-center">
        Pembayaran Denda Keterlambatan
    </h2>

    {{-- DETAIL --}}
    <div class="mb-6 border-b border-gray-700 pb-4">
        <p><strong>No Sewa:</strong> #{{ $rental->id }}</p>
        <p><strong>Pelanggan:</strong> {{ $rental->customer->name }}</p>
        <p><strong>Unit:</strong> {{ $rental->unit->name }}</p>

        <p><strong>Tgl Estimasi:</strong>
            {{ \Carbon\Carbon::parse($rental->return_date)->format('d M Y H:i') }}
        </p>

        <p><strong>Tgl Aktual:</strong>
            {{ $rental->actual_return_time 
                ? \Carbon\Carbon::parse($rental->actual_return_time)->format('d M Y H:i') 
                : '-' }}
        </p>

        <p class="text-lg font-bold mt-2">
            Denda:
            <span class="text-red-400">
                Rp {{ number_format($fine_amount,0,',','.') }}
            </span>
        </p>
    </div>

    {{-- FORM --}}
    <form action="{{ route('petugas.return.store-fine-payment', $rental->id) }}" method="POST">
        @csrf

        <input type="hidden" name="fine_amount" value="{{ $fine_amount }}">

        {{-- METODE --}}
        <div class="mb-4">
            <label class="block mb-2 font-bold">Metode Pembayaran:</label>

            <label class="block">
                <input type="radio" id="manual_payment" name="payment_method" value="manual" checked>
                Manual (Tunai)
            </label>

            <label class="block">
                <input type="radio" id="xendit_payment" name="payment_method" value="xendit">
                Xendit (Online)
            </label>
        </div>

        {{-- MANUAL --}}
        <div id="manual_section" class="mb-6">
            <label class="block mb-2 font-bold">Nominal Bayar</label>
            <input type="number" id="amount_paid" name="amount_paid"
                class="w-full px-3 py-2 rounded bg-gray-700 text-white"
                onkeyup="hitungKembalian()">

            <label class="block mt-3 mb-1">Kembalian</label>
            <input type="text" id="change_amount"
                class="w-full px-3 py-2 rounded bg-gray-900 text-white"
                readonly>
        </div>

        {{-- ERROR --}}
        @if ($errors->any())
            <div class="text-red-400 mb-3">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- BUTTON --}}
        <div class="flex justify-between">
            <a href="{{ route('petugas.return.list') }}"
               class="bg-gray-600 px-4 py-2 rounded">
                Batal
            </a>

            <button type="submit"
                class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded">
                Bayar Denda
            </button>
        </div>
    </form>
</div>

{{-- 🔥 SCRIPT --}}
<script>
const fine = {{ $fine_amount }};

function hitungKembalian() {
    let bayar = parseInt(document.getElementById('amount_paid').value) || 0;
    let kembali = bayar - fine;

    document.getElementById('change_amount').value =
        'Rp ' + kembali.toLocaleString('id-ID');
}

// toggle metode
const manual = document.getElementById('manual_payment');
const xendit = document.getElementById('xendit_payment');
const section = document.getElementById('manual_section');

function toggle() {
    if (manual.checked) {
        section.style.display = 'block';
        document.getElementById('amount_paid').setAttribute('required', true);
    } else {
        section.style.display = 'none';
        document.getElementById('amount_paid').removeAttribute('required');
    }
}

manual.addEventListener('change', toggle);
xendit.addEventListener('change', toggle);

toggle();
</script>

@endsection