document.addEventListener('DOMContentLoaded', function() {

        setupSearchableInput({
        inputId: 'nama_barang',
        hiddenId: 'barang_id',
        suggestionBoxId: 'barang-suggestions',
        searchUrl: '/daftar-produk/search/barcode',
        valueKeys: {
            id: 'idBarang',
            name: 'namaBarang',
            barcode: 'barcode',
            satuan: 'satuan',
            merek: 'merekNama',
            tglKadaluarsa: 'tglKadaluarsa',
            harga: 'hargaJual',
            kategori: 'kategoriBarang',
            quantity: 'quantity',
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
                    if(query === '') {
                        hiddenInput.value = '';
                        document.getElementById('barcode_field').value = '';
                        document.getElementById('tglKadaluarsa_field').value = '';
                        document.getElementById('merek_field').value = '';
                        suggestionBox.classList.add('hidden');
                        return;
                    }
                    if (query.length >= 2) {
                        fetch(`${searchUrl}?q=${encodeURIComponent(query)}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.length > 0) {
                                   suggestionBox.innerHTML = data.map(item => {
                                        const kategori = parseInt(item[valueKeys.kategori]);
                                        // Only show Exp: if kategori is 1, 2, or 3
                                        let expDisplay = '';
                                        if ([1, 2, 3].includes(kategori)) {
                                            expDisplay = `Exp: ${item[valueKeys.tglKadaluarsa] ? item[valueKeys.tglKadaluarsa] : 'Tidak Tersedia'} <br>`;
                                        }
                                        return `
                                            <div class="px-3 py-2 cursor-pointer hover:bg-gray-100"
                                                data-id="${item[valueKeys.id]}"
                                                data-name="${item[valueKeys.name]}"
                                                data-barcode="${item[valueKeys.barcode]}"
                                                data-satuan="${item[valueKeys.satuan]}"
                                                data-merek="${item[valueKeys.merek]}"
                                                data-tgl="${item[valueKeys.tglKadaluarsa]}"
                                                data-harga="${item[valueKeys.harga]}"
                                                data-kategori="${item[valueKeys.kategori] || ''}"
                                                data-quantity="${item[valueKeys.quantity] || ''}">
                                                <div class="font-semibold">${item[valueKeys.name]}</div>
                                                <div class="text-sm text-gray-600">
                                                    Barcode: ${item[valueKeys.barcode]} <br>
                                                    ${expDisplay}
                                                    Harga: ${formatRupiah(item[valueKeys.harga])} <br>
                                                    Stok: ${item[valueKeys.quantity] || 'Tidak tersedia'}
                                                </div>
                                            </div>
                                        `;
                                    }).join('');
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

                            const namaBarangField = document.getElementById('nama_barang');
                            const barcodeField = document.getElementById('barcode_field');
                            const tglKadaluarsaField = document.getElementById('tglKadaluarsa_field');
                            const merekField = document.getElementById('merek_field');
                            const hargaField = document.getElementById('hargaBarang');
                            const satuan = item.getAttribute('data-satuan');
                            const kauntitasField = document.getElementById('qty');
                            let satuanText = '';

                            if(satuan == 1){
                                satuanText = 'Pcs';
                            } else if(satuan == 2){
                                satuanText = 'Kg';
                            } else if(satuan == 3){
                                satuanText = 'Dus';
                            }

                            if(namaBarangField){
                                barcodeField.value = item.getAttribute('data-barcode');
                                tglKadaluarsaField.value = item.getAttribute('data-tgl');
                                merekField.value = item.getAttribute('data-merek');
                                hargaField.textContent = "Harga: " + formatRupiah(item.getAttribute('data-harga')) + " / " + satuanText;

                                if(satuan == 1){
                                    kauntitasField.value = item.getAttribute('data-quantity');
                                } else if(satuan == 2){
                                    kauntitasField.value = item.getAttribute('data-quantity') + "000";
                                } else if(satuan == 3){
                                    kauntitasField.value = item.getAttribute('data-quantity');
                                }
                            }

                        });
                    });
                }

                document.addEventListener('click', function(e) {
                    if (!suggestionBox.contains(e.target) && e.target !== input) {
                        suggestionBox.classList.add('hidden');

                        const barcodeField = document.getElementById('barcode_field');
                        const tglKadaluarsaField = document.getElementById('tglKadaluarsa_field');
                        const merekField = document.getElementById('merek_field');
                        const hargaField = document.getElementById('hargaBarang');

                        if (!hiddenInput.value) {
                            input.value = '';
                            barcodeField.value = '';
                            tglKadaluarsaField.value = '';
                            merekField.value = '';
                            hargaField.textContent = '';
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
        // document.getElementById('search-barcode-btn').addEventListener('click', function(e) {
        //     e.preventDefault();

        //     const barcode = document.getElementById('nama_barang').value;
        //     if (!barcode) {
        //         alert('Silakan masukkan nama barang atau barcode.');
        //         return;
        //     }

        //     fetch(`/daftar-produk/search-detail?barcode=${encodeURIComponent(barcode)}`)
        //         .then(res => res.json())
        //         .then(data => {
        //             if (!data) {
        //                 alert('Barcode not found.');
        //                 return;
        //             };

        //             let satuanLabel = '';
        //             if(data.satuan == 2){
        //                 satuanLabel = 'Kg';
        //             } else
        //             {
        //                 satuanLabel = 'pcs';
        //             }

        //             // Fill popup content
        //             document.getElementById('popup-barcode').innerText = data.barcode;
        //             document.getElementById('popup-name').innerText = data.nama;
        //             document.getElementById('popup-price').innerText = data.harga || '-';
        //             document.getElementById('popup-satuan').innerText = satuanLabel;
        //             document.getElementById('popup-stock').innerText = data.stok || 0;

        //             // Position popup near button
        //             const btn = document.getElementById('search-barcode-btn');
        //             const popup = document.getElementById('barcode-popup');
        //             const rect = btn.getBoundingClientRect();

        //             popup.style.top = `${rect.bottom + window.scrollY + 8}px`;
        //             popup.style.left = `${rect.left + window.scrollX}px`;
        //             popup.classList.remove('hidden');
        //         })
        //         .catch(err => console.error('Error loading detail:', err));
        // });
        // document.addEventListener('click', function(e) {
        //     const popup = document.getElementById('barcode-popup');
        //     const button = document.getElementById('search-barcode-btn');

        //     if (!popup.contains(e.target) && !button.contains(e.target)) {
        //         popup.classList.add('hidden');
        //     }
        // });

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
            const barcode = document.getElementById('barcode_field').value.trim();
            const nama_akun = document.getElementById('nama_akun').value.trim();
            const qty = parseInt(document.getElementById('qty')?.value || "1", 10);
            const totalInvoiceElement = document.getElementById('invoice-total'); // Get the total invoice element

            if (!barcode || qty <= 0) {
                alert('Silakan masukkan nama barang atau barcode dan kuantitas.');
                return;
            }

            if(!nama_akun){
                alert('Nama Penanggung Jawab belum diisi.')
                return
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

            const tableBody = document.getElementById('transaction-table-body');

            // Calculate total quantity for this barcode already in the table
            let totalKuantitasForBarcode = 0;
            Array.from(tableBody.querySelectorAll('tr')).forEach(row => {
                const rowBarcode = row.cells[1]?.textContent.trim(); // barcode is in cell 1 (second column)
                const rowKuantitasCell = row.cells[4]?.textContent.trim(); // quantity is in cell 4 (fifth column)
                let rowKuantitas = 0;
                if (rowKuantitasCell) {
                    // Remove ' gr' if present for KG mode
                    rowKuantitas = parseInt(rowKuantitasCell.replace(' gr', '').trim(), 10) || 0;
                }
                if (rowBarcode === barcode) {
                    totalKuantitasForBarcode += rowKuantitas;
                }
            });

            // Logic for satuan
            if (detail.satuan == 2) { // KG mode, qty is in grams
                // Minimal pengeluaran 50 gram
                if (qty < 50) {
                    alert('Pengeluaran barang dengan satuan kg minimal 50 gram.');
                    return;
                }
                // Check stock in grams
                const stokGram = parseFloat(detail.stok) * 1000; // stok in kg -> grams
                if ((totalKuantitasForBarcode + qty) > stokGram) {
                    alert('Jumlah pengeluaran melebihi stok barang pada barcode ini.');
                    return;
                }
            } else { // PCS mode
                if ((totalKuantitasForBarcode + qty) > detail.stok) {
                    alert('Jumlah pengeluaran melebihi stok barang pada barcode ini.');
                    return;
                }
            }

            const id = detail.idBarang;
            const name = detail.nama;
            const price = parseFloat(detail.harga) || 0;

            let totalPrice = '';
            if (detail.satuan == 2) {
                // Price per kg * (qty in grams / 1000)
                const total = price * (qty / 1000);
                totalPrice = total.toLocaleString('id-ID');
            } else {
                const total = price * qty;
                totalPrice = total.toLocaleString('id-ID');
            }

            // Create new row
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="p-2 border text-center">${id}</td>
                <td class="p-2 border text-center">${barcode}</td>
                <td class="p-2 border">${name}</td>
                <td class="p-2 border text-right">Rp. ${price.toLocaleString('id-ID')}</td>
                <td class="p-2 border text-center">${qty}${detail.satuan == 2 ? ' gr' : ''}</td>
                <td class="p-2 border text-right">Rp. ${totalPrice}</td>
                <td class="p-2 border text-center"><button class="text-red-500 hover:underline remove-item">Remove</button></td>
            `;

            tableBody.appendChild(row);

            // Reset inputs
            document.getElementById('nama_barang').value = '';
            document.getElementById('qty').value = 1;
            document.getElementById('barcode_field').value = '';
            document.getElementById('tglKadaluarsa_field').value = '';
            document.getElementById('merek_field').value = '';

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
    // Calculate the total from all rows
    const rows = document.querySelectorAll('#transaction-table-body tr');
    let total = 0;

    rows.forEach(row => {
        const totalCell = row.cells[5]; // The total cell in each row (6th column)
        if (totalCell) {
            // Remove "Rp. ", dots, and commas for parsing
            let rowTotal = totalCell.innerText.replace(/[^0-9]/g, '');
            rowTotal = parseInt(rowTotal, 10) || 0;
            total += rowTotal;
        }
    });

    // Format the total as Indonesian Rupiah
    const formattedTotal = `Rp. ${total.toLocaleString('id-ID')}`;

    // Update the total invoice display
    const invoiceSpan = document.getElementById('invoice-total');
    if (invoiceSpan) {
        invoiceSpan.innerText = formattedTotal;
    }

    // Update the hidden/visible input for total (if exists)
    const invoiceInput = document.getElementById('invoice-total-input');
    if (invoiceInput) {
        invoiceInput.value = `Rp. ${total.toLocaleString('id-ID')}`; // Use raw number for backend processing
    }
}

        document.addEventListener('DOMContentLoaded', function() {
    const cashInput = document.getElementById('cash-input');

    // Create or get the error message element for cash-input
    let cashInputError = document.getElementById('cashInputError');
    if (!cashInputError && cashInput) {
        cashInputError = document.createElement('div');
        cashInputError.id = 'cashInputError';
        cashInputError.className = 'text-red-500 text-xs mt-1';
        cashInputError.style.display = 'none';
        cashInput.parentNode.appendChild(cashInputError);
    }


    if (cashInput) {
        cashInput.addEventListener('input', function() {
            let value = this.value.replace(/[^0-9]/g, '');
            if (value !== '' && !/^\d+$/.test(value)) {
                this.value = '';
                this.classList.add('border-red-500');
                return;
            } else if (value === '' || Number(value) <= 0) {
                this.classList.add('border-red-500');
            } else {
                this.classList.remove('border-red-500');
            }

            // Format as Rupiah for display
            this.value = formatRupiah(value);

            const cashValue = parseFloat(value) || 0;
            const totalText = document.getElementById('invoice-total-input').value;
            const totalValue = parseFloat(totalText.replace(/[^0-9]/g, '')) || 0;

            const change = cashValue - totalValue;
            const formattedChange = `Rp. ${change.toLocaleString('id-ID')}`;

            document.getElementById('change-output').value = formattedChange;
        });

        // On focus, remove formatting for easier editing
        cashInput.addEventListener('focus', function() {
            this.value = getNumericValue(this.value);
        });

        // On blur, reformat as Rupiah
        cashInput.addEventListener('blur', function() {
            let numericValue = getNumericValue(this.value);
            if (numericValue === '') {
                numericValue = '0'; // Default to 0 if empty
            }
            this.value = formatRupiah(numericValue);
        });
    }
});

// Helper functions
function formatRupiah(value) {
    return 'Rp. ' + parseInt(value, 10).toLocaleString('id-ID');
}

function getNumericValue(value) {
    return value.replace(/[^0-9]/g, '');
}

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
