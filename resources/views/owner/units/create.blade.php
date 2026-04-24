@extends('layouts.app')

@section('title', 'Tambah Unit PS Baru')
@section('page_title', 'Tambah Unit PlayStation Baru')

@section('content')
    <div class="bg-gray-800 shadow-xl rounded-lg p-6 max-w-lg mx-auto text-gray-100">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-100">Form Tambah Unit PS</h2>
        <form action="{{ route('owner.units.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-gray-300 text-sm font-bold mb-2">Nama PS:</label>
                <input type="text" id="name" name="name"
                       class="shadow appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-100 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-700 placeholder-gray-400"
                       value="{{ old('name') }}" required>
                @error('name')<p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label for="type" class="block text-gray-300 text-sm font-bold mb-2">Jenis PS (Ex: Slim, Digital Edition):</label>
                <input type="text" id="type" name="type"
                       class="shadow appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-100 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-700 placeholder-gray-400"
                       value="{{ old('type') }}" required>
                @error('type')<p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label for="image_url" class="block text-gray-300 text-sm font-bold mb-2">URL Gambar (opsional):</label>
                <input type="text" id="image_url" name="image_url"
                       class="shadow appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-100 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-700 placeholder-gray-400"
                       value="{{ old('image_url') }}" placeholder="Ex: images/ps_units/ps5_standard.jpg">
                @error('image_url')<p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label for="code" class="block text-gray-300 text-sm font-bold mb-2">Kode Unit (Unik):</label>
                <input type="text" id="code" name="code"
                       class="shadow appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-100 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-700 placeholder-gray-400"
                       value="{{ old('code') }}" required>
                @error('code')<p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label for="condition" class="block text-gray-300 text-sm font-bold mb-2">Kondisi:</label>
                <input type="text" id="condition" name="condition"
                       class="shadow appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-100 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-700 placeholder-gray-400"
                       value="{{ old('condition') }}" required>
                @error('condition')<p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label for="price_per_day" class="block text-gray-300 text-sm font-bold mb-2">Harga Per Hari (Rp):</label>
                <input type="number" id="price_per_day" name="price_per_day"
                       class="shadow appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-100 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-700 placeholder-gray-400"
                       value="{{ old('price_per_day') }}" min="0" step="any" required>
                @error('price_per_day')<p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>@enderror
            </div>
            <div class="mb-6">
                <label for="stock_available" class="block text-gray-300 text-sm font-bold mb-2">Stok Tersedia:</label>
                <input type="number" id="stock_available" name="stock_available"
                       class="shadow appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-100 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-700 placeholder-gray-400"
                       value="{{ old('stock_available') }}" min="0" required>
                @error('stock_available')<p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>@enderror
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-200">
                    Simpan Unit
                </button>
            </div>
        </form>
    </div>
@endsection