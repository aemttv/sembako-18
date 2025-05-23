@extends('layout')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@section('content')
    <div class="p-6 space-y-4">
        {{-- @foreach ($barang as $data) --}}
        <!-- Top Row - 3 Cards -->
        <div class="flex flex-wrap justify-center gap-4 mb-6">
            <div class="w-full sm:w-1/2 lg:w-1/3 max-w-sm">
                <x-card title="Total Stok Barang" icon="📦"
                    class="bg-pink-50 border border-pink-200">{{ $totalStok }}</x-card>
            </div>
            <div class="w-full sm:w-1/2 lg:w-1/3 max-w-sm">
                <x-card title="Total Barang Keluar" icon="📤"
                    class="bg-blue-50 border ">{{ $totalBarangKeluar }}</x-card>
            </div>
            <div class="w-full sm:w-1/2 lg:w-1/3 max-w-sm">
                <x-card title="Total Barang Masuk" icon="📥"
                    class="bg-green-50 border border-green-200">{{ $totalBarangMasuk }}</x-card>
            </div>
        </div>

        <!-- Bottom Row - 2 Cards Centered -->
        <div class="flex justify-center gap-4 mb-6">
            <div class="w-full sm:w-1/2 lg:w-1/3 max-w-sm">
                @if ($totalDekatKadaluarsa > 0)
                    <x-card title="Mendekati Masa Simpan" icon="⏳" class="bg-yellow-50 border border-yellow-200">
                        {{ $totalDekatKadaluarsa }} <span class="text-red-500 ml-0.5 top-0.5">⚠️</span>
                    </x-card>
                @else
                    <x-card title="Mendekati Masa Simpan" icon="⏳" class="bg-yellow-50 border border-yellow-200">
                        {{ $totalDekatKadaluarsa }}
                    </x-card>
                @endif
            </div>
            <div class="w-full sm:w-1/2 lg:w-1/3 max-w-sm">
                <x-card title="Stok Rendah" icon="⚠️" class="bg-rose-50 border border-rose-200">Beras Pinpin</x-card>
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
        {{-- @endforeach --}}

    </div>
    <script>
        let monthlySalesChart;

        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('monthlySalesChart').getContext('2d');

            // Destroy old chart instance if exists
            if (monthlySalesChart) {
                monthlySalesChart.destroy();
            }

            monthlySalesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Barang Keluar',
                        data: @json($chartData),
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
                                color: '#1F2937'
                            },
                            grid: {
                                color: '#E5E7EB'
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
