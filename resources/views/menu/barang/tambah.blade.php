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
        <!-- Form action to store data -->
        <form action="{{ route('produk.submit') }}" method="POST" enctype="multipart/form-data" id="tambahBarangForm">
            @csrf
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
                            <input type="text" id="nama_merek" name="nama_merek"
                                class="w-full border rounded-md px-3 py-2" placeholder="Search Merek Barang..."
                                autocomplete="off">

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
                            <select id="kategori" name="kategori" class="w-full border rounded-md px-3 py-2">
                                @foreach ($kategori as $item)
                                    <option value="{{ $item->value }}">
                                        {{ $item->namaKategori() }}
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
                            <input type="number" id="kuantitas_masuk"
                                class="w-full border rounded-md px-3 py-2 bg-gray-100 cursor-no-drop" value="0"
                                readonly />
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
                                class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center relative cursor-pointer">
                                <input type="file" id="barangFile" name="barang_image[]" class="hidden" accept="image/*,.pdf,.doc,.docx" multiple />
                                <div id="previewContainer" class="flex flex-col items-center justify-center">
                                    <img id="imagePreview" src="" alt="Image Preview"
                                        class="max-h-40 mb-2 hidden" />
                                    <span id="fileName" class="text-sm text-gray-600"></span>
                                    <span id="uploadPrompt" class="text-gray-400">Drag & drop or click to upload a
                                        file</span>
                                </div>
                            </div>
                            <p id="fileName" class="text-sm text-gray-600 mt-2"></p>
                        </div>
                        <!-- Image Modal -->
                        <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
                            <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-4 relative">
                                <button type="button" id="closeModal" class="absolute top-2 right-2 text-gray-600 hover:text-red-500 text-xl">&times;</button>
                                <img id="modalImage" src="" alt="Uploaded Preview" class="w-full h-auto rounded">
                            </div>
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
                            <tr id="noDataRow">
                                <td colspan="8" class="text-center text-gray-500 py-2">Tidak ada data</td>
                            </tr>
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
            const uploadedImages = []; // To store uploaded image DataURLs
            document.getElementById('barangFile').addEventListener('change', function (event) {
                const file = event.target.files[0];
                const reader = new FileReader();

                reader.onload = function (e) {
                    const dataURL = e.target.result;
                    document.getElementById('imagePreview').src = dataURL;
                    document.getElementById('imagePreview').classList.remove('hidden');
                    document.getElementById('fileName').textContent = file.name;

                    uploadedImages.push(dataURL); // Store it for "Lihat" later
                };

                if (file) {
                    reader.readAsDataURL(file);
                }
            });

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

                    if (!hiddenInput.value) {
                        input.value = '';
                    }
                }
            });
                }
            });

            function updateNoDataRow() {
                const tableBody = document.getElementById('barangTableBody');
                const noDataRow = document.getElementById('noDataRow');
                // Count rows that are NOT the placeholder
                const dataRows = Array.from(tableBody.children).filter(
                    row => row.id !== 'noDataRow'
                );
                if (dataRows.length === 0) {
                    // Show placeholder if not present
                    if (!noDataRow) {
                        const tr = document.createElement('tr');
                        tr.id = 'noDataRow';
                        tr.innerHTML = `<td colspan="7" class="text-center text-gray-500 p-2">Tidak ada data</td>`;
                        tableBody.appendChild(tr);
                    }
                } else {
                    // Remove placeholder if present
                    if (noDataRow) {
                        noDataRow.remove();
                    }
                }
            }

            // File upload handling
            const uploadArea = document.getElementById('uploadArea');
            const barangFileInput = document.getElementById('barangFile');
            const fileNameDisplay = document.getElementById('fileName');
            const imagePreview = document.getElementById('imagePreview');
            const uploadPrompt = document.getElementById('uploadPrompt');

            // Click to open file dialog
            uploadArea.addEventListener('click', () => {
                barangFileInput.click();
            });

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
                    barangFileInput.files = e.dataTransfer.files;
                    updateFileNameAndPreview();
                }
            });

            // Handle file selection
            barangFileInput.addEventListener('change', updateFileNameAndPreview);

            function updateFileNameAndPreview() {
                if (barangFileInput.files.length > 0) {
                    const file = barangFileInput.files[0];
                    fileNameDisplay.textContent = `Selected file: ${file.name}`;
                    // If image, show preview
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.src = e.target.result;
                            imagePreview.classList.remove('hidden');
                        };
                        reader.readAsDataURL(file);
                    } else {
                        imagePreview.src = '';
                        imagePreview.classList.add('hidden');
                    }
                    uploadPrompt.classList.add('hidden');
                } else {
                    fileNameDisplay.textContent = '';
                    imagePreview.src = '';
                    imagePreview.classList.add('hidden');
                    uploadPrompt.classList.remove('hidden');
                }
            }

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
                    <td class="px-4 py-2 border-b text-center">${tableBody.rows.length - 1}</td>
                    <td class="px-4 py-2 border-b text-center">${namaBarang}</td>
                    <td class="px-4 py-2 border-b text-center">${namaMerek}</td>
                    <td class="px-4 py-2 border-b text-center">${kategoriBarang}</td>
                    <td class="px-4 py-2 border-b text-center">${hargaSatuan}</td>
                    <td class="px-4 py-2 border-b text-center">${kuantitasMasuk}</td>
                    <td class="px-4 py-2 border-b text-center">
                        <button type="button" class="lihat-btn bg-blue-500 text-white rounded-md px-4 py-2 hover:bg-blue-600" data-index="${uploadedImages.length - 1}">Lihat</button>
                        <button class="bg-orange-500 text-white rounded-md px-4 py-2 hover:bg-orange-600">Edit</button>
                        <button class="bg-red-500 text-white rounded-md px-4 py-2 hover:bg-red-600">Hapus</button>
                    </td>
                `;

                tableBody.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('lihat-btn')) {
            const index = e.target.getAttribute('data-index');
            const imageURL = uploadedImages[index];

            if (imageURL) {
                document.getElementById('modalImage').src = imageURL;
                document.getElementById('imageModal').classList.remove('hidden');
            } else {
                alert("No image uploaded.");
            }
        }
    });



                // Menambahkan input tersembunyi untuk setiap row ke dalam form
                var hiddenRows = document.getElementById('hiddenRows');
                var hiddenInput = document.createElement('input');
                var merekId = document.getElementById('merek_id').value;
                hiddenInput.type = 'hidden';

                hiddenInput.name = `barang_input[]`;
                hiddenInput.value = JSON.stringify({
                    nama_barang: namaBarang,
                    merek_barang: merekId,
                    kategori: kategoriBarang,
                    harga_satuan: hargaSatuan,
                    kuantitas_masuk: kuantitasMasuk,
                });
                hiddenRows.appendChild(hiddenInput);
                updateNoDataRow();

                // Clear form fields
                document.getElementById('nama_barang').value = '';
                document.getElementById('nama_merek').value = '';
                document.getElementById('harga_satuan').value = '';
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
            document.getElementById('closeModal').addEventListener('click', function () {
                document.getElementById('imageModal').classList.add('hidden');
                document.getElementById('modalImage').src = '';
            });


            // Pastikan form bisa submit ke backend
            document.getElementById('submitData').addEventListener('click', function() {
                e.preventDefault();

                // Validate at least one row exists
                const rowCount = document.getElementById('barangTableBody').rows.length;
                if (rowCount === 0) {
                    alert('Please add at least one item');
                    return;
                }

                // Create FormData object
                const formData = new FormData(this);

                // Get the nota file
                const barangFile = barangFileInput.files[0];
                if (barangFile) {
                    formData.append('barang_image[]', barangFile);
                }
            });
        </script>

    </div>
@endsection
