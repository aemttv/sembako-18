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
                                <input type="text" id="harga_satuan" class="w-full border rounded-md px-3 py-2"
                                    maxlength="16" />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Satuan</label>
                                <input type="text" id="satuan"
                                    class="w-full border rounded-md px-3 py-2 bg-gray-100 cursor-no-drop" value="Pcs/Eceran"
                                    readonly />
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Kuantitas Masuk</label>
                                <input type="number" id="kuantitas_masuk" class="w-full border rounded-md px-3 py-2"
                                    min="1" value="1" max="100"
                                    oninput="
                                        if(this.value.length > 3) this.value = this.value.slice(0,3);
                                        if(this.value == 0) this.value = 1;
                                    " />
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Tanggal Masuk</label>
                                <input type="date" id="tanggal_masuk" name="tglMasuk"
                                    class="w-full border rounded-md px-3 py-2 @error('tglMasuk') border-red-500 @enderror"
                                    value="{{ old('tglMasuk', now()->format('Y-m-d')) }}"
                                    max="{{ now()->addYear()->format('Y-m-d') }}" onchange="updateMinExpiryDate()"
                                    />
                                @error('tglMasuk')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Tanggal Kadaluwarsa</label>
                                <input type="date" id="tanggal_kadaluwarsa" name="tglKadaluwarsa"
                                    class="w-full border rounded-md px-3 py-2 @error('tglKadaluwarsa') border-red-500 @enderror"
                                    value="{{ old('tglKadaluwarsa', now()->addMonth()->format('Y-m-d')) }}"
                                    onchange="validateDates()"/>
                                @error('tglKadaluwarsa')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                                <span id="date_error" class="text-red-500 text-xs" style="display:none;">
                                    Tanggal kadaluwarsa harus setelah tanggal masuk.
                                </span>
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
                                    class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center relative cursor-pointer">
                                    <input type="file" id="notaFile" name="nota_file" class="hidden"
                                        accept="image/*,.pdf,.doc,.docx" />
                                    <div id="previewContainer" class="flex flex-col items-center justify-center">
                                        <img id="imagePreview" src="" alt="Image Preview"
                                            class="max-h-40 mb-2 hidden" />
                                        <span id="fileName" class="text-sm text-gray-600"></span>
                                        <span id="uploadPrompt" class="text-gray-400">Drag & drop or click to upload a
                                            file</span>
                                    </div>
                                </div>
                                <p id="fileName" class="text-sm text-gray-600 mt-2"></p>
                                <span class="text-xs text-orange-500 mt-1">
                                            Resolusi gambar harus minimal 400x400px dan maksimal 1200x1200px.
                                        </span>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    {{-- <div class="px-6 pb-6 flex justify-end gap-4">
                        <button id="addRow" type="button"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Masukkan
                            Barang</button>
                    </div> --}}
                </div>
            </div>

            <div class="mt-6 border rounded-lg shadow-sm bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 text-center cursor-pointer"
            id="addRow">
                (+) Tambah Data Barang Ke Tabel
            </div>

            <div id="hiddenRows"></div>

            <div class="mt-6 border rounded-lg bg-white shadow-sm">
                <div class="border-b px-6 py-3 font-medium text-gray-700">Daftar Simulasi Barang Masuk</div>
                <div class="p-4">
                    <table id="barangTable" class="min-w-full border border-gray-300 text-sm">
                        <thead class="bg-gray-100 uppercase text-md">
                            <tr>
                                <th class="px-4 py-2 border-b">ID Barang</th>
                                <th class="px-4 py-2 border-b">Nama Barang</th>
                                <th class="px-4 py-2 border-b">Harga Satuan</th>
                                <th class="px-4 py-2 border-b">Satuan</th>
                                <th class="px-4 py-2 border-b">Kuantitas</th>
                                <th class="px-4 py-2 border-b">Tanggal Masuk</th>
                                <th class="px-4 py-2 border-b">Tanggal Kadaluarsa</th>
                                <th class="px-4 py-2 border-b">Proses</th>
                            </tr>
                        </thead>
                        <tbody id="barangTableBody" class="text-center">
                            <!-- Rows will be added here dynamically -->
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

                            if (!hiddenInput.value) {
                                input.value = ''
                            }
                        }
                    });
                }
            });

            function updateNoDataRow() {
                const tableBody = document.getElementById('barangTableBody');
                const noDataRow = document.getElementById('noDataRow');
                // If there are no rows except the placeholder, show it
                if (tableBody.children.length === 0) {
                    // Add the placeholder row if not present
                    if (!noDataRow) {
                        const tr = document.createElement('tr');
                        tr.id = 'noDataRow';
                        tr.innerHTML = `<td colspan="8" class="text-center text-gray-500 py-4">Tidak ada data</td>`;
                        tableBody.appendChild(tr);
                    }
                } else {
                    // Remove the placeholder if there are other rows
                    if (noDataRow && tableBody.children.length > 1) {
                        noDataRow.remove();
                    }
                }
            }

            // File upload handling
            const uploadArea = document.getElementById('uploadArea');
            const notaFileInput = document.getElementById('notaFile');
            const fileNameDisplay = document.getElementById('fileName');
            const imagePreview = document.getElementById('imagePreview');
            const uploadPrompt = document.getElementById('uploadPrompt');

            // Click to open file dialog
            uploadArea.addEventListener('click', () => {
                notaFileInput.click();
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
                    notaFileInput.files = e.dataTransfer.files;
                    updateFileNameAndPreview();
                }
            });

            // Handle file selection
            notaFileInput.addEventListener('change', updateFileNameAndPreview);

            function updateFileNameAndPreview() {
                if (notaFileInput.files.length > 0) {
                    const file = notaFileInput.files[0];
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

            function updateMinExpiryDate() {
                const masuk = document.getElementById('tanggal_masuk');
                const kadaluwarsa = document.getElementById('tanggal_kadaluwarsa');

                // Get the selected date and add one day to it
                const masukDate = new Date(masuk.value);
                masukDate.setDate(masukDate.getDate() + 1);

                // Format the date as YYYY-MM-DD
                const minDate = masukDate.toISOString().split('T')[0];

                // Set the min attribute of the expiry date input
                kadaluwarsa.min = minDate;

                // Validate the current expiry date
                validateDates();
            }

            function validateDates() {
                const masuk = document.getElementById('tanggal_masuk').value;
                const kadaluwarsa = document.getElementById('tanggal_kadaluwarsa').value;
                const errorSpan = document.getElementById('date_error');

                if (masuk && kadaluwarsa) {
                    const masukDate = new Date(masuk);
                    const kadaluwarsaDate = new Date(kadaluwarsa);

                    if (kadaluwarsaDate <= masukDate) {
                        errorSpan.style.display = 'block';
                        // Reset the expiry date to the minimum allowed date
                        const minDate = new Date(masuk);
                        minDate.setDate(minDate.getDate() + 1);
                        document.getElementById('tanggal_kadaluwarsa').valueAsDate = minDate;
                    } else {
                        errorSpan.style.display = 'none';
                    }
                }
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
                            hargaSatuanError.textContent = 'Harga Satuan hanya boleh angka!';
                            hargaSatuanError.style.display = '';
                            return;
                        } else if (value === '' || Number(value) <= 0) {
                            this.classList.add('border-red-500');
                            hargaSatuanError.textContent = 'Harga Satuan harus berupa angka positif';
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

            // Menambahkan baris baru ke dalam tabel
            document.addEventListener('DOMContentLoaded', function() {
                const addRowBtn = document.getElementById('addRow');
                const tableBody = document.getElementById('barangTableBody');
                const hiddenRows = document.getElementById('hiddenRows');

                let rowIndex = 0;

                addRowBtn.addEventListener('click', function() {
                    const barangId = document.getElementById('barang_id').value;
                    const namaBarang = document.getElementById('nama_barang').value;
                    const hargaSatuanInput = document.getElementById('harga_satuan');
                    const hargaSatuanNumeric = hargaSatuanInput.getAttribute('data-numeric') || '';
                    const hargaSatuanFormatted = formatRupiah(hargaSatuanNumeric);
                    const satuan = document.getElementById('satuan').value;
                    const kuantitas = document.getElementById('kuantitas_masuk').value;
                    const tanggalMasuk = document.getElementById('tanggal_masuk').value;
                    const tanggalKadaluwarsa = document.getElementById('tanggal_kadaluwarsa').value;

                    if (!barangId || !namaBarang || !hargaSatuanFormatted || !kuantitas || !tanggalMasuk || !
                        tanggalKadaluwarsa) {
                        alert('Mohon lengkapi semua field penting.');
                        return;
                    }

                    function formatTanggalMasuk(dateStr) {
                        // dateStr is expected in 'YYYY-MM-DD'
                        const months = [
                            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                        ];
                        const [year, month, day] = dateStr.split('-');
                        return `${parseInt(day)} ${months[parseInt(month) - 1]} ${year}`;
                    }

                    // Tambahkan ke tabel tampilan
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="px-4 py-2 border-b">${barangId}</td>
                        <td class="px-4 py-2 border-b">${namaBarang}</td>
                        <td class="px-4 py-2 border-b">${hargaSatuanFormatted}</td>
                        <td class="px-4 py-2 border-b">${satuan}</td>
                        <td class="px-4 py-2 border-b">${kuantitas}</td>
                        <td class="px-4 py-2 border-b">${formatTanggalMasuk(tanggalMasuk)}</td>
                        <td class="px-4 py-2 border-b">${formatTanggalMasuk(tanggalKadaluwarsa)}</td>
                        <td class="px-4 py-2 border-b">
                            <button type="button" class="text-red-500 hover:underline remove-row" data-index="${rowIndex}">Hapus</button>
                        </td>
                    `;
                    tableBody.appendChild(tr);

                    hiddenRows.insertAdjacentHTML('beforeend', `
                        <input type="hidden" name="items[${rowIndex}][barang_id]" value="${barangId}">
                        <input type="hidden" name="items[${rowIndex}][nama_barang]" value="${namaBarang}">
                        <input type="hidden" name="items[${rowIndex}][harga_satuan]" value="${hargaSatuanNumeric}">
                        <input type="hidden" name="items[${rowIndex}][satuan]" value="${satuan}">
                        <input type="hidden" name="items[${rowIndex}][kuantitas_masuk]" value="${kuantitas}">
                        <input type="hidden" name="items[${rowIndex}][tanggal_masuk]" value="${tanggalMasuk}">
                        <input type="hidden" name="items[${rowIndex}][tanggal_kadaluwarsa]" value="${tanggalKadaluwarsa}">
                    `);

                    rowIndex++;
                    updateNoDataRow();

                    // Kosongkan field setelah input
                    document.getElementById('nama_barang').value = '';
                    document.getElementById('barang_id').value = '';
                    document.getElementById('harga_satuan').value = '';
                    document.getElementById('kuantitas_masuk').value = '1';
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
                        updateNoDataRow();
                    }
                });

                // Tombol kosongkan field
                document.getElementById('clearFields').addEventListener('click', function() {
                    document.getElementById('nama_barang').value = '';
                    document.getElementById('barang_id').value = '';
                    document.getElementById('harga_satuan').value = '';
                    document.getElementById('kuantitas_masuk').value = '1';
                    document.getElementById('tanggal_kadaluwarsa').value = '';
                });
                updateNoDataRow();
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
            updateMinExpiryDate();
        </script>

    </div>
@endsection
