@extends('layout')

@section('content')
    <div class="p-6 space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold">Barang <span class="text-gray-500">Keluar</span></h1>
                <p class="text-sm text-gray-600">Transaction > Sales</p>
            </div>

        </div>

        <!-- Form Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Date, Cashier, Customer -->
            <div class="space-y-2 bg-white rounded-md shadow p-4">
                <label class="block text-sm font-medium">Date</label>
                <input type="date" class="w-full border border-gray-300 rounded p-2" value="{{ now()->format('Y-m-d') }}">

                <label class="block text-sm font-medium">Kasir</label>
                <input type="text" value="Mohammad Nur Fawaiq" class="w-full border border-gray-300 rounded p-2"
                    readonly>

                <label class="block text-sm font-medium">Customer</label>
                <select class="w-full border border-gray-300 rounded p-2">
                    <option>Umum</option>
                    <option>Pribadi</option>
                </select>
            </div>

            <!-- Barcode & Qty Input -->
            <div class="bg-white rounded-md shadow p-4 flex flex-col justify-between h-full min-h-[220px]">
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Barcode</label>
                    <div class="flex gap-2">
                        <input type="text" class="w-full border border-gray-300 rounded p-2">
                        <button class="bg-blue-500 text-white px-3 py-2 rounded">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>

                    <label class="block text-sm font-medium">Qty</label>
                    <input type="number" class="w-full border border-gray-300 rounded p-2" value="1" min="1">
                </div>

                <div class="flex justify-end mt-4">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded">+ Add</button>
                </div>
            </div>



            <!-- Invoice Total -->
            <div class="bg-white rounded-md shadow p-4 flex flex-col justify-between">
                <div class="flex justify-end">
                    <span class="text-lg font-semibold">Invoice <span class="text-blue-600">MP1909250001</span></span>
                </div>
                <div class="flex justify-center items-center flex-1">
                    <span class="text-6xl font-bold text-gray-700">0</span>
                </div>
            </div>

        </div>


        <!-- Table Section -->
        <div class="overflow-x-auto bg-white rounded-md shadow p-4 my-4">
            <table class="min-w-full border border-gray-300 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 border">#</th>
                        <th class="p-2 border">Barcode</th>
                        <th class="p-2 border">Product Item</th>
                        <th class="p-2 border">Price</th>
                        <th class="p-2 border">Qty</th>
                        <th class="p-2 border">Discount Item</th>
                        <th class="p-2 border">Total</th>
                        <th class="p-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="p-2 border text-center" colspan="8">Tidak ada item</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Payment Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 ">
            <div class="space-y-2 bg-white rounded-md shadow p-4">
                <label class="block text-sm font-medium">Sub Total</label>
                <input type="text" class="w-full border border-gray-300 rounded p-2" readonly>

                <label class="block text-sm font-medium">Discount</label>
                <input type="text" value="0" class="w-full border border-gray-300 rounded p-2">

                <label class="block text-sm font-medium">Grand Total</label>
                <input type="text" class="w-full border border-gray-300 rounded p-2" readonly>
            </div>

            <div class="space-y-2 bg-white rounded-md shadow p-4">
                <label class="block text-sm font-medium">Cash</label>
                <input type="text" value="0" class="w-full border border-gray-300 rounded p-2">

                <label class="block text-sm font-medium">Change</label>
                <input type="text" class="w-full border border-gray-300 rounded p-2" readonly>
            </div>

            <div class="space-y-2 bg-white rounded-md shadow p-4">
                <label for="kategoriKet">Keterangan Pengeluaran</label>
                <select name="kategoriKet" id="kategoriKet" class="w-full border border-gray-300 rounded p-2">
                    <option value="1">Jual</option>
                    <option value="2">Pribadi</option>
                </select>
                <label class="block text-sm font-medium">Note</label>
                <textarea class="w-full border border-gray-300 rounded p-2"></textarea>

                <div class="flex gap-2 mt-2">
                    <button class="bg-yellow-500 text-white px-4 py-2 rounded">Cancel</button>
                    <button class="bg-green-600 text-white px-4 py-2 rounded">Process</button>
                </div>
            </div>
        </div>
    </div>
@endsection
