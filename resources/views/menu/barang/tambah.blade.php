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
                <h1 class="text-xl font-semibold text-center">Form Tambah Barang (Daftar Produk)</h1>
            </div>
        </div>

        <!-- Form Container -->
        <!-- Form action to store data -->

        <div class="max-w-2xl mx-auto">
            <!-- Informasi Barang -->
            <div class="border rounded-lg bg-white shadow-sm">
                <div class="border-b px-6 py-3 font-medium text-gray-700">Informasi Barang</div>
                <div class="p-6 space-y-4">
                    <div class="relative">
                        <label class="block text-sm text-gray-600 mb-1">Nama Barang</label>
                        <input type="text" id="nama_barang" name="nama_barang" class="w-full border rounded-md px-3 py-2"
                            placeholder="Nama Barang..." autocomplete="off">
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
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Kuantitas Masuk</label>
                            <input type="number" id="kuantitas_masuk"
                                class="w-full border rounded-md px-3 py-2 bg-gray-100 cursor-no-drop" value="0"
                                readonly />
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
        </div>

        <form action="{{ route('produk.submit') }}" method="POST" enctype="multipart/form-data" id="tambahBarangForm">
            @csrf
            <!-- Hidden fields to store row data -->
            <div id="hiddenRows"></div>

            <div class="mt-6 border rounded-lg bg-white shadow-sm">
                <div class="border-b px-6 py-3 font-medium text-gray-700">Daftar Simulasi Barang Masuk</div>
                <div class="p-6">
                    <table id="barangTable" class="min-w-full table-auto border-separate border-spacing-0">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 border-b">*</th>
                                <th class="px-4 py-2 border-b">Nama Barang</th>
                                <th class="px-4 py-2 border-b">Merek</th>
                                <th class="px-4 py-2 border-b">Kategori</th>
                                <th class="px-4 py-2 border-b">Harga Jual</th>
                                <th class="px-4 py-2 border-b">Kuantitas</th>
                                <th class="px-4 py-2 border-b">Gambar</th>
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

            <div id="imageModal"
                class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-50 p-4">
                <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] flex flex-col">
                    <div class="flex justify-between items-center border-b p-4">
                        <h3 class="text-lg font-medium">Image Preview</h3>
                        <button id="closeModal" class="text-gray-500 hover:text-gray-700" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex-1 overflow-auto p-4 flex items-center justify-center">
                        <img id="modalImage" src="" alt="Preview"
                            class="max-w-full max-h-[70vh] object-contain">
                    </div>
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

            // Menambahkan baris baru ke dalam tabel
            let rowIndex = 0;

            function updateNoDataRow() {
                const tableBody = document.getElementById('barangTableBody');
                const noDataRow = document.getElementById('noDataRow');
                const dataRows = Array.from(tableBody.children).filter(row => row.id !== 'noDataRow');

                if (dataRows.length === 0) {
                    if (!noDataRow) {
                        const tr = document.createElement('tr');
                        tr.id = 'noDataRow';
                        tr.innerHTML = `<td colspan="8" class="text-center text-gray-500 p-2">Tidak ada data</td>`;
                        tableBody.appendChild(tr);
                    }
                } else if (noDataRow) {
                    noDataRow.remove();
                }
            }

            // Add new row to the table
            document.getElementById('addRow').addEventListener('click', function() {
                const namaBarang = document.getElementById('nama_barang').value;
                const namaMerek = document.getElementById('nama_merek').value;
                const kategoriBarang = document.getElementById('kategori').value;
                const hargaSatuan = document.getElementById('harga_satuan').value;
                const kuantitasMasuk = document.getElementById('kuantitas_masuk').value;
                const merekId = document.getElementById('merek_id').value;

                if (!namaBarang || !namaMerek || !hargaSatuan) {
                    alert('Please fill in all required fields');
                    return;
                }

                const tableBody = document.getElementById('barangTableBody');
                const newRow = tableBody.insertRow();
                newRow.id = `row-${rowIndex}`;

                // Create file input for this row
                const fileInputId = `barang_image_${rowIndex}`;
                const fileInput = document.createElement('input');
                fileInput.type = 'file';
                fileInput.name = `barang_image[${rowIndex}]`;
                fileInput.id = fileInputId;
                fileInput.className = 'hidden';
                fileInput.accept = 'image/*';
                fileInput.multiple = true;

                // Create preview container for this row
                const previewId = `image-preview-${rowIndex}`;
                const previewContainer = document.createElement('div');
                previewContainer.id = previewId;
                previewContainer.className = 'flex flex-wrap gap-2 mb-2 items-center justify-center min-h-[60px]';

                newRow.innerHTML = `
                    <td class="px-4 py-2 border-b text-center">${rowIndex + 1}</td>
                    <td class="px-4 py-2 border-b text-center">${namaBarang}</td>
                    <td class="px-4 py-2 border-b text-center">${namaMerek}</td>
                    <td class="px-4 py-2 border-b text-center">${kategoriBarang}</td>
                    <td class="px-4 py-2 border-b text-center">${hargaSatuan}</td>
                    <td class="px-4 py-2 border-b text-center">${kuantitasMasuk}</td>
                    <td class="px-4 py-2 border-b">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <!-- Preview container with border placeholder -->
                            <div id="${previewId}" class="border-2 border-dashed border-gray-300 rounded-lg w-1/2 min-h-[80px] flex flex-wrap items-center justify-center gap-2">
                                <!-- Images will appear here -->
                                <span class="text-gray-400 text-sm text-center ${previewContainer.innerHTML ? 'hidden' : ''}">No images uploaded</span>
                            </div>
                            
                            <!-- Upload button -->
                            <button type="button" onclick="document.getElementById('${fileInputId}').click()" 
                                class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">
                                Upload Gambar
                            </button>
                        </div>
                    </td>
                    <td class="px-4 py-2 border-b text-center">
                        <button type="button" onclick="removeRow('row-${rowIndex}')" 
                            class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">
                            Hapus
                        </button>
                    </td>
                `;

                // Append file input to the row
                newRow.querySelector(`td:nth-child(7)`).appendChild(fileInput);

                // Handle file selection for this row
                fileInput.addEventListener('change', function(e) {
                    const files = e.target.files;
                    const previewContainer = document.getElementById(previewId);
                    const placeholderText = previewContainer.querySelector('span');

                    previewContainer.innerHTML = '';

                    if (files.length > 0) {
                        // Hide placeholder text if it exists
                        if (placeholderText) placeholderText.classList.add('hidden');

                        Array.from(files).forEach((file, i) => {
                            if (file.type.startsWith('image/')) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    const previewDiv = document.createElement('div');
                                    previewDiv.className = 'relative group';

                                    const img = document.createElement('img');
                                    img.src = e.target.result;
                                    img.className =
                                        'h-16 w-16 object-cover rounded cursor-pointer hover:opacity-80';
                                    img.onclick = function() {
                                        showImageModal(e.target.result);
                                    };

                                    const deleteBtn = document.createElement('button');
                                    deleteBtn.innerHTML = '&times;';
                                    deleteBtn.className =
                                        'absolute -top-2 -right-2 bg-red-500 text-white rounded-full h-5 w-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100';
                                    deleteBtn.onclick = function(ev) {
                                        ev.stopPropagation();
                                        removeImageFromInput(fileInput, i);
                                        previewDiv.remove();

                                        // Show placeholder if no images left
                                        if (previewContainer.children.length === 0 &&
                                            placeholderText) {
                                            placeholderText.classList.remove('hidden');
                                        }
                                    };

                                    previewDiv.appendChild(img);
                                    previewDiv.appendChild(deleteBtn);
                                    previewContainer.appendChild(previewDiv);
                                };
                                reader.readAsDataURL(file);
                            }
                        });
                    } else if (placeholderText) {
                        placeholderText.classList.remove('hidden');
                    }
                });

                // Add hidden inputs for form submission
                const hiddenInputs = document.createElement('div');
                hiddenInputs.innerHTML = `
                    <input type="hidden" name="items[${rowIndex}][nama_barang]" value="${namaBarang}">
                    <input type="hidden" name="items[${rowIndex}][merek_id]" value="${merekId}">
                    <input type="hidden" name="items[${rowIndex}][kategori]" value="${kategoriBarang}">
                    <input type="hidden" name="items[${rowIndex}][harga_satuan]" value="${hargaSatuan}">
                    <input type="hidden" name="items[${rowIndex}][kuantitas_masuk]" value="${kuantitasMasuk}">
                `;
                document.getElementById('hiddenRows').appendChild(hiddenInputs);

                // Clear form fields
                document.getElementById('nama_barang').value = '';
                document.getElementById('nama_merek').value = '';
                document.getElementById('merek_id').value = '';
                document.getElementById('harga_satuan').value = '';

                rowIndex++;
                updateNoDataRow();
            });

            // Remove row function
            window.removeRow = function(rowId) {
                const row = document.getElementById(rowId);
                if (row) {
                    row.remove();
                    updateNoDataRow();
                }
            };

            // Show image modal
            function showImageModal(imageSrc) {
                const modal = document.getElementById('imageModal');
                const modalImage = document.getElementById('modalImage');

                modalImage.src = imageSrc;
                modal.classList.remove('hidden');

                // Close modal when clicking outside image
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                    }
                });
            }

            // Clear fields
            document.getElementById('clearFields').addEventListener('click', function() {
                document.getElementById('nama_barang').value = '';
                document.getElementById('nama_merek').value = '';
                document.getElementById('merek_id').value = '';
                document.getElementById('harga_satuan').value = '';
            });

            document.getElementById('closeModal').addEventListener('click', function() {
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
                const form = document.getElementById('tambahBarangForm');
                const formData = new FormData(form);

                // Get the nota file
                const barangFiles = barangFileInput.files;
                if (barangFiles.length > 0) {
                    for (let i = 0; i < barangFiles.length; i++) {
                        formData.append('barang_image[]', barangFiles[i]);
                    }
                }

            });
        </script>

    </div>
@endsection
