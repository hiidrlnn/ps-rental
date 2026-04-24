@extends('layouts.app')

@section('title', 'Laporan Penyewaan')
@section('page_title', 'Laporan Penyewaan PlayStation')

@section('content')
    <div class="bg-gray-800 shadow-xl rounded-lg p-6 text-gray-100">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-100">Laporan Penyewaan</h2>

        {{-- Form Filter Tanggal --}}
        <form action="{{ route('owner.reports.index') }}" method="GET" class="mb-6 flex flex-wrap items-end space-y-4 md:space-y-0 md:space-x-4 bg-gray-700 p-4 rounded-md shadow-sm">
            <div class="w-full md:w-auto">
                <label for="start_date" class="block text-sm font-medium text-gray-300 mb-1">Dari Tanggal:</label>
                <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}"
                       class="mt-1 block w-full rounded-md border border-gray-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-800 text-gray-100">
            </div>
            <div class="w-full md:w-auto">
                <label for="end_date" class="block text-sm font-medium text-gray-300 mb-1">Sampai Tanggal:</label>
                <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}"
                       class="mt-1 block w-full rounded-md border border-gray-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-800 text-gray-100">
            </div>
            <div class="w-full md:w-auto flex space-x-2 mt-4 md:mt-0">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                    Filter
                </button>
                <a href="{{ route('owner.reports.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-200 bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                    Reset Filter
                </a>
            </div>
        </form>

        {{-- Tabel Laporan Penyewaan --}}
        <div class="overflow-x-auto">
            <table class="min-w-full bg-gray-700 border border-gray-600 rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-600">
                        <th class="py-3 px-4 border-b border-gray-600 text-left text-gray-200">No. Sewa</th>
                        <th class="py-3 px-4 border-b border-gray-600 text-left text-gray-200">Pelanggan</th>
                        <th class="py-3 px-4 border-b border-gray-600 text-left text-gray-200">Unit PS</th>
                        <th class="py-3 px-4 border-b border-gray-600 text-left text-gray-200">Qty</th>
                        <th class="py-3 px-4 border-b border-gray-600 text-left text-gray-200">Durasi</th>
                        <th class="py-3 px-4 border-b border-gray-600 text-left text-gray-200">Tgl Sewa</th>
                        <th class="py-3 px-4 border-b border-gray-600 text-left text-gray-200">Estimasi Kembali</th>
                        <th class="py-3 px-4 border-b border-gray-600 text-left text-gray-200">Tgl Kembali Aktual</th>
                        <th class="py-3 px-4 border-b border-gray-600 text-left text-gray-200">Subtotal</th>
                        <th class="py-3 px-4 border-b border-gray-600 text-left text-gray-200">Denda</th>
                        <th class="py-3 px-4 border-b border-gray-600 text-left text-gray-200">Total</th>
                        <th class="py-3 px-4 border-b border-gray-600 text-left text-gray-200">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr class="border-b border-gray-600 hover:bg-gray-600 transition duration-150">
                            <td class="py-2 px-4">{{ $report->id }}</td>
                            <td class="py-2 px-4 text-gray-200">{{ $report->customer->name }}</td>
                            <td class="py-2 px-4 text-gray-200">{{ $report->unit->name }} ({{ $report->unit->type }})</td>
                            <td class="py-2 px-4">{{ $report->quantity }}</td>
                            <td class="py-2 px-4">{{ $report->duration_days }} hari</td>
                            {{-- FORMAT TANGGAL SEWA DENGAN HARI DAN JAM --}}
                            <td class="py-2 px-4">{{ \Carbon\Carbon::parse($report->rental_date)->translatedFormat('l, d F Y, H:i') }}</td>
                            {{-- FORMAT ESTIMASI KEMBALI DENGAN HARI DAN JAM --}}
                            <td class="py-2 px-4">{{ \Carbon\Carbon::parse($report->return_date)->translatedFormat('l, d F Y, H:i') }}</td>
                            <td class="py-2 px-4">
                                @if($report->actual_return_time)
                                    {{-- FORMAT TANGGAL KEMBALI AKTUAL DENGAN HARI DAN JAM --}}
                                    {{ \Carbon\Carbon::parse($report->actual_return_time)->translatedFormat('l, d F Y, H:i') }}
                                @else
                                    <span class="text-gray-400">Belum Kembali</span>
                                @endif
                            </td>
                            <td class="py-2 px-4 text-green-400">Rp {{ number_format($report->subtotal_price, 0, ',', '.') }}</td>
                            <td class="py-2 px-4 text-red-400">Rp {{ number_format($report->fine_amount, 0, ',', '.') }}</td>
                            <td class="py-2 px-4 font-bold text-blue-400">Rp {{ number_format($report->total_price + $report->fine_amount, 0, ',', '.') }}</td>
                            <td class="py-2 px-4">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $report->status == 'rented' ? 'bg-blue-600 text-white' : '' }}
                                    {{ $report->status == 'returned' ? 'bg-green-600 text-white' : '' }}
                                    {{ $report->status == 'late' ? 'bg-red-600 text-white' : '' }}">
                                    {{ ucfirst($report->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="py-4 px-4 text-center text-gray-500">Tidak ada data laporan penyewaan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection