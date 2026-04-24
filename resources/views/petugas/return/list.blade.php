@extends('layouts.app')

@section('title', 'Daftar Pengembalian')
@section('page_title', 'Unit Sedang Disewa & Pengembalian')

@php
    use Carbon\Carbon;
@endphp

@section('content')
    <div class="bg-gray-800 shadow-xl rounded-lg p-6 max-w-full mx-auto text-gray-100">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-100">Unit PlayStation yang Sedang Disewa</h2>

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
                        <th class="py-3 px-4 border-b border-gray-600 text-left text-gray-200">Tgl Kembali (Estimasi)</th>
                        <th class="py-3 px-4 border-b border-gray-600 text-left text-gray-200">Sisa Waktu</th>
                        <th class="py-3 px-4 border-b border-gray-600 text-left text-gray-200">Status</th>
                        <th class="py-3 px-4 border-b border-gray-600 text-left text-gray-200">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rentals as $rental)
                        @php
                            // Ubah ini: Hapus ->endOfDay() agar waktu presisi
                            $return_date_carbon = Carbon::parse($rental->return_date);
                            $now_carbon = Carbon::now();
                            $diff_for_humans = '';
                            $is_late = false;
                            $late_duration = ''; // Untuk menyimpan durasi keterlambatan

                            if ($rental->status === 'returned') {
                                $diff_for_humans = 'Sudah Kembali';
                            } elseif ($now_carbon->greaterThan($return_date_carbon)) {
                                $is_late = true;
                                // Hitung durasi keterlambatan
                                $diff = $now_carbon->diff($return_date_carbon);
                                $late_duration = '';
                                if ($diff->days > 0) {
                                    $late_duration .= $diff->days . ' hari ';
                                }
                                if ($diff->h > 0) {
                                    $late_duration .= $diff->h . ' jam ';
                                }
                                if ($diff->i > 0) {
                                    $late_duration .= $diff->i . ' menit';
                                }
                                $late_duration = trim($late_duration);
                                if (empty($late_duration)) { // Jika telatnya kurang dari 1 menit
                                    $late_duration = 'sebentar';
                                }
                                $diff_for_humans = 'Terlambat ' . $late_duration;

                            } else {
                                // Hitung mundur sisa waktu secara real-time di frontend jika perlu
                                // Untuk backend ini cukup menampilkan waktu relatif
                                $diff_for_humans = $now_carbon->diffForHumans($return_date_carbon, [
                                    'syntax' => Carbon::DIFF_RELATIVE_TO_NOW,
                                    'parts' => 3, // Tampilkan hingga 3 bagian (hari, jam, menit)
                                    'short' => false, // Tampilkan penuh (days, hours, minutes)
                                ]);
                            }
                        @endphp
                        <tr class="{{ $is_late ? 'bg-red-800' : 'bg-gray-700' }} border-b border-gray-600 hover:bg-gray-600 transition duration-150">
                            <td class="py-2 px-4">{{ $rental->id }}</td>
                            <td class="py-2 px-4 text-gray-200">{{ $rental->customer->name }}</td>
                            <td class="py-2 px-4 text-gray-200">{{ $rental->unit->name }} ({{ $rental->unit->type }})</td>
                            <td class="py-2 px-4">{{ $rental->quantity }}</td>
                            <td class="py-2 px-4">{{ $rental->duration_days }} hari</td>
                            {{-- UBAH FORMAT TANGGAL DAN WAKTU DI SINI --}}
                            <td class="py-2 px-4">{{ Carbon::parse($rental->rental_date)->format('d F Y, H:i') }}</td>
                            <td class="py-2 px-4">{{ Carbon::parse($rental->return_date)->format('d F Y, H:i') }}</td>
                            <td class="py-2 px-4 {{ $is_late ? 'text-red-400 font-bold' : 'text-green-400' }}">
                                {{ $diff_for_humans }}
                            </td>
                            <td class="py-2 px-4">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $rental->status == 'rented' ? 'bg-blue-600 text-white' : '' }}
                                    {{ $rental->status == 'returned' ? 'bg-green-600 text-white' : '' }}
                                    {{ $rental->status == 'late' ? 'bg-red-600 text-white' : '' }}">
                                    {{ ucfirst($rental->status) }}
                                </span>
                            </td>
                            <td class="py-2 px-4">
                            @if(in_array($rental->status, ['rented', 'late']))
                                <form action="{{ route('petugas.return.process', $rental->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit"
                                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-1 px-3 rounded transition duration-200">
                                        Proses Pengembalian
                                    </button>
                                </form>
                            @else
                                <span class="bg-green-600 text-white text-sm px-3 py-1 rounded">
                                    ✔ Selesai
                                </span>
                            @endif
                        </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-4 px-4 text-center text-gray-500">Tidak ada unit PlayStation yang sedang disewa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection