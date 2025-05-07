@extends('layout')

@section('content')

    <div class="p-6 space-y-4">
        @if (session('success'))
            <x-ui.alert type="success" :message="session('success')" />
        @elseif (session('error'))
            <x-ui.alert type="error" :message="session('error')" />
        @endif
        
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
                    {{-- <div>
                        <label class="block text-sm text-gray-600 mb-1">ID Barang Masuk</label>
                        <input type="text" id="id_barang" class="w-full border rounded-md px-3 py-2" />
                    </div> --}}
                    <div class="relative">
                        <label class="block text-sm text-gray-600 mb-1">Nama Barang</label>
                        <input type="text" id="nama_barang" name="nama_barang" class="w-full border rounded-md px-3 py-2"
                            placeholder="Search Barang..." autocomplete="off">
                        <div id="barang-suggestions"
                            class="absolute z-10 w-full bg-white border mt-1 rounded-md hidden max-h-60 overflow-auto">
                            <!-- Suggestions will appear here -->
                        </div>
                        <!-- Hidden input to store supplier ID -->
                        <input type="hidden" id="barang_id" name="barang_id" />
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
                            <input type="date" id="tanggal_masuk"
                                class="w-full border rounded-md px-3 py-2 bg-gray-100 cursor-not-allowed"
                                value="{{ now()->format('Y-m-d') }}" readonly />
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
                        <div class="relative">
                            <label class="block text-sm text-gray-600 mb-1">Nama Supplier</label>
                            <input type="text" id="nama_supplier" name="nama_supplier"
                                class="w-full border rounded-md px-3 py-2" placeholder="Search Supplier..."
                                autocomplete="off">
                            <div id="supplier-suggestions"
                                class="w-full border rounded-md px-3 py-2 absolute z-10 bg-white mt-1 hidden max-h-60 overflow-auto">
                                <!-- Suggestions will appear here -->
                            </div>
                            <!-- Hidden input to store supplier ID -->
                            <input type="hidden" id="supplier_id" name="supplier_id" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Upload Nota</label>
                            <div id="uploadArea"
                                class="border rounded-md h-40 flex items-center justify-center text-gray-400 bg-gray-50 cursor-pointer relative">
                                <span id="uploadText" class="text-sm text-center">[ Upload area / drag file here or click to
                                    select ]</span>
                                <input type="file" id="notaFile" name="nota"
                                    class="absolute inset-0 opacity-0 cursor-pointer" />
                            </div>
                            <p id="fileName" class="text-sm text-gray-600 mt-2"></p>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="px-6 pb-6 flex justify-end gap-4">
                    <button id="addRow"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Masukkan
                        Barang</button>
                    <button type="button"
                        class="px-4 py-2 border border-gray-400 text-gray-700 rounded-md hover:bg-gray-100 transition"
                        id="clearFields">Kosongkan Field</button>
                </div>
            </div>
        </div>

        <!-- Form action to store data -->
        <form action="{{ route('barang-masuk.store') }}" method="POST" enctype="multipart/form-data" id="barangMasukForm">
            @csrf
            <!-- Hidden fields to store row data -->
            <div id="hiddenRows"></div>

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
                                {{-- <th class="px-4 py-2 border-b">Supplier ID</th> --}}
                                <th class="px-4 py-2 border-b">Tanggal Kadaluarsa</th>
                                <th class="px-4 py-2 border-b">Detail</th>
                                <th class="px-4 py-2 border-b">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="barangTableBody">
                            <!-- Rows will be added here dynamically -->
                            <input type="hidden" id="supplier_id" name="supplier_id" />
                        </tbody>
                    </table>
                </div>

                <div class="px-6 pb-6 flex justify-end gap-4">
                    <button type="submit" id="submitData"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                        Konfirmasi Input
                    </button>
                </div>
            </div>
        </form>

        <script>
            // get search inputs - nama barang atau supplier 
            document.addEventListener('DOMContentLoaded', function() {
                setupSearchableInput({
                    inputId: 'nama_barang',
                    hiddenId: 'barang_id',
                    suggestionBoxId: 'barang-suggestions',
                    searchUrl: '/daftar-produk/search',
                    valueKeys: {
                        id: 'idBarang',
                        name: 'namaBarang'
                    }
                });

                setupSearchableInput({
                    inputId: 'nama_supplier',
                    hiddenId: 'supplier_id',
                    suggestionBoxId: 'supplier-suggestions',
                    searchUrl: '/suppliers/search',
                    valueKeys: {
                        id: 'idSupplier',
                        name: 'nama'
                    }
                });

                function setupSearchableInput({
                    inputId,
                    hiddenId,
                    suggestionBoxId,
                    searchUrl,
                    valueKeys
                }) {
                    const input = document.getElementById(inputId);
                    const hiddenInput = document.getElementById(hiddenId);
                    const suggestionBox = document.getElementById(suggestionBoxId);

                    input.addEventListener('input', function() {
                        const query = input.value.trim();
                        if (query.length >= 2) {
                            fetch(`${searchUrl}?q=${encodeURIComponent(query)}`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.length > 0) {
                                        suggestionBox.innerHTML = data.map(item => `
                                <div class="px-3 py-2 cursor-pointer hover:bg-gray-100"
                                     data-id="${item[valueKeys.id]}"
                                     data-name="${item[valueKeys.name]}">
                                     ${item[valueKeys.name]} (${item[valueKeys.id]})
                                </div>
                            `).join('');
                                        suggestionBox.classList.remove('hidden');
                                        addSuggestionClickListeners();
                                    } else {
                                        suggestionBox.classList.add('hidden');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error fetching suggestions:', error);
                                    suggestionBox.classList.add('hidden');
                                });
                        } else {
                            suggestionBox.classList.add('hidden');
                        }
                    });

                    function addSuggestionClickListeners() {
                        const items = suggestionBox.querySelectorAll('div');
                        items.forEach(item => {
                            item.addEventListener('click', function() {
                                input.value = item.getAttribute('data-name');
                                hiddenInput.value = item.getAttribute('data-id');
                                suggestionBox.classList.add('hidden');
                            });
                        });
                    }

                    // Close dropdown when clicking outside
                    document.addEventListener('click', function(e) {
                        if (!suggestionBox.contains(e.target) && e.target !== input) {
                            suggestionBox.classList.add('hidden');
                        }
                    });
                }
            });

            // Menambahkan baris baru ke dalam tabel
            document.getElementById('addRow').addEventListener('click', function() {
                var idBarang = document.getElementById('barang_id').value;
                var namaBarang = document.getElementById('nama_barang').value;
                var hargaSatuan = document.getElementById('harga_satuan').value;
                var satuan = document.getElementById('satuan').value;
                var kuantitasMasuk = document.getElementById('kuantitas_masuk').value;
                var tanggalMasuk = document.getElementById('tanggal_masuk').value;
                var idSupplier = document.getElementById('supplier_id').value;
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

                // Menambahkan input tersembunyi untuk setiap row ke dalam form
                var hiddenRows = document.getElementById('hiddenRows');
                var hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';

                hiddenInput.name = `barang_masuk[]`;
                hiddenInput.value = JSON.stringify({
                    barang_id: idBarang,
                    nama_barang: namaBarang,
                    harga_satuan: hargaSatuan,
                    satuan: satuan,
                    kuantitas_masuk: kuantitasMasuk,
                    tanggal_masuk: tanggalMasuk,
                    supplier_id: idSupplier,
                    tanggal_kadaluwarsa: tanggalKadaluwarsa
                });
                hiddenRows.appendChild(hiddenInput);

                // Clear form fields
                document.getElementById('barang_id').value = '';
                document.getElementById('nama_barang').value = '';
                document.getElementById('harga_satuan').value = '';
                document.getElementById('satuan').value = 'Pcs';
                document.getElementById('kuantitas_masuk').value = '';
                // document.getElementById('tanggal_masuk').value = '';
                document.getElementById('tanggal_kadaluwarsa').value = '';
            });

            // Kosongkan semua field
            document.getElementById('clearFields').addEventListener('click', function() {
                document.getElementById('barang_id').value = '';
                document.getElementById('nama_barang').value = '';
                document.getElementById('harga_satuan').value = '';
                document.getElementById('satuan').value = 'Pcs';
                document.getElementById('kuantitas_masuk').value = '';
                document.getElementById('tanggal_masuk').value = '';
                document.getElementById('tanggal_kadaluwarsa').value = '';
            });

            // Pastikan form bisa submit ke backend
            document.getElementById('submitData').addEventListener('click', function() {
                // Di sini bisa tambahkan validasi jika diperlukan sebelum submit
            });
        </script>

    </div>
@endsection
