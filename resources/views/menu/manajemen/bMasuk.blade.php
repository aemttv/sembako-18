@extends('layout')

@section('content')

    <div class="p-6 space-y-4">
        <!-- Header -->
    <div class="flex justify-between items-center">
        <div class="flex-1">
            <h1 class="text-xl font-semibold">Form Pemasukan Barang</h1>
        </div>
        <div class="text-right">
            <p class="text-sm text-gray-500">Home > Barang Masuk</p>
        </div>
    </div>
        <!-- Form Container -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informasi Barang -->
            <div class="border rounded-lg bg-white shadow-sm">
                <div class="border-b px-6 py-3 font-medium text-gray-700">Informasi Barang</div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">ID Barang Masuk</label>
                        <input type="text"
                            class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Nama Barang</label>
                        <input type="text" class="w-full border rounded-md px-3 py-2" />
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Harga Satuan</label>
                            <input type="text" class="w-full border rounded-md px-3 py-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Satuan</label>
                            <select class="w-full border rounded-md px-3 py-2">
                                <option>Pcs</option>
                                <option>Kg</option>
                                <option>Lusin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Kuantitas Masuk</label>
                            <input type="number" class="w-full border rounded-md px-3 py-2" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Tanggal Masuk</label>
                            <input type="date" class="w-full border rounded-md px-3 py-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Tanggal Kadaluwarsa</label>
                            <input type="date" class="w-full border rounded-md px-3 py-2" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Supplier -->
            <div class="border rounded-lg bg-white shadow-sm flex flex-col justify-between">
                <div>
                    <div class="border-b px-6 py-3 font-medium text-gray-700">Informasi Supplier</div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Nama Supplier</label>
                            <input type="text" class="w-full border rounded-md px-3 py-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Upload Nota</label>
                            <div class="border rounded-md h-40 flex items-center justify-center text-gray-400 bg-gray-50">
                                <span class="text-sm">[ Upload area / drag file here ]</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="px-6 pb-6 flex justify-end gap-4">
                    <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Masukkan
                        Barang</button>
                    <button
                        class="px-4 py-2 border border-gray-400 text-gray-700 rounded-md hover:bg-gray-100 transition">Kosongkan
                        Field</button>
                </div>
            </div>
        </div>
    </div>
@endsection
