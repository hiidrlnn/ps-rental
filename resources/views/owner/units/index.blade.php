@extends('layouts.app')

@section('title', 'Kelola Stok Unit PS')
@section('page_title', 'Daftar Unit PlayStation')

@section('content')
<div class="bg-gray-800 shadow-xl rounded-lg p-6 text-gray-100">

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="bg-green-600 text-white px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Daftar Unit PlayStation</h2>

        <div class="flex gap-2">

            {{-- 🔄 SYNC STOK --}}
            <form action="{{ route('owner.units.sync-stock') }}" method="POST"
                onsubmit="return confirm('Yakin mau sync stok?')">
                @csrf
                <button type="submit"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                    🔄 Sync Stok
                </button>
            </form>

            {{-- TAMBAH --}}
            <a href="{{ route('owner.units.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Tambah Unit
            </a>

        </div>
    </div>

    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table class="min-w-full bg-gray-700 border border-gray-600 rounded-lg overflow-hidden">

            <thead>
                <tr class="bg-gray-600 text-gray-200">
                    <th class="py-3 px-4 text-left">Nama PS</th>
                    <th class="py-3 px-4 text-left">Jenis</th>
                    <th class="py-3 px-4 text-left">Kode</th>
                    <th class="py-3 px-4 text-left">Kondisi</th>
                    <th class="py-3 px-4 text-left">Harga/Hari</th>
                    <th class="py-3 px-4 text-left">Total</th>
                    <th class="py-3 px-4 text-left">Tersedia</th>
                    <th class="py-3 px-4 text-left">Status</th>
                    <th class="py-3 px-4 text-left">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($units as $unit)
                    @php
                        $isMismatch = $unit->stock_available > $unit->stock_total;
                    @endphp

                    <tr class="border-b border-gray-600 hover:bg-gray-600">

                        <td class="py-2 px-4">{{ $unit->name }}</td>
                        <td class="py-2 px-4">{{ $unit->type }}</td>
                        <td class="py-2 px-4">{{ $unit->code }}</td>
                        <td class="py-2 px-4">{{ $unit->condition }}</td>

                        <td class="py-2 px-4 text-green-400">
                            Rp {{ number_format($unit->price_per_day, 0, ',', '.') }}
                        </td>

                        {{-- TOTAL --}}
                        <td class="py-2 px-4">
                            {{ $unit->stock_total }}
                        </td>

                        {{-- AVAILABLE --}}
                        <td class="py-2 px-4">
                            {{ $unit->stock_available }}
                        </td>

                        {{-- STATUS --}}
                        <td class="py-2 px-4">
                            @if($isMismatch)
                                <span class="bg-red-600 text-white px-2 py-1 rounded text-xs">
                                    Tidak Sinkron
                                </span>
                            @else
                                <span class="bg-green-600 text-white px-2 py-1 rounded text-xs">
                                    Normal
                                </span>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td class="py-2 px-4 flex gap-2">

                            <a href="{{ route('owner.units.edit', $unit->id) }}"
                                class="bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-bold py-1 px-3 rounded">
                                Edit
                            </a>

                            <form action="{{ route('owner.units.destroy', $unit->id) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin hapus unit ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white text-sm font-bold py-1 px-3 rounded">
                                    Hapus
                                </button>
                            </form>

                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="9" class="py-4 text-center text-gray-400">
                            Belum ada unit PlayStation.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>
@endsection