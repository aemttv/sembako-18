@extends('layout')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@section('content')
    <!-- Main content -->
    {{-- <div class="flex-1 flex flex-col p-6 w-full">
        <!-- Page Header (optional) -->
        <div class="mb-4">
            <h1 class="text-2xl font-bold text-gray-800">Welcome to Dashboard</h1>
            <p class="text-sm text-gray-500">Halaman utama untuk melihat ringkasan data.</p>
        </div>

        <!-- Page Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Tambahkan card/konten di sini -->
            <div class="bg-white rounded-2xl shadow p-4">Card 1</div>
            <div class="bg-white rounded-2xl shadow p-4">Card 2</div>
            <div class="bg-white rounded-2xl shadow p-4">Card 3</div>
        </div>
    </div> --}}

    <!-- Top Row - 3 Cards -->
    <div class="flex flex-wrap justify-center gap-4 mb-6">
        <div class="w-full sm:w-1/2 lg:w-1/3 max-w-sm">
            <x-card title="Total Stok Barang" icon="📦">1,201</x-card>
        </div>
        <div class="w-full sm:w-1/2 lg:w-1/3 max-w-sm">
            <x-card title="Total Barang Keluar" icon="📤">459</x-card>
        </div>
        <div class="w-full sm:w-1/2 lg:w-1/3 max-w-sm">
            <x-card title="Total Barang Masuk" icon="📥">303</x-card>
        </div>
    </div>

    <!-- Bottom Row - 2 Cards Centered -->
    <div class="flex justify-center gap-4 mb-6">
        <div class="w-full sm:w-1/2 lg:w-1/3 max-w-sm">
            <x-card title="Mendekati Masa Simpan" icon="⏳" class="border-blue-500">2</x-card>
        </div>
        <div class="w-full sm:w-1/2 lg:w-1/3 max-w-sm">
            <x-card title="Stok Rendah" icon="⚠️">Beras Pinpin</x-card>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 shadow-md border-gray-500">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Jumlah Pengeluaran Barang</h2>
            {{-- <button class="text-gray-400 hover:text-gray-600">⋮</button> --}}
        </div>
    
        <!-- Set a fixed height on a wrapping div -->
        <div style="height: 350px;">
            <canvas id="monthlySalesChart"></canvas>
        </div>
    </div>
    
    
    <script>
        // Only initialize once, or destroy previous instance if exists
        let monthlySalesChart;
    
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('monthlySalesChart').getContext('2d');
    
            // Destroy old chart instance if exists
            if (monthlySalesChart) {
                monthlySalesChart.destroy();
            }
    
            monthlySalesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Kebutuhan Harian', 'Perawatan', 'Peralatan Sekolah', 'Aksesoris'],
                    datasets: [{
                        label: 'Sales',
                        data: [150, 370, 190, 290],
                        backgroundColor: '#3B82F6',
                        borderRadius: 5,
                        barThickness: 40,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#1F2937' // text-gray-500
                            },
                            grid: {
                                color: '#E5E7EB' // bg-gray-100
                            }
                        },
                        x: {
                            ticks: {
                                color: '#1F2937'
                            },
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
    
    
    
@endsection
