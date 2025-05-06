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
                        <input type="text" id="id_barang" class="w-full border rounded-md px-3 py-2" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Nama Barang</label>
                        <input type="text" id="nama_barang" class="w-full border rounded-md px-3 py-2" />
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Harga Satuan</label>
                            <input type="text" id="harga_satuan" class="w-full border rounded-md px-3 py-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Satuan</label>
                            <select id="satuan" class="w-full border rounded-md px-3 py-2">
                                <option>Pcs</option>
                                <option>Kg</option>
                                <option>Lusin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Kuantitas Masuk</label>
                            <input type="number" id="kuantitas_masuk" class="w-full border rounded-md px-3 py-2" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Tanggal Masuk</label>
                            <input type="date" id="tanggal_masuk" class="w-full border rounded-md px-3 py-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Tanggal Kadaluwarsa</label>
                            <input type="date" id="tanggal_kadaluwarsa" class="w-full border rounded-md px-3 py-2" />
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
                            <input type="text" id="nama_supplier" class="w-full border rounded-md px-3 py-2" />
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
                    <button id="addRow" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Masukkan Barang</button>
                    <button class="px-4 py-2 border border-gray-400 text-gray-700 rounded-md hover:bg-gray-100 transition">Kosongkan Field</button>
                </div>
            </div>
        </div>
        
        <!-- Table for displaying Barang Masuk -->
        <div class="mt-6 border rounded-lg bg-white shadow-sm">
            <div class="border-b px-6 py-3 font-medium text-gray-700">Daftar Simulasi Barang Masuk</div>
            <div class="p-6">
                <table id="barangTable" class="min-w-full table-auto">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 border-b">ID Barang</th>
                            <th class="px-4 py-2 border-b">Nama Barang</th>
                            <th class="px-4 py-2 border-b">Harga Satuan</th>
                            <th class="px-4 py-2 border-b">Satuan</th>
                            <th class="px-4 py-2 border-b">Kuantitas</th>
                            <th class="px-4 py-2 border-b">Tanggal Masuk</th>
                            <th class="px-4 py-2 border-b">Tanggal Kadaluarsa</th>
                            <th class="px-4 py-2 border-b">Detail</th>
                            <th class="px-4 py-2 border-b">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="barangTableBody">
                        <!-- Rows will be added here dynamically -->
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            document.getElementById('tanggal_masuk').value = new Date().toISOString().split('T')[0];
            document.getElementById('addRow').addEventListener('click', function () {
                var idBarang = document.getElementById('id_barang').value;
                var namaBarang = document.getElementById('nama_barang').value;
                var hargaSatuan = document.getElementById('harga_satuan').value;
                var satuan = document.getElementById('satuan').value;
                var namaSupplier = document.getElementById('nama_supplier').value;
                var kuantitasMasuk = document.getElementById('kuantitas_masuk').value;
                var tanggalMasuk = document.getElementById('tanggal_masuk').value;
                var tanggalKadaluwarsa = document.getElementById('tanggal_kadaluwarsa').value;
        
                // Add new row to the table
                var tableBody = document.getElementById('barangTableBody');
                var newRow = tableBody.insertRow();
        
                newRow.innerHTML = `
                    <td class="px-4 py-2 border-b text-center">${idBarang}</td>
                    <td class="px-4 py-2 border-b text-center">${namaBarang}</td>
                    <td class="px-4 py-2 border-b text-center">${hargaSatuan}</td>
                    <td class="px-4 py-2 border-b text-center">${satuan}</td>
                    <td class="px-4 py-2 border-b text-center">${kuantitasMasuk}</td>
                    <td class="px-4 py-2 border-b text-center">${tanggalMasuk}</td>
                    <td class="px-4 py-2 border-b text-center">${tanggalKadaluwarsa}</td>
                    <td class="px-4 py-2 border-b text-center">
                        <button class="text-blue-500 hover:text-blue-700 hover:underline">Lihat</button>
                    </td>
                    <td class="px-4 py-2 border-b text-center">
                        <button class="text-red-500 hover:text-red-700">Hapus</button>
                    </td>
                `;
        
                // Clear the form fields
                document.getElementById('id_barang').value = '';
                document.getElementById('nama_barang').value = '';
                document.getElementById('harga_satuan').value = '';
                document.getElementById('satuan').value = 'Pcs';
                document.getElementById('kuantitas_masuk').value = '';
                document.getElementById('tanggal_masuk').value = '';
                document.getElementById('tanggal_kadaluwarsa').value = '';
            });
        
            // Optional: Remove row functionality
            document.getElementById('barangTableBody').addEventListener('click', function (e) {
                if (e.target && e.target.nodeName === 'BUTTON') {
                    e.target.closest('tr').remove();
                }
            });
        </script>
        
@endsection
