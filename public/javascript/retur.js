document.addEventListener('DOMContentLoaded', function () {
    setupSearchableInput({
        inputId: 'nama_barang',
        hiddenId: 'barang_id',
        suggestionBoxId: 'barang-suggestions',
        searchUrl: '/daftar-produk/search/barcode',
        valueKeys: {
            id: 'idBarang',
            name: 'barcode'
        }
    })

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

    setupSearchableInput({
        inputId: 'nama_akun',
        hiddenId: 'akun_id',
        suggestionBoxId: 'akun-suggestions',
        searchUrl: '/akun/search',
        valueKeys: {
            id: 'idAkun',
            name: 'nama'
        }
    })

/**
 * Initializes a searchable input field with autocomplete suggestions.
 *
 * @param {Object} config - Configuration object for setting up the searchable input.
 * @param {string} config.inputId - The ID of the input element for entering search queries.
 * @param {string} config.hiddenId - The ID of the hidden input element to store the selected item's ID.
 * @param {string} config.suggestionBoxId - The ID of the element to display autocomplete suggestions.
 * @param {string} config.searchUrl - The URL endpoint to fetch search suggestions from.
 * @param {Object} config.valueKeys - Object containing keys to map the suggestion data.
 * @param {string} config.valueKeys.id - Key for the item ID in the suggestion data.
 * @param {string} config.valueKeys.name - Key for the item name in the suggestion data.
 */

    function setupSearchableInput ({
        inputId,
        hiddenId,
        suggestionBoxId,
        searchUrl,
        valueKeys
    }) {
        const input = document.getElementById(inputId)
        const hiddenInput = document.getElementById(hiddenId)
        const suggestionBox = document.getElementById(suggestionBoxId)

        input.addEventListener('input', function () {
            const query = input.value.trim()
            if (query.length >= 2) {
                fetch(`${searchUrl}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            suggestionBox.innerHTML = data
                                .map(
                                    item => `
                    <div class="px-3 py-2 cursor-pointer hover:bg-gray-100"
                        data-id="${item[valueKeys.id]}"
                        data-name="${item[valueKeys.name]}">
                        ${item[valueKeys.name]} (${item[valueKeys.id]})
                    </div>
                `
                                )
                                .join('')
                            suggestionBox.classList.remove('hidden')
                            addSuggestionClickListeners()
                        } else {
                            suggestionBox.classList.add('hidden')
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching suggestions:', error)
                        suggestionBox.classList.add('hidden')
                    })
            } else {
                suggestionBox.classList.add('hidden')
            }
        })

/**
 * Attaches click event listeners to each suggestion item in the suggestion box.
 * When a suggestion is clicked, updates the input field with the selected item's
 * name and sets the hidden input field with the selected item's id. Hides the
 * suggestion box after a selection is made.
 */

        function addSuggestionClickListeners () {
            const items = suggestionBox.querySelectorAll('div')
            items.forEach(item => {
                item.addEventListener('click', function () {
                    input.value = item.getAttribute('data-name')
                    hiddenInput.value = item.getAttribute('data-id')
                    suggestionBox.classList.add('hidden')
                })
            })
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!suggestionBox.contains(e.target) && e.target !== input) {
                suggestionBox.classList.add('hidden')

                if (!hiddenInput.value) {
                    input.value = ''
                }
            }
        })
    }

    let selectedSupplierName = ''
    // search popup function
    document
        .getElementById('search-barcode-btn')
        .addEventListener('click', function (e) {
            e.preventDefault()
            e.stopPropagation() // Prevent event from bubbling up to document click handler

            const barcode = document.getElementById('nama_barang').value
            if (!barcode) {
                alert('Masukan Barcode terlebih dahulu!')
                return
            }

            fetch(
                `/daftar-produk/search-detail?barcode=${encodeURIComponent(
                    barcode
                )}`
            )
                .then(res => res.json())
                .then(data => {
                    if (!data) return

                    // Fill popup content
                    document.getElementById('popup-barcode').innerText =
                        data.barcode
                    document.getElementById('popup-name').innerText = data.nama
                    document.getElementById('popup-price').innerText =
                        data.harga || '-'
                    document.getElementById('popup-stock').innerText =
                        data.stok || 0
                    document.getElementById('popup-id-supplier').innerText =
                        data.idSupplier || 0
                    document.getElementById('popup-supplier-nama').innerText =
                        data.namaSupplier

                    if (data.barcode) {
                        document.getElementById('nama_barang').value = data.barcode
                        selectedBarcode = data.barcode
                        stokInput = data.stok
                        document.getElementById('kuantitas').value = stokInput
                    }

                    if (data.idSupplier) {
                        document.getElementById('nama_supplier').value = data.idSupplier;
                        selectedSupplierName = data.idSupplier
                    }

                    // Toggle popup visibility - no manual positioning needed
                    const popup = document.getElementById('barcode-popup')
                    popup.classList.toggle('hidden')


                })
                .catch(err => console.error('Error loading detail:', err))
        })

    function updateNoDataRow() {
        const tableBody = document.getElementById('returTableBody');
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
                tr.innerHTML = `<td colspan="8" class="text-center text-gray-500 p-2">Tidak ada data</td>`;
                tableBody.appendChild(tr);
            }
        } else {
            // Remove placeholder if present
            if (noDataRow) {
                noDataRow.remove();
            }
        }
    }

    // Close popup when clicking outside
    document.addEventListener('click', function (e) {
        const popup = document.getElementById('barcode-popup')
        const button = document.getElementById('search-barcode-btn')
        const namaSupplierInput = document.getElementById('nama_supplier')


        if (!popup.contains(e.target) && e.target !== button) {
            popup.classList.add('hidden')
            namaSupplierInput.value = selectedSupplierName || ''
        }
    })

    // Add Row Button Functionality
    const addRowBtn = document.getElementById('addRow');
    const returTableBody = document.getElementById('returTableBody');
    const hiddenRowsDiv = document.getElementById('hiddenRows');
    let rowCount = 0;

    addRowBtn.addEventListener('click', function() {
        // Get all input values
        const namaAkun = document.getElementById('nama_akun').value;
        const akunId = document.getElementById('akun_id').value;
        const namaBarang = document.getElementById('nama_barang').value;
        const barcode = namaBarang;
        const barangId = document.getElementById('barang_id').value;
        const kuantitas = document.getElementById('kuantitas').value;
        const supplierId = document.getElementById('nama_supplier').value;
        const kategoriKet = document.getElementById('kategoriKet');
        const kategoriKetText = kategoriKet.options[kategoriKet.selectedIndex].text;
        const kategoriKetValue = kategoriKet.value;
        const tanggalRetur = document.getElementById('tanggal_retur').value;
        const note = document.querySelector('textarea').value;

        // Validate required fields
        if (!namaBarang || !barangId || !kuantitas || !supplierId) {
            alert('Please fill in all required fields (Nama Barang, kuantitas, and Nama Supplier)');
            return;
        }

        if(kuantitas > stokInput) {
            alert('Stok melebihi batas stok pada saat ini.')
            return
        }

        // Create a new table row
        rowCount++;
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td class="px-4 py-2 border-b">${rowCount}</td>
            <td class="px-4 py-2 border-b">${namaAkun}</td>
            <td class="px-4 py-2 border-b">${barcode}</td>
            <td class="px-4 py-2 border-b">${kuantitas}</td>
            <td class="px-4 py-2 border-b">${supplierId}</td>
            <td class="px-4 py-2 border-b">${kategoriKetText}</td>
            <td class="px-4 py-2 border-b">${note || '-'}</td>
            <td class="px-4 py-2 border-b">
                <button type="button" class="text-red-600 hover:text-red-800 remove-row">Hapus</button>
            </td>
        `;

        // Add the row to the table
        returTableBody.appendChild(newRow);

        // Create hidden inputs for form submission
        const hiddenInputs = `
            <input type="hidden" name="retur[${rowCount}][id_akun]" value="${akunId}">
            <input type="hidden" name="retur[${rowCount}][id_barang]" value="${barangId}">
            <input type="hidden" name="retur[${rowCount}][barcode]" value="${barcode}">
            <input type="hidden" name="retur[${rowCount}][kuantitas]" value="${kuantitas}">
            <input type="hidden" name="retur[${rowCount}][id_supplier]" value="${supplierId}">
            <input type="hidden" name="retur[${rowCount}][kategori_ket]" value="${kategoriKetValue}">
            <input type="hidden" name="retur[${rowCount}][tanggal_retur]" value="${tanggalRetur}">
            <input type="hidden" name="retur[${rowCount}][note]" value="${note}">
        `;
        hiddenRowsDiv.insertAdjacentHTML('beforeend', hiddenInputs);

        // Add event listener to the remove button
        newRow.querySelector('.remove-row').addEventListener('click', function() {
            newRow.remove();
            
            updateRowNumbers();
            updateNoDataRow();
        });

        updateNoDataRow();

        // Clear the form fields (except for staff and date)
        document.getElementById('nama_barang').value = '';
        document.getElementById('barang_id').value = '';
        document.getElementById('nama_supplier').value = '';
        document.getElementById('supplier_id').value = '';
        document.querySelector('textarea').value = '';
    });

    // Function to update row numbers after deletion
    function updateRowNumbers() {
        const rows = returTableBody.querySelectorAll('tr');
        rowCount = 0;
        rows.forEach((row, index) => {
            row.cells[0].textContent = index + 1;
            rowCount++;
        });
    }

    // Submit button functionality
    const submitBtn = document.getElementById('submitData');
    submitBtn.addEventListener('click', function(e) {
        if (rowCount === 0) {
            e.preventDefault();
            alert('Please add at least one item to the table before submitting');
        }
    });
})
