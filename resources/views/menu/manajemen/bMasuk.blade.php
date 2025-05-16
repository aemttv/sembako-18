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

        <!-- Form action to store data -->
        <form action="{{ route('barang-masuk.submit') }}" method="POST" enctype="multipart/form-data" id="barangMasukForm">
            @csrf
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
                            <input type="text" id="nama_barang" name="nama_barang"
                                class="w-full border rounded-md px-3 py-2" placeholder="Search Barang..."
                                autocomplete="off">
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
                                <input type="number" id="kuantitas_masuk" class="w-full border rounded-md px-3 py-2" />
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Tanggal Masuk</label>
                                <input type="date" id="tanggal_masuk" class="w-full border rounded-md px-3 py-2"
                                    value="{{ now()->format('Y-m-d') }}" />
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
                                <input type="hidden" id="supplier_id" name="supplier_id"
                                    value="{{ old('supplier_id') }}" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Upload Nota</label>
                                <div id="uploadArea"
                                    class="border rounded-md h-40 flex items-center justify-center text-gray-400 bg-gray-50 cursor-pointer relative">
                                    <span id="uploadText" class="text-sm text-center">[ Upload area / drag file here or
                                        click to
                                        select ]</span>
                                    <input type="file" id="notaFile" name="nota_file"
                                        class="absolute inset-0 opacity-0 cursor-pointer" />
                                </div>
                                <p id="fileName" class="text-sm text-gray-600 mt-2"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="px-6 pb-6 flex justify-end gap-4">
                        <button id="addRow" type="button"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Masukkan
                            Barang</button>
                        <button type="button"
                            class="px-4 py-2 border border-gray-400 text-gray-700 rounded-md hover:bg-gray-100 transition"
                            id="clearFields">Kosongkan Field</button>
                    </div>
                </div>
            </div>

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
                                <th class="px-4 py-2 border-b">Tanggal Kadaluarsa</th>
                                <th class="px-4 py-2 border-b">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="barangTableBody">
                            <!-- Rows will be added here dynamically -->

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

            // File upload handling
            const uploadArea = document.getElementById('uploadArea');
            const notaFileInput = document.getElementById('notaFile');
            const fileNameDisplay = document.getElementById('fileName');

            // Handle drag and drop
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                uploadArea.classList.add('border-blue-500', 'bg-blue-50');
            });

            uploadArea.addEventListener('dragleave', function() {
                uploadArea.classList.remove('border-blue-500', 'bg-blue-50');
            });

            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('border-blue-500', 'bg-blue-50');
                if (e.dataTransfer.files.length) {
                    notaFileInput.files = e.dataTransfer.files;
                    updateFileNameDisplay();
                }
            });

            // Handle file selection
            notaFileInput.addEventListener('change', updateFileNameDisplay);

            function updateFileNameDisplay() {
                if (notaFileInput.files.length > 0) {
                    fileNameDisplay.textContent = `Selected file: ${notaFileInput.files[0].name}`;
                } else {
                    fileNameDisplay.textContent = '';
                }
            }

            // Menambahkan baris baru ke dalam tabel
            document.addEventListener('DOMContentLoaded', function() {
                const addRowBtn = document.getElementById('addRow');
                const tableBody = document.getElementById('barangTableBody');
                const hiddenRows = document.getElementById('hiddenRows');

                let rowIndex = 0;

                addRowBtn.addEventListener('click', function() {
                    const barangId = document.getElementById('barang_id').value;
                    const namaBarang = document.getElementById('nama_barang').value;
                    const hargaSatuan = document.getElementById('harga_satuan').value;
                    const satuan = document.getElementById('satuan').value;
                    const kuantitas = document.getElementById('kuantitas_masuk').value;
                    const tanggalMasuk = document.getElementById('tanggal_masuk').value;
                    const tanggalKadaluwarsa = document.getElementById('tanggal_kadaluwarsa').value;

                    if (!barangId || !namaBarang || !hargaSatuan || !kuantitas || !tanggalMasuk) {
                        alert('Mohon lengkapi semua field penting.');
                        return;
                    }

                    // Tambahkan ke tabel tampilan
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                <td class="px-4 py-2 border-b">${barangId}</td>
                <td class="px-4 py-2 border-b">${namaBarang}</td>
                <td class="px-4 py-2 border-b">${hargaSatuan}</td>
                <td class="px-4 py-2 border-b">${satuan}</td>
                <td class="px-4 py-2 border-b">${kuantitas}</td>
                <td class="px-4 py-2 border-b">${tanggalMasuk}</td>
                <td class="px-4 py-2 border-b">${tanggalKadaluwarsa}</td>
                <td class="px-4 py-2 border-b">
                    <button type="button" class="text-red-500 hover:underline remove-row" data-index="${rowIndex}">Hapus</button>
                </td>
            `;
                    tableBody.appendChild(tr);

                    hiddenRows.insertAdjacentHTML('beforeend', `
                <input type="hidden" name="items[${rowIndex}][barang_id]" value="${barangId}">
                <input type="hidden" name="items[${rowIndex}][nama_barang]" value="${namaBarang}">
                <input type="hidden" name="items[${rowIndex}][harga_satuan]" value="${hargaSatuan}">
                <input type="hidden" name="items[${rowIndex}][satuan]" value="${satuan}">
                <input type="hidden" name="items[${rowIndex}][kuantitas_masuk]" value="${kuantitas}">
                <input type="hidden" name="items[${rowIndex}][tanggal_masuk]" value="${tanggalMasuk}">
                <input type="hidden" name="items[${rowIndex}][tanggal_kadaluwarsa]" value="${tanggalKadaluwarsa}">
            `);

                    rowIndex++;

                    // Kosongkan field setelah input
                    document.getElementById('nama_barang').value = '';
                    document.getElementById('barang_id').value = '';
                    document.getElementById('harga_satuan').value = '';
                    document.getElementById('kuantitas_masuk').value = '';
                    document.getElementById('tanggal_kadaluwarsa').value = '';
                });

                // Hapus baris
                tableBody.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-row')) {
                        const index = e.target.getAttribute('data-index');

                        // Hapus row dari tabel
                        e.target.closest('tr').remove();

                        // Hapus hidden input
                        const inputs = hiddenRows.querySelectorAll(`input[name^="items[${index}]"]`);
                        inputs.forEach(input => input.remove());
                    }
                });

                // Tombol kosongkan field
                document.getElementById('clearFields').addEventListener('click', function() {
                    document.getElementById('nama_barang').value = '';
                    document.getElementById('barang_id').value = '';
                    document.getElementById('harga_satuan').value = '';
                    document.getElementById('kuantitas_masuk').value = '';
                    document.getElementById('tanggal_kadaluwarsa').value = '';
                });
            });

            // Pastikan form bisa submit ke backend
            document.getElementById('submitData').addEventListener('click', function() {
                e.preventDefault();
                const supplierId = document.getElementById('supplier_id').value;
                if (!supplierId) {
                    alert('Please select a supplier first');
                    return;
                }

                // Validate at least one row exists
                const rowCount = document.getElementById('barangTableBody').rows.length;
                if (rowCount === 0) {
                    alert('Please add at least one item');
                    return;
                }

                // Create FormData object
                const formData = new FormData(this);

                // Get the nota file
                const notaFile = notaFileInput.files[0];
                if (notaFile) {
                    formData.append('nota_file', notaFile);
                }
            });
        </script>

    </div>
@endsection
