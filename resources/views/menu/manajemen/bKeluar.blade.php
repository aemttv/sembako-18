@extends('layout')

@section('content')
    <div class="p-6 space-y-4">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div class="flex-1">
                <h1 class="text-xl font-semibold">Tabel Produk Jumlah Stok Tersedia</h1>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Home > Barang Retur</p>
            </div>
        </div>


        <!-- Tabs -->
        <div class="flex justify-between items-center gap-2 border rounded-lg p-2 bg-white">
            <!-- Search Input Group -->
            <div class="flex items-center bg-gray-50 border border-gray-200 rounded-xl px-3 w-[360px] shadow-sm mx-auto">
                <i class="fas fa-search text-gray-400 mr-2"></i>
                <input type="text" placeholder="Search or type command..."
                    class="bg-transparent border-none focus:ring-0 focus:outline-none w-full text-sm text-gray-700 placeholder-gray-400" />
            </div>
            {{-- <a href="/tambah-produk" class="px-4 py-2 text-sm font-medium text-white bg-green-500 rounded-md hover:bg-green-600">Tambah Produk</a> --}}
        </div>


        <!-- Table -->
        <div class="border rounded-lg overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2">ID Barang</th>
                        <th class="px-4 py-2">Nama Barang</th>
                        <th class="px-4 py-2">Merek</th>
                        <th class="px-4 py-2">Kategori</th>
                        <th class="px-4 py-2">Stok</th>
                        <th class="px-4 py-2">Kondisi</th>
                        <th class="px-4 py-2">Proses</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    {{-- @foreach ($products as $product) --}}
                    <tr>
                        <td class="px-4 py-2">B0001</td>
                        <td class="px-4 py-2">Susu UHT</td>
                        <td class="px-4 py-2">ULTRA MILK</td>
                        <td class="px-4 py-2">Kebutuhan Harian</td>
                        <td class="px-4 py-2">10</td>
                        <td class="px-4 py-2">Kadaluarsa</td>
                        <td class="px-4 py-2 flex gap-1">
                            <a href="#" onclick="openModal()"
                                class="px-2 py-1 bg-blue-500 text-white rounded text-xs">Proses</a>
                        </td>
                    </tr>
                    {{-- @endforeach --}}
                </tbody>
            </table>
        </div>



        <!-- Modal -->
        <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white w-[90%] max-w-3xl rounded-lg shadow-lg p-6 relative">

                <!-- Tombol close -->
                <button onclick="closeModal()"
                    class="absolute top-4 right-4 text-gray-500 hover:text-black text-xl">✕</button>

                <!-- Header -->
                <h2 class="text-lg font-semibold mb-4">Detail Pengeluaran Produk</h2>

                <!-- Divider -->
                <div class="border-b border-gray-300 my-3"></div>

                <!-- Isi Modal -->
                <div class="flex gap-4">

                    <!-- Gambar Produk -->
                    <div class="w-1/2 border rounded flex items-center justify-center aspect-square">
                        <img src="https://dummyimage.com/1920x1080/000/fff" alt="Foto Produk"
                            class="h-full max-w-full max-h-full object-cover rounded">
                    </div>

                    <!-- Form -->
                    <div class="w-1/2 flex flex-col gap-2">
                        <div class="flex gap-2">
                            <input type="text" placeholder="ID Produk" class="border px-2 py-1 w-1/2 rounded text-sm">
                            <input type="text" placeholder="INVOICE" class="border px-2 py-1 w-1/2 rounded text-sm">
                        </div>

                        <input type="text" placeholder="Nama Produk" class="border px-2 py-1 rounded text-sm">

                        <div class="flex gap-2">
                            <input type="number" placeholder="Jumlah" class="border px-2 py-1 w-1/2 rounded text-sm">
                            <input type="text" value="Kg" class="border px-2 py-1 w-1/2 rounded text-sm">
                        </div>

                        <select class="border px-2 py-1 rounded text-sm">
                            <option value="">Alasan Pengeluaran Produk</option>
                            <option value="keluar">Keluar/Terjual</option>
                        </select>

                        <label class="text-sm mt-2">Keterangan</label>
                        <textarea rows="4" class="border px-2 py-1 rounded text-sm resize-none"></textarea>

                        <div class="flex justify-end gap-2 mt-3">
                            <button class="bg-green-500 text-white px-4 py-1 rounded-md text-sm">Simpan</button>
                            <button onclick="closeModal()" class="bg-gray-300 px-4 py-1 rounded-md text-sm">Batal</button>
                        </div>
                    </div>
                </div>
            </div>
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

    <!-- JS untuk buka/tutup modal -->
    <script>
        function openModal() {
            document.getElementById('modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }
    </script>
@endsection
