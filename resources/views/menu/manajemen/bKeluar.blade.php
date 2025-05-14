@extends('layout')

@section('content')
    <div class="p-6 space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold">Barang <span class="text-gray-500">Keluar</span></h1>
                <p class="text-sm text-gray-600">Transaction > Sales</p>
            </div>

        </div>

        <!-- Form Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Date, Cashier, Customer -->
            <div class="space-y-2 bg-white rounded-md shadow p-4">
                <label class="block text-sm font-medium">Date</label>
                <input type="date" class="w-full border border-gray-300 rounded p-2" value="{{ now()->format('Y-m-d') }}">

                <div class="relative">
                    <label class="block text-sm font-medium">Staff</label>
                    <input id="nama_akun" type="text" class="w-full border border-gray-300 rounded p-2"
                        value="{{ session('user_data')->nama }} ({{ session('user_data')->idAkun }})">
                    <div id="staff-suggestions"
                        class="w-full border rounded-md px-3 py-2 absolute z-10 bg-white mt-1 hidden max-h-60 overflow-auto">
                        <!-- Suggestions will appear here -->
                    </div>
                    <input type="hidden" id="akun_id" name="idAkun" />
                </div>


                <label class="block text-sm font-medium">Customer</label>
                <select class="w-full border border-gray-300 rounded p-2">
                    <option>Umum</option>
                    <option>Pribadi</option>
                </select>
            </div>

            <!-- Barcode & Qty Input -->
            <div class="bg-white rounded-md shadow p-4 flex flex-col justify-between h-full min-h-[220px]">
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Barcode</label>
                    <div class="flex w-full gap-2">
                        <div class="relative flex-grow">
                            <input id="nama_barang" type="text" class="w-full border border-gray-300 rounded p-2">
                            <div id="barang-suggestions"
                                class="w-full border rounded-md px-3 py-2 absolute z-10 bg-white mt-1 hidden max-h-60 overflow-auto">
                                <!-- Suggestions will appear here -->
                            </div>
                            <input type="hidden" id="barang_id" name="idBarang" />
                        </div>
                        <button id="search-barcode-btn" class="bg-blue-500 text-white px-3 py-2 rounded">
                            <i class="fas fa-search"></i>
                        </button>

                        <!-- Popup container -->
                        <div id="barcode-popup" class="absolute bg-white border shadow rounded-md p-4 text-sm hidden z-50"
                            style="min-width: 200px;">
                            <strong>Barcode Details</strong>
                            <div>Barcode: <span id="popup-barcode"></span></div>
                            <div>Name: <span id="popup-name"></span></div>
                            <div>Price: <span id="popup-price"></span></div>
                            <div>Stock: <span id="popup-stock"></span></div>
                        </div>

                    </div>

                    <label class="block text-sm font-medium">Qty</label>
                    <input id="qty" type="number" class="w-full border border-gray-300 rounded p-2" value="1"
                        min="1">
                </div>

                <div class="flex justify-end mt-4">
                    <button id="add-barcode-btn" class="bg-blue-600 text-white px-4 py-2 rounded">+ Add</button>
                </div>
            </div>

            <!-- Invoice Total -->
            <div class="bg-white rounded-md shadow p-4 flex flex-col justify-between">
                <div class="flex justify-end">
                    <span class="text-lg font-semibold">Invoice <span class="text-blue-600">MP1909250001</span></span>
                </div>
                <div class="flex justify-center items-center flex-1">
                    <span id="invoice-total" class="text-6xl font-bold text-gray-700">Rp.0</span>
                </div>
            </div>

        </div>


        <!-- Table Section -->
        <div class="overflow-x-auto bg-white rounded-md shadow p-4 my-4">
            <table class="min-w-full border border-gray-300 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 border">#</th>
                        <th class="p-2 border">Barcode</th>
                        <th class="p-2 border">Product Item</th>
                        <th class="p-2 border">Price</th>
                        <th class="p-2 border">Qty</th>
                        <th class="p-2 border">Subtotal</th>
                        <th class="p-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody id="transaction-table-body">
                    <tr class="no-items-row">
                        {{-- <td class="p-2 border text-center" colspan="8">Tidak ada item</td> --}}
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Payment Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 ">


            <div class="space-y-2 bg-white rounded-md shadow p-4">
                <label class="block text-sm font-medium">GrandTotal</label>
                <input id="invoice-total-input" value="" type="text" class="w-full border border-gray-300 rounded p-2" readonly>

                <label class="block text-sm font-medium">Cash</label>
                <input id="cash-input" type="text" value="0" class="w-full border border-gray-300 rounded p-2">

                <label class="block text-sm font-medium">Change</label>
                <input id="change-output" type="text" class="w-full border border-gray-300 rounded p-2" readonly>

            </div>

            <div class="space-y-2 bg-white rounded-md shadow p-4">
                <label for="kategoriKet">Keterangan Pengeluaran</label>
                <select name="kategoriKet" id="kategoriKet" class="w-full border border-gray-300 rounded p-2">
                    <option value="1">Jual</option>
                    <option value="2">Pribadi</option>
                </select>
                <label class="block text-sm font-medium">Note</label>
                <textarea class="w-full border border-gray-300 rounded p-2"></textarea>

                <div class="flex gap-2 mt-2">
                    <button class="bg-yellow-500 text-white px-4 py-2 rounded">Cancel</button>
                    <button class="bg-green-600 text-white px-4 py-2 rounded">Process</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setupSearchableInput({
                inputId: 'nama_barang',
                hiddenId: 'barang_id',
                suggestionBoxId: 'barang-suggestions',
                searchUrl: '/daftar-produk/search/barcode',
                valueKeys: {
                    id: 'idBarang',
                    name: 'barcode'
                }
            });
            setupSearchableInput({
                inputId: 'nama_akun',
                hiddenId: 'akun_id',
                suggestionBoxId: 'staff-suggestions',
                searchUrl: '/akun/search',
                valueKeys: {
                    id: 'idAkun',
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

                document.addEventListener('click', function(e) {
                    if (!suggestionBox.contains(e.target) && e.target !== input) {
                        suggestionBox.classList.add('hidden');
                    }
                });
            }
        });

        // search popup function
        document.getElementById('search-barcode-btn').addEventListener('click', function(e) {
            e.preventDefault();

            const barcode = document.getElementById('nama_barang').value;
            if (!barcode) return;

            fetch(`/daftar-produk/search-detail?barcode=${encodeURIComponent(barcode)}`)
                .then(res => res.json())
                .then(data => {
                    if (!data) return;

                    // Fill popup content
                    document.getElementById('popup-barcode').innerText = data.barcode;
                    document.getElementById('popup-name').innerText = data.nama;
                    document.getElementById('popup-price').innerText = data.harga || '-';
                    document.getElementById('popup-stock').innerText = data.stok || 0;

                    // Position popup near button
                    const btn = document.getElementById('search-barcode-btn');
                    const popup = document.getElementById('barcode-popup');
                    const rect = btn.getBoundingClientRect();

                    popup.style.top = `${rect.bottom + window.scrollY + 8}px`;
                    popup.style.left = `${rect.left + window.scrollX}px`;
                    popup.classList.remove('hidden');
                })
                .catch(err => console.error('Error loading detail:', err));
        });
        document.addEventListener('click', function(e) {
            const popup = document.getElementById('barcode-popup');
            const button = document.getElementById('search-barcode-btn');

            if (!popup.contains(e.target) && !button.contains(e.target)) {
                popup.classList.add('hidden');
            }
        });

        document.getElementById('add-barcode-btn').addEventListener('click', async function() {
            const barcode = document.getElementById('nama_barang').value.trim();
            const qty = parseInt(document.getElementById('qty')?.value || "1", 10);
            const totalInvoiceElement = document.getElementById(
            'invoice-total'); // Get the total invoice element

            if (!barcode || qty <= 0) {
                alert('Please enter a valid barcode and quantity.');
                return;
            }

            // Fetch detail based on barcode
            let detail;

            try {
                const response = await fetch(`/daftar-produk/search-detail?barcode=${barcode}`);
                if (!response.ok) throw new Error('Not Found');

                detail = await response.json();
            } catch (e) {
                alert('Barcode not found.');
                return;
            }

            const name = detail.nama;
            const price = parseFloat(detail.harga) || 0;
            const total = price * qty;

            const tableBody = document.getElementById('transaction-table-body');

            // Remove "Tidak ada item" row if exists
            const noItemRow = document.getElementById('no-items-row');
            if (noItemRow) noItemRow.remove();

            // Create new row
            const row = document.createElement('tr');
            row.innerHTML = `
            <td class="p-2 border text-center">*</td>
            <td class="p-2 border">${barcode}</td>
            <td class="p-2 border">${name}</td>
            <td class="p-2 border">Rp. ${price.toLocaleString()}</td>
            <td class="p-2 border">${qty}</td>
            <td class="p-2 border">Rp. ${total.toLocaleString()}</td>
            <td class="p-2 border text-center"><button class="text-red-500 hover:underline remove-item">Remove</button></td>
        `;

            tableBody.appendChild(row);

            // Reset inputs
            document.getElementById('nama_barang').value = '';
            document.getElementById('qty').value = 1;

            // Add remove event
            row.querySelector('.remove-item').addEventListener('click', function() {
                row.remove();
                if (tableBody.querySelectorAll('tr').length === 0) {
                    const noItemRow = document.createElement('tr');
                    noItemRow.id = 'no-items-row';
                    noItemRow.innerHTML = `
                <td class="p-2 border text-center" colspan="8">Tidak ada item</td>
            `;
                    tableBody.appendChild(noItemRow);
                }

                // Update the total invoice after removal
                updateTotalInvoice();
            });

            // Update the total invoice after adding the row
            updateTotalInvoice();
        });

        // Function to update the total invoice
        function updateTotalInvoice() {
            // Calculate the total
            const rows = document.querySelectorAll('#transaction-table-body tr');
            let total = 0;

            rows.forEach(row => {
                const totalCell = row.cells[5]; // The total cell in each row (6th column)
                if (totalCell) {
                    const rowTotal = parseFloat(totalCell.innerText.replace("Rp. ", "").replace(",", "")) || 0;
                    total += rowTotal;
                }
            });

            // Update the total invoice element
            const totalInvoiceElement = document.getElementById('invoice-total');
            totalInvoiceElement.innerText = `Rp. ${total.toLocaleString()}`;
            
            // Get current total from input (if already has value)
            let invoiceSpan = document.getElementById('invoice-total');
            let invoiceInput = document.getElementById('invoice-total-input');

            // Remove "Rp." and formatting from span
            let currentTotal = parseFloat(invoiceSpan.innerText.replace(/[^0-9]/g, '')) || 0;

            // Add new total
            let newTotal = currentTotal + total;

            // Update both display elements
            invoiceSpan.innerText = `Rp. ${newTotal.toLocaleString('id-ID')}`;
            invoiceInput.value = `Rp. ${newTotal.toLocaleString('id-ID')}`;
        }

        document.getElementById('cash-input').addEventListener('input', function () {
        const cashValue = parseFloat(this.value.replace(/[^0-9]/g, '')) || 0;

        const totalText = document.getElementById('invoice-total-input').value;
        const totalValue = parseFloat(totalText.replace(/[^0-9]/g, '')) || 0;

        const change = cashValue - totalValue;
        const formattedChange = `Rp. ${change.toLocaleString('id-ID')}`;

        document.getElementById('change-output').value = formattedChange;
    });
    </script>
@endsection
