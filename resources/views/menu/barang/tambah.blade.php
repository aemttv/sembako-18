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
                <h1 class="text-xl font-semibold">Form Tambah Barang (Daftar Produk)</h1>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Home > Tambah Barang</p>
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
                            placeholder="Nama Barang..." autocomplete="off">
                        {{-- <div id="barang-suggestions"
                            class="absolute z-10 w-full bg-white border mt-1 rounded-md hidden max-h-60 overflow-auto">
                            <!-- Suggestions will appear here -->
                        </div>
                        <!-- Hidden input to store supplier ID -->
                        <input type="hidden" id="barang_id" name="barang_id" /> --}}
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative w-full">
    <label class="block text-sm text-gray-600 mb-1">Merek Barang</label>
    <input type="text" id="nama_merek" name="namaMerek"
        class="w-full border rounded-md px-3 py-2"
        placeholder="Search Merek Barang..." autocomplete="off">

    <!-- Suggestions -->
    <div id="merek-suggestions"
        class="absolute left-0 right-0 z-10 bg-white border mt-1 rounded-md hidden max-h-60 overflow-auto">
        <!-- Suggestions will appear here -->
    </div>

    <!-- Hidden input to store ID -->
    <input type="hidden" id="merek_id" name="idMerek" />
</div>

                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Kategori Barang</label>
                            <select id="kategori" name="kategoriBarang" class="w-full border rounded-md px-3 py-2">
                                @foreach ($kategori as $item)
                                    <option value="{{$item->value}}">
                                        {{$item->namaKategori()}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Harga Jual</label>
                            <input type="text" id="harga_satuan" class="w-full border rounded-md px-3 py-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Satuan</label>
                            <input type="text" id="satuan"
                                class="w-full border rounded-md px-3 py-2 bg-gray-100 cursor-no-drop" value="Pcs"
                                readonly />
                            {{-- <select id="satuan" class="w-full border rounded-md px-3 py-2">
                                <option>Pcs</option>
                                <option>Kg</option>
                            </select> --}}
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Kuantitas Masuk</label>
                            <input type="number" id="kuantitas_masuk" class="w-full border rounded-md px-3 py-2 bg-gray-100 cursor-no-drop" value="0" readonly/>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Supplier -->
            <div class="border rounded-lg bg-white shadow-sm flex flex-col justify-between">
                <div>
                    <div class="border-b px-6 py-3 font-medium text-gray-700">Gambar Produk</div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Upload Gambar</label>
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
        <form action="{{ route('produk.submit') }}" method="POST" enctype="multipart/form-data" id="tambahBarangForm">
            @csrf
            <!-- Hidden fields to store row data -->
            <div id="hiddenRows"></div>

            <div class="mt-6 border rounded-lg bg-white shadow-sm">
                <div class="border-b px-6 py-3 font-medium text-gray-700">Daftar Simulasi Barang Masuk</div>
                <div class="p-6">
                    <table id="barangTable" class="min-w-full table-auto">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 border-b">*</th>
                                <th class="px-4 py-2 border-b">Nama Barang</th>
                                <th class="px-4 py-2 border-b">Merek</th>
                                <th class="px-4 py-2 border-b">Kategori</th>
                                <th class="px-4 py-2 border-b">Harga Jual</th>
                                <th class="px-4 py-2 border-b">Kuantitas</th>
                                <th class="px-4 py-2 border-b">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="barangTableBody">
                            <!-- Rows will be added here dynamically -->
                            {{-- <input type="hidden" id="supplier_id" name="supplier_id" /> --}}
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
                    inputId: 'nama_merek',
                    hiddenId: 'merek_id',
                    suggestionBoxId: 'merek-suggestions',
                    searchUrl: '/merek/search',
                    valueKeys: {
                        id: 'idMerek',
                        name: 'namaMerek'
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
                var namaBarang = document.getElementById('nama_barang').value;
                var namaMerek = document.getElementById('nama_merek').value;
                var kategoriBarang = document.getElementById('kategori').value;
                var hargaSatuan = document.getElementById('harga_satuan').value;
                var kuantitasMasuk = document.getElementById('kuantitas_masuk').value;

                // Add new row to the table
                var tableBody = document.getElementById('barangTableBody');
                var newRow = tableBody.insertRow();

                newRow.innerHTML = `
                    <td class="px-4 py-2 border-b text-center">*</td>
                    <td class="px-4 py-2 border-b text-center">${namaBarang}</td>
                    <td class="px-4 py-2 border-b text-center">${namaMerek}</td>
                    <td class="px-4 py-2 border-b text-center">${kategoriBarang}</td>
                    <td class="px-4 py-2 border-b text-center">${hargaSatuan}</td>
                    <td class="px-4 py-2 border-b text-center">${kuantitasMasuk}</td>
                    <td class="px-4 py-2 border-b text-center">
                        <button class="bg-blue-500 text-white rounded-md px-4 py-2 hover:bg-blue-600">Lihat</button>
                        <button class="bg-orange-500 text-white rounded-md px-4 py-2 hover:bg-orange-600">Edit</button>
                        <button class="bg-red-500 text-white rounded-md px-4 py-2 hover:bg-red-600">Hapus</button>
                    </td>
                `;

                // Menambahkan input tersembunyi untuk setiap row ke dalam form
                var hiddenRows = document.getElementById('hiddenRows');
                var hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';

                hiddenInput.name = `barang_input[]`;
                hiddenInput.value = JSON.stringify({
                    nama_barang: namaBarang,
                    nama_merek: namaMerek,
                    kategori: kategoriBarang,
                    harga_satuan: hargaJual,
                    kuantitas_masuk: kuantitasMasuk,
                });
                hiddenRows.appendChild(hiddenInput);

                // Clear form fields
                document.getElementById('nama_barang').value = '';
                document.getElementById('nama_merek').value = '';
                document.getElementById('harga_satuan').value = '';
                document.getElementById('kuantitas_masuk').value = '';
                document.getElementById('tanggal_kadaluwarsa').value = '';
            });

            // Kosongkan semua field
            document.getElementById('clearFields').addEventListener('click', function() {
                document.getElementById('nama_barang').value = '';
                document.getElementById('nama_merek').value = '';
                document.getElementById('harga_satuan').value = '';
                document.getElementById('kuantitas_masuk').value = '';
                document.getElementById('tanggal_kadaluwarsa').value = '';
            });

            // Pastikan form bisa submit ke backend
            document.getElementById('submitData').addEventListener('click', function() {
                // Di sini bisa tambahkan validasi jika diperlukan sebelum submit
            });
        </script>

    </div>
@endsection
