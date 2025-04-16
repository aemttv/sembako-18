@extends('layout')

@section('content')
<div class="p-6 space-y-4">
    <!-- Header -->
<div class="flex justify-between items-center">
    <div class="flex-1">
        <h1 class="text-xl font-semibold">Laporan Stok</h1>
    </div>
    <div class="text-right">
        <p class="text-sm text-gray-500">Home > Laporan Stok</p>
    </div>
</div>


<!-- Tabs -->
<div class="flex justify-between items-center gap-4 border rounded-lg p-2 bg-white flex-wrap">
    <!-- Left Side Buttons -->
    <div class="flex items-center gap-2">
        <input type="date"
            class="border border-gray-300 rounded-md px-3 py-1.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300" 
            placeholder="Tanggal Awal" />
        
        <input type="date"
            class="border border-gray-300 rounded-md px-3 py-1.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300" 
            placeholder="Tanggal Akhir" />
        
        <button
            class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600">
            Tampilkan
        </button>
    </div>

    <!-- Right Side: Search + Tambah Produk -->
    <div class="flex items-center gap-3">
        <!-- Search Input -->
        <div class="flex items-center bg-gray-50 border border-gray-200 rounded-xl px-3 w-[280px] shadow-sm">
            <i class="fas fa-search text-gray-400 mr-2"></i>
            <input type="text" placeholder="Search or type command..."
                class="bg-transparent border-none focus:ring-0 focus:outline-none w-full text-sm text-gray-700 placeholder-gray-400" />
        </div>
    </div>
</div>



    <!-- Table -->
    <div class="border rounded-lg overflow-x-auto">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="px-4 py-2">Nama Barang</th>
                    <th class="px-4 py-2">ID Barang</th>
                    <th class="px-4 py-2">Jumlah Awal Stok</th>
                    <th class="px-4 py-2">Pemasukan</th>
                    <th class="px-4 py-2">Pengeluaran</th>
                    <th class="px-4 py-2">Jumlah Akhir Bulan</th>
                    <th class="px-4 py-2">Harga Satuan (Rp)</th>
                    <th class="px-4 py-2">Nilai Stok Akhir (Rp)</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y">
                {{-- @foreach ($products as $product) --}}
                    <tr>
                        <td class="px-4 py-2">Susu UHT</td>
                        <td class="px-4 py-2">B0001</td>
                        <td class="px-4 py-2">50</td>
                        <td class="px-4 py-2">20</td>
                        <td class="px-4 py-2">30</td>
                        <td class="px-4 py-2">40</td>
                        <td class="px-4 py-2">10000</td>
                        <td class="px-4 py-2">400000</td>
                    </tr>
                {{-- @endforeach --}}
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-between items-center text-sm text-gray-800">
        <p>Showing 1 to 10 of 59 entries</p>
        <div class="flex gap-1">
            <button class="px-3 py-1 border rounded">Previous</button>
            <button class="px-3 py-1 border rounded bg-blue-200">1</button>
            <button class="px-3 py-1 border rounded">2</button>
            <button class="px-3 py-1 border rounded">3</button>
            <button class="px-3 py-1 border rounded">4</button>
            <button class="px-3 py-1 border rounded">Next</button>
        </div>
    </div>
</div>

   
@endsection
