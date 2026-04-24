@extends('layouts.app') {{-- <--- BARIS PERTAMA YANG PENTING --}}

@section('title', 'Dashboard Pemilik')
@section('page_title', 'Dashboard Statistik')

@section('content') {{-- <--- AWAL KONTEN YANG AKAN MASUK KE @yield('content') DI LAYOUT UTAMA --}}
    <div class="bg-gray-800 shadow-xl rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-100 mb-6">Ringkasan Statistik Rental PS</h2>

        {{-- Statistik Angka Ringkasan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gray-700 p-6 rounded-lg shadow-md text-center flex flex-col items-center justify-center transform hover:scale-105 transition-transform duration-200">
                <div class="text-blue-400 mb-3">
                    <svg class="h-10 w-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h.01M7 12h.01M7 15h.01M17 15h.01M17 12h.01M12 21v-5m0-5h.01"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-200 mb-2">Penyewaan Bulan Ini</h3>
                <p class="text-5xl font-extrabold text-blue-300">{{ $totalRentalsMonth }}</p>
                <p class="text-gray-400 mt-2">Transaksi</p>
            </div>
            <div class="bg-gray-700 p-6 rounded-lg shadow-md text-center flex flex-col items-center justify-center transform hover:scale-105 transition-transform duration-200">
                <div class="text-green-400 mb-3">
                    <svg class="h-10 w-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10m0 0h10m-10 0L9 14m4-4L9 7m4 4h-4m4 0l-4-4m4 4v4m4-4h4"></path></svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-200 mb-2">PS Tersedia</h3>
                <p class="text-5xl font-extrabold text-green-300">{{ $availableUnits }}</p>
                <p class="text-gray-400 mt-2">Unit</p>
            </div>
            <div class="bg-gray-700 p-6 rounded-lg shadow-md text-center flex flex-col items-center justify-center transform hover:scale-105 transition-transform duration-200">
                <div class="text-purple-400 mb-3">
                    <svg class="h-10 w-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 4v4m0 4v2m-6 0h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-200 mb-2">Pendapatan Bulan Ini</h3>
                <p class="text-4xl font-extrabold text-purple-300">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
                <p class="text-gray-400 mt-2">Estimasi</p>
            </div>
            <div class="bg-gray-700 p-6 rounded-lg shadow-md text-center flex flex-col items-center justify-center transform hover:scale-105 transition-transform duration-200">
                <div class="text-yellow-400 mb-3">
                    <svg class="h-10 w-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V6m0 4v4m0 4v2m-6 0h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-200 mb-2">Total Pendapatan</h3>
                <p class="text-4xl font-extrabold text-yellow-300">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                <p class="text-gray-400 mt-2">Seluruhnya</p>
            </div>
        </div>

        {{-- Diagram Statistik --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-gray-700 rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-100 mb-4">Pendapatan Harian Bulan Ini</h3>
                {{-- Kontainer dengan tinggi tetap agar chart tidak berantakan --}}
                <div class="relative h-96">
                    <canvas id="dailyRevenueChart"></canvas>
                </div>
            </div>
            <div class="bg-gray-700 rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-100 mb-4">Unit Terlaris (Berdasarkan Kuantitas Disewa)</h3>
                {{-- Kontainer dengan tinggi tetap agar chart tidak berantakan --}}
                <div class="relative h-96">
                    <canvas id="topSellingUnitsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection {{-- <--- AKHIR DARI KONTEN UTAMA --}}

@push('scripts') {{-- <--- SCRIPT DI SINI --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data untuk Pendapatan Harian
            const dailyRevenueLabels = @json($dailyRevenueLabels);
            const dailyRevenueValues = @json($dailyRevenueValues);

            const dailyRevenueCtx = document.getElementById('dailyRevenueChart').getContext('2d');
            new Chart(dailyRevenueCtx, {
                type: 'bar',
                data: {
                    labels: dailyRevenueLabels,
                    datasets: [{
                        label: 'Pendapatan Harian (Rp)',
                        data: dailyRevenueValues,
                        backgroundColor: 'rgba(59, 130, 246, 0.6)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1,
                        borderRadius: 5,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#E5E7EB',
                                font: {
                                    size: 14
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: '#D1D5DB'
                            },
                            grid: {
                                color: 'rgba(107, 114, 128, 0.2)'
                            }
                        },
                        y: {
                            ticks: {
                                color: '#D1D5DB',
                                callback: function(value, index, ticks) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            },
                            grid: {
                                color: 'rgba(107, 114, 128, 0.2)'
                            }
                        }
                    }
                }
            });

            // Data untuk Unit Terlaris
            const topSellingUnitsLabels = @json($topSellingUnitsLabels);
            const topSellingUnitsValues = @json($topSellingUnitsValues);

            const topSellingUnitsCtx = document.getElementById('topSellingUnitsChart').getContext('2d');
            new Chart(topSellingUnitsCtx, {
                type: 'pie',
                data: {
                    labels: topSellingUnitsLabels,
                    datasets: [{
                        label: 'Kuantitas Disewa',
                        data: topSellingUnitsValues,
                        backgroundColor: [
                            'rgba(167, 139, 250, 0.6)',
                            'rgba(52, 211, 153, 0.6)',
                            'rgba(251, 191, 36, 0.6)',
                            'rgba(239, 68, 68, 0.6)',
                            'rgba(96, 165, 250, 0.6)',
                            'rgba(244, 63, 94, 0.6)',
                            'rgba(6, 182, 212, 0.6)',
                        ],
                        borderColor: [
                            'rgba(167, 139, 250, 1)',
                            'rgba(52, 211, 153, 1)',
                            'rgba(251, 191, 36, 1)',
                            'rgba(239, 68, 68, 1)',
                            'rgba(96, 165, 250, 1)',
                            'rgba(244, 63, 94, 1)',
                            'rgba(6, 182, 212, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#E5E7EB',
                                font: {
                                    size: 14
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed !== null) {
                                        label += context.parsed + ' unit';
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush {{-- <--- AKHIR DARI SCRIPT --}}