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

        <div class="max-w-2xl mx-auto">
            <!-- Informasi Barang -->
            <div class="border rounded-lg bg-white shadow-sm">
                <div class="border-b px-6 py-3 font-medium text-gray-700">Informasi Barang</div>
                <div class="p-6 space-y-4">
                    <div class="relative w-full">
                        <label class="block text-sm text-gray-600 mb-1">Nama Barang</label>
                        <input type="text" id="nama_barang" name="nama_barang" class="w-full border rounded-md px-3 py-2"
                            placeholder="Nama Barang..." autocomplete="off" maxlength="100">
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
                            <label class="block text-sm text-gray-600 mb-1">Harga Jual</label>
                            <input type="text" id="harga_satuan" class="w-full border rounded-md px-3 py-2"
                                maxlength="16" />
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

                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Satuan Barang</label>
                            <select id="satuan_barang" name="satuan" class="w-full border rounded-md px-3 py-2">
                                @foreach ($satuan as $item)
                                    <option value="{{ $item->value }}">
                                        {{ $item->namaSatuan() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <input type="hidden" id="kuantitas_masuk" value="0" readonly />

                    <!-- Buttons -->
                    <div class="pt-4 flex justify-end gap-4">
                        <button id="addRow" type="button"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Masukkan
                            Barang</button>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('produk.submit') }}" method="POST" enctype="multipart/form-data" id="tambahBarangForm">
            @csrf
            <!-- Hidden fields to store row data -->
            <div id="hiddenRows"></div>

            <div class="mt-6 border rounded-lg bg-white shadow-sm">
                <div class="border-b px-6 py-3 font-medium text-gray-700">Daftar Simulasi Informasi Barang Baru</div>
                <div class="p-6">
                    <table id="barangTable" class="min-w-full border border-gray-300 text-sm">
                        <thead class="bg-gray-100 uppercase text-md">
                            <tr>
                                <th class="px-4 py-2 border-b">*</th>
                                <th class="px-4 py-2 border-b">Nama Barang</th>
                                <th class="px-4 py-2 border-b">Merek</th>
                                <th class="px-4 py-2 border-b">Kategori</th>
                                <th class="px-4 py-2 border-b">Harga Jual</th>
                                <th class="px-4 py-2 border-b">Satuan</th>
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

            // Helper to format number as Rupiah
            function formatRupiah(angka) {
                if (!angka) return '';
                let number_string = angka.replace(/[^,\d]/g, '').toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    rupiah += (sisa ? '.' : '') + ribuan.join('.');
                }
                rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
                return rupiah ? 'Rp. ' + rupiah : '';
            }

            // Helper to get only the numeric value
            function getNumericValue(str) {
                return str.replace(/[^0-9]/g, '');
            }

            document.addEventListener('DOMContentLoaded', function() {
                const hargaSatuan = document.getElementById('harga_satuan');

                // Create or get the error message element
                let hargaSatuanError = document.getElementById('hargaSatuanError');
                if (!hargaSatuanError && hargaSatuan) {
                    hargaSatuanError = document.createElement('div');
                    hargaSatuanError.id = 'hargaSatuanError';
                    hargaSatuanError.className = 'text-red-500 text-xs mt-1';
                    hargaSatuanError.style.display = 'none';
                    hargaSatuan.parentNode.appendChild(hargaSatuanError);
                }

                if (hargaSatuan) {
                    hargaSatuan.addEventListener('input', function(e) {
                        let value = this.value.replace(/[^0-9]/g, '');
                        if (value !== '' && !/^\d+$/.test(value)) {
                            this.value = '';
                            this.classList.add('border-red-500');
                            hargaSatuanError.textContent = 'Harga jual hanya boleh angka!';
                            hargaSatuanError.style.display = '';
                            return;
                        } else if (value === '' || Number(value) <= 0) {
                            this.classList.add('border-red-500');
                            hargaSatuanError.textContent = 'Harga jual harus berupa angka positif';
                            hargaSatuanError.style.display = '';
                        } else {
                            this.classList.remove('border-red-500');
                            hargaSatuanError.textContent = '';
                            hargaSatuanError.style.display = 'none';
                        }

                        // Format as Rupiah for display
                        this.value = formatRupiah(value);
                    });

                    // On focus, remove formatting for easier editing
                    hargaSatuan.addEventListener('focus', function() {
                        this.value = getNumericValue(this.value);
                    });

                    // On blur, reformat as Rupiah
                    hargaSatuan.addEventListener('blur', function() {
                        this.value = formatRupiah(this.value);
                    });
                }

                // When adding row, get the numeric value for storage
                const addRowBtn = document.getElementById('addRow');
                if (addRowBtn) {
                    addRowBtn.addEventListener('click', function() {
                        if (hargaSatuan) {
                            // Set a data attribute with the numeric value for use in the table/hidden input
                            hargaSatuan.setAttribute('data-numeric', getNumericValue(hargaSatuan.value));
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
                const hargaSatuanInput = document.getElementById('harga_satuan');
                const hargaSatuanNumeric = getNumericValue(hargaSatuanInput.value);
                const hargaSatuanFormatted = formatRupiah(hargaSatuanNumeric);
                const kuantitasMasuk = document.getElementById('kuantitas_masuk').value;
                const merekId = document.getElementById('merek_id').value;
                const satuan = document.getElementById('satuan_barang').value;

                if (!namaBarang || !hargaSatuanFormatted || !merekId) {
                    alert('Silakan isi nama barang, merek dan harga.');
                    return;
                }

                letKategoriLabel = '';
                switch (kategoriBarang) {
                    case '1':
                        kategoriLabel = 'Kebutuhan Harian';
                        break;
                    case '2':
                        kategoriLabel = 'Perawatan Kebersihan';
                        break;
                    case '3':
                        kategoriLabel = 'Produk Kesehatan';
                        break;
                    case '4':
                        kategoriLabel = 'Peralatan Sekolah';
                        break;
                    case '5':
                        kategoriLabel = 'Aksesoris Fashion';
                        break;
                    case '6':
                        kategoriLabel = 'Aksesoris Hiasan';
                        break;
                    default:
                        kategoriLabel = kategoriBarang;
                }

                let satuanLabel = '';
                if (satuan === '1') {
                    satuanLabel = 'pcs/eceran';
                } else if (satuan === '2') {
                    satuanLabel = 'kg';
                } else if (satuan === '3') {
                    satuanLabel = 'dus';
                } else {
                    satuanLabel = satuan;
                }


                const tableBody = document.getElementById('barangTableBody');

                let isDuplicate = false;
                for (let i = 0; i < tableBody.rows.length; i++) {
                    const cellNamaBarang = tableBody.rows[i].cells[1]; // 2nd column (index 1)
                    const cellNamaMerek = tableBody.rows[i].cells[2]; // 3rd column (index 2)
                    if (
                        cellNamaBarang &&
                        cellNamaMerek &&
                        cellNamaBarang.textContent.trim().toLowerCase() === namaBarang.trim().toLowerCase() &&
                        cellNamaMerek.textContent.trim().toLowerCase() === namaMerek.trim().toLowerCase()
                    ) {
                        isDuplicate = true;
                        break;
                    }
                }
                if (isDuplicate) {
                    alert('Nama barang dengan merek yang sama sudah ada di tabel!');
                    return;
                }

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
                    <td class="px-4 py-2 border-b text-center">${kategoriLabel}</td>
                    <td class="px-4 py-2 border-b text-center">${hargaSatuanFormatted}</td>
                    <td class="px-4 py-2 border-b text-center">${satuanLabel}</td>
                    <td class="px-4 py-2 border-b">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <!-- Preview container with border placeholder -->
                            <div id="${previewId}" class="border-2 border-dashed border-gray-300 rounded-lg w-1/2 min-h-[80px] flex flex-wrap items-center justify-center gap-2">
                                <!-- Images will appear here -->
                                <span class="text-gray-400 text-sm text-center ${previewContainer.innerHTML ? 'hidden' : ''}"></span>
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

                // Define the function to remove an image from the file input
                function removeImageFromInput(fileInput, index) {
                    const dataTransfer = new DataTransfer();
                    const files = fileInput.files;

                    // Add all files except the one to be removed
                    for (let i = 0; i < files.length; i++) {
                        if (i !== index) {
                            dataTransfer.items.add(files[i]);
                        }
                    }

                    // Update the file input with the new file list
                    fileInput.files = dataTransfer.files;
                }

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
                // Validate file type
                const allowedImageTypes = ['image/jpeg', 'image/png'];
                if (!allowedImageTypes.includes(file.type)) {
                    alert('Tipe gambar tidak sesuai(harus JPEG atau PNG).');
                    return;
                }

                const img = new Image();
                img.onload = function() {
                    // Validate image dimensions
                    if (img.width < 400 || img.height < 400 || img.width > 1200 || img.height > 1200) {
                        alert('Resolusi gambar tidak sesuai(harus 400x400 sampai 1200x1200).');
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewDiv = document.createElement('div');
                        previewDiv.className = 'relative group';

                        const imgElement = document.createElement('img');
                        imgElement.src = e.target.result;
                        imgElement.className =
                            'h-16 w-16 object-cover rounded cursor-pointer hover:opacity-80';
                        imgElement.onclick = function() {
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
                            if (previewContainer.children.length === 0 && placeholderText) {
                                placeholderText.classList.remove('hidden');
                            }
                        };

                        previewDiv.appendChild(imgElement);
                        previewDiv.appendChild(deleteBtn);
                        previewContainer.appendChild(previewDiv);
                    };
                    reader.readAsDataURL(file);
                };
                img.onerror = function() {
                    alert('Failed to load the image. Please try again.');
                };
                img.src = URL.createObjectURL(file);
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
                    <input type="hidden" name="items[${rowIndex}][harga_satuan]" value="${hargaSatuanNumeric}">
                    <input type="hidden" name="items[${rowIndex}][kuantitas_masuk]" value="${kuantitasMasuk}">
                    <input type="hidden" name="items[${rowIndex}][satuan_barang]" value="${satuan}">
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
