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

                        if (!hiddenInput.value) {
                            input.value = ''
                        }
                    }
                });
            }
        });

        function updateNoDataRow() {
            const tableBody = document.getElementById('transaction-table-body');
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

        // search popup function
        document.getElementById('search-barcode-btn').addEventListener('click', function(e) {
            e.preventDefault();

            const barcode = document.getElementById('nama_barang').value;
            if (!barcode) {
                alert('Please input barcode.');
                return;
            }

            fetch(`/daftar-produk/search-detail?barcode=${encodeURIComponent(barcode)}`)
                .then(res => res.json())
                .then(data => {
                    if (!data) {
                        alert('Barcode not found.');
                        return;
                    };

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

        document.addEventListener('DOMContentLoaded', function() {
            const externalDateInput = document.getElementById('tanggal_keluar_external');
            const hiddenDateInput = document.getElementById('tanggal_keluar');
            const form = document.getElementById('form-bkeluar');

            // Set initial value
            hiddenDateInput.value = externalDateInput.value;

            // Update hidden input whenever external input changes
            externalDateInput.addEventListener('change', function() {
                hiddenDateInput.value = this.value;
            });

            // Ensure value is up-to-date before form submit
            form.addEventListener('submit', function() {
                hiddenDateInput.value = externalDateInput.value;
            });
        });

        // add item to row
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

                if(qty > detail.stok) {
                    alert('Jumlah pengeluaran melebihi stok barang pada barcode ini.');
                    return;
                }
                    
            
            const id = detail.idBarang;
            const name = detail.nama;
            const price = parseFloat(detail.harga) || 0;
            const total = price * qty;

            const tableBody = document.getElementById('transaction-table-body');

            let totalKuantitasForBarcode = 0;
            Array.from(tableBody.querySelectorAll('tr')).forEach(row => {
                const rowBarcode = row.cells[2]?.textContent.trim();
                const rowKuantitas = parseInt(row.cells[3]?.textContent.trim(), 10) || 0;
                if (rowBarcode === barcode) {
                    totalKuantitasForBarcode += rowKuantitas;
                }
            });

            // Create new row
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="p-2 border text-center">${id}</td>
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

                // Update the total invoice after removal
                updateTotalInvoice();
                updateNoDataRow()
            });

            // Update the total invoice after adding the row
            updateTotalInvoice();
            updateNoDataRow()
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

        document.getElementById('cash-input').addEventListener('input', function() {
            const cashValue = parseFloat(this.value.replace(/[^0-9]/g, '')) || 0;

            const totalText = document.getElementById('invoice-total-input').value;
            const totalValue = parseFloat(totalText.replace(/[^0-9]/g, '')) || 0;

            const change = cashValue - totalValue;
            const formattedChange = `Rp. ${change.toLocaleString('id-ID')}`;

            document.getElementById('change-output').value = formattedChange;
        });

        //form submission
        document.getElementById('process-button').addEventListener('click', function() {
            const rows = document.querySelectorAll('#transaction-table-body tr');
            const hiddenRowsDiv = document.getElementById('hiddenRows');
            hiddenRowsDiv.innerHTML = ''; // Clear previous inputs if any

            const kategoriAlasan = document.getElementById('kategoriKet').value;
            const keterangan = document.querySelector('textarea').value;

            let hasValidRow = false;

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length < 6) return;

                const barangId = cells[0].innerText.trim();
                const barcode = cells[1].innerText.trim();
                const namaBarang = cells[2].innerText.trim();
                const hargaText = cells[3].innerText.replace(/[^\d]/g, '');
                const qty = parseInt(cells[4].innerText.trim(), 10);
                const subtotalText = cells[5].innerText.replace(/[^\d]/g, '');

                if (!barcode || !qty || !subtotalText) return;

                hasValidRow = true;

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'barang_keluar[]';
                input.value = JSON.stringify({
                    barang_id: barangId,
                    barcode: barcode,
                    nama_barang: namaBarang,
                    kuantitas_keluar: qty,
                    subtotal: parseInt(subtotalText),
                    kategori_alasan: kategoriAlasan,
                    keterangan: keterangan
                });

                hiddenRowsDiv.appendChild(input);
            });

            if (!hasValidRow) {
                alert("Tidak ada item untuk disimpan.");
                return;
            }

            document.getElementById('form-bkeluar').submit();
        });