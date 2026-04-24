@extends('layouts.app')

@section('title', 'Pembayaran Sukses')
@section('page_title', 'Pembayaran Berhasil')

@section('content')

<div class="bg-gray-800 shadow-xl rounded-xl p-8 max-w-3xl mx-auto text-gray-100">

    {{-- ================= ICON + HEADER ================= --}}
    <div class="text-center mb-6">
        <div class="mx-auto w-20 h-20 flex items-center justify-center rounded-full bg-green-500/20 mb-4">
            <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0" />
            </svg>
        </div>

        @php
            $isFine = $payment && ($payment->type === 'fine' || $payment->payment_type === 'fine');
            $isPaid = $payment && in_array($payment->status, ['paid','completed']);
            $amount = $payment->amount ?? $payment->amount_paid ?? 0;
        @endphp

        <h2 class="text-3xl font-bold text-green-400">
            Pembayaran Berhasil
        </h2>

        <p class="text-sm mt-1 {{ $isFine ? 'text-red-400' : 'text-blue-400' }}">
            {{ $isFine ? 'Pembayaran Denda' : 'Transaksi Penyewaan' }}
        </p>

        <p class="text-gray-400 text-sm mt-2">
            Transaksi telah diproses.
        </p>
    </div>

    {{-- ================= CARD ================= --}}
    <div class="bg-gray-700 rounded-lg p-6">

        <h3 class="text-lg font-semibold mb-4 text-gray-200">
            Detail Transaksi
        </h3>

        {{-- INFO UTAMA --}}
        <div class="space-y-2 text-sm">

            <div class="flex justify-between">
                <span class="text-gray-400">No Sewa</span>
                <span>#{{ $rental->id }}</span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-400">Pelanggan</span>
                <span>{{ $rental->customer->name }}</span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-400">Unit</span>
                <span>{{ $rental->unit->name }} ({{ $rental->unit->type }})</span>
            </div>

        </div>

        <hr class="my-4 border-gray-600">

        {{-- ================= RENTAL / DENDA ================= --}}
        @if($isFine)

            @php
                $lateDuration = '-';
                if ($rental->actual_return_time) {
                    $lateDuration = \Carbon\Carbon::parse($rental->return_date)
                        ->diffForHumans($rental->actual_return_time, true);
                }
            @endphp

            <div class="space-y-2 text-sm">

                <div class="flex justify-between">
                    <span class="text-gray-400">Status</span>
                    <span class="text-red-400 font-semibold">Terlambat</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-400">Keterlambatan</span>
                    <span>{{ $lateDuration }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-400">Total Denda</span>
                    <span class="text-red-400 font-bold text-lg">
                        Rp {{ number_format($amount, 0, ',', '.') }}
                    </span>
                </div>

            </div>

        @else

            <div class="space-y-2 text-sm">

                <div class="flex justify-between">
                    <span class="text-gray-400">Jumlah Unit</span>
                    <span>{{ $rental->quantity }} unit</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-400">Durasi</span>
                    <span>{{ $rental->duration_days }} hari</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-400">Tanggal Sewa</span>
                    <span>{{ \Carbon\Carbon::parse($rental->rental_date)->format('d M Y, H:i') }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-400">Tanggal Kembali</span>
                    <span>{{ \Carbon\Carbon::parse($rental->return_date)->format('d M Y, H:i') }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-400">Total Sewa</span>
                    <span class="text-blue-400 font-bold text-lg">
                        Rp {{ number_format($amount, 0, ',', '.') }}
                    </span>
                </div>

            </div>

        @endif

        <hr class="my-4 border-gray-600">

        {{-- ================= PAYMENT ================= --}}
        @if($payment)

        <div class="space-y-2 text-sm">

            <div class="flex justify-between">
                <span class="text-gray-400">Metode</span>
                <span>{{ ucfirst($payment->method ?? 'online') }}</span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-400">Channel</span>
                <span>{{ $payment->payment_channel ?? '-' }}</span>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-gray-400">Status</span>

                @if($isPaid)
                    <span class="bg-green-600 text-white px-3 py-1 rounded text-xs">
                        Lunas
                    </span>
                @else
                    <span class="bg-yellow-600 text-white px-3 py-1 rounded text-xs">
                        Pending
                    </span>
                @endif
            </div>

        </div>

        @endif

    </div>

    {{-- ================= BUTTON ================= --}}
    <div class="mt-6 flex justify-center gap-3">

        @if($isPaid)
        <button onclick="sendInvoiceEmail({{ $rental->id }})"
            class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-5 rounded">
            Kirim Invoice
        </button>
        @endif

        <a href="{{ route('petugas.rental.form') }}"
           class="bg-gray-600 hover:bg-gray-700 text-white py-2 px-5 rounded">
            Penyewaan Baru
        </a>

    </div>

</div>

@push('scripts')
<script>
function sendInvoiceEmail(rentalId) {
    fetch('/petugas/rental/send-invoice-email/' + rentalId, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        alert(data.success ? 'Email berhasil dikirim' : 'Error: ' + data.message);
    })
    .catch(() => alert('Terjadi error'));
}
</script>
@endpush

@endsection