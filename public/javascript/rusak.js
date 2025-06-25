document.addEventListener('DOMContentLoaded', function () {
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
    let stokInput = 1;
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
                            suggestionBox.innerHTML = data.map(item => `
                                <div class="px-3 py-2 cursor-pointer hover:bg-gray-100"
                                    data-id="${item[valueKeys.id]}"
                                    data-name="${item[valueKeys.name]}"
                                    data-barcode="${item[valueKeys.barcode]}"
                                    data-satuan="${item[valueKeys.satuan]}"
                                    data-merek="${item[valueKeys.merek]}"
                                    data-tgl="${item[valueKeys.tglKadaluarsa]}"
                                    data-stok="${item['stok']}">
                                    <div class="font-semibold">${item[valueKeys.name]}</div>
                                    <div class="text-sm text-gray-600">Barcode: ${item[valueKeys.barcode]}<br>
                                    Exp: ${item[valueKeys.tglKadaluarsa]}<br>
                                    Stok: ${item['stok']}
                                    </div>
                                </div>
                            `).join('');
                            suggestionBox.classList.remove('hidden')
                            addSuggestionClickListeners()
                        } else {
                            suggestionBox.classList.add('hidden')
                            document.getElementById('kuantitas').value = 1
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

                    const namaBarangField = document.getElementById('nama_barang');
                    const barcodeField = document.getElementById('barcode_field');
                    const tglKadaluarsaField = document.getElementById('tglKadaluarsa_field');
                    const merekField = document.getElementById('merek_field');
                    const satuanField = document.getElementById('satuan');
                    const kuantitasField = document.getElementById('kuantitas');

                    if(namaBarangField){
                        barcodeField.value = item.getAttribute('data-barcode');
                        tglKadaluarsaField.value = item.getAttribute('data-tgl');
                        merekField.value = item.getAttribute('data-merek');

                        cacheKadaluarsa = item.getAttribute('data-tgl');
                        satuanField.value = item.getAttribute('data-satuan') || 'Pcs';

                        if(satuanField.value == 2){
                            kuantitasField.value = item.getAttribute('data-stok');
                            stokInput = kuantitasField.value;
                            kuantitasField.readOnly = true;
                        } else {
                            kuantitasField.value = item.getAttribute('data-stok');
                            stokInput = kuantitasField.value;
                            kuantitasField.readOnly = false;
                        }
                    }
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

    let cacheKadaluarsa = null;

    function updateNoDataRow() {
        const tableBody = document.getElementById('rusakTableBody');
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


    const addRowBtn = document.getElementById('addRow');
    const rusakTableBody = document.getElementById('rusakTableBody');
    const hiddenRowsDiv = document.getElementById('hiddenRows');
    let rowCount = 0;

    addRowBtn.addEventListener('click', async function () {
        // Get all input values
        const namaAkun = document.getElementById('nama_akun').value;
        // const akunId = document.getElementById('akun_id').value;
        const namaBarang = document.getElementById('nama_barang').value;
        const barcode = document.getElementById('barcode_field').value;
        const barangId = document.getElementById('barang_id').value;
        const kuantitas = document.getElementById('kuantitas').value;
        const kategoriKet = document.getElementById('kategoriKet');
        const kategoriKetText = kategoriKet.options[kategoriKet.selectedIndex].text;
        const kategoriKetValue = kategoriKet.value;
        const tanggalRusak = document.getElementById('tanggal_rusak').value;
        const note = document.querySelector('textarea').value;

        // Validate required fields
        if (!namaAkun || !namaBarang || !barangId || !kuantitas ) {
            alert('Silakan mengisi field yang dibutuhkan (Penanggung Jawab, Nama Barang, kuantitas)');
            return;
        }

        if(kuantitas > stokInput) {
            alert('Stok melebihi batas stok pada saat ini.')
            document.getElementById('nama_barang').value = ''
            document.getElementById('supplier_field').value = ''
            document.getElementById('barcode_field').value = ''
            document.getElementById('tglKadaluarsa_field').value = ''
            document.getElementById('merek_field').value = ''
            document.getElementById('kuantitas').value = 1
            return
        }

        if (kategoriKetValue === "5") { // 5 = kadaluarsa
            if (!cacheKadaluarsa) {
                alert('Data kadaluarsa untuk barcode ini tidak ditemukan!');
                return;
            }
            // Compare only the date part as string (YYYY-MM-DD)
            const today = new Date();
            const todayStr = today.getFullYear() + '-' +
                String(today.getMonth() + 1).padStart(2, '0') + '-' +
                String(today.getDate()).padStart(2, '0');
            const kadaluarsaStrOnly = cacheKadaluarsa.slice(0, 10);

            console.log(todayStr, kadaluarsaStrOnly);

            if (todayStr < kadaluarsaStrOnly) {
                console.log(cacheKadaluarsa);
                alert('Barang ini belum kadaluarsa, tidak dapat diproses sebagai kadaluarsa.');
                document.getElementById('nama_barang').value = ''
                document.getElementById('barcode_field').value = ''
                document.getElementById('tglKadaluarsa_field').value = ''
                document.getElementById('merek_field').value = ''
                document.getElementById('kuantitas').value = 1
                return;
            }
        }

        let totalKuantitasForBarcode = 0;
        Array.from(rusakTableBody.querySelectorAll('tr')).forEach(row => {
            const rowBarcode = row.cells[2]?.textContent.trim();
            const rowKuantitas = parseInt(row.cells[3]?.textContent.trim(), 10) || 0;
            if (rowBarcode === barcode) {
                totalKuantitasForBarcode += rowKuantitas;
            }
        });
        const newTotal = totalKuantitasForBarcode + parseInt(kuantitas, 10);
        if (newTotal > stokInput) {
            alert(`Total kuantitas untuk barcode ini (${newTotal}) melebihi stok (${stokInput})!`);
            document.getElementById('nama_barang').value = ''
            document.getElementById('barcode_field').value = ''
            document.getElementById('tglKadaluarsa_field').value = ''
            document.getElementById('merek_field').value = ''
            document.getElementById('kuantitas').value = 1
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

        // Create a new table row
        rowCount++;
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td class="px-4 py-2 border-b">${rowCount}</td>
            <td class="px-4 py-2 border-b">${namaAkun}</td>
            <td class="px-4 py-2 border-b">${barcode}</td>
            <td class="px-4 py-2 border-b">${kuantitas}</td>
            <td class="px-4 py-2 border-b">${kategoriKetText}</td>
            <td class="px-4 py-2 border-b">${formatTanggalMasuk(tanggalRusak)}</td>
            <td class="px-4 py-2 border-b">${note || '-'}</td>
            <td class="px-4 py-2 border-b">
                <button type"button" class="text-red-600 hover:text-red-800 remove-row">Hapus</button>
            </td>
        `;

        rusakTableBody.appendChild(newRow);

        // Remove the "No data" row if it exists
        const noDataRow = document.getElementById('noDataRow');
        if (noDataRow) {
            noDataRow.remove();
        }

        // Create hidden inputs for form submission
        const hiddenInputs = `
            <input type="hidden" name="rusak[${rowCount}][id_akun]" value="${namaAkun}">
            <input type="hidden" name="rusak[${rowCount}][id_barang]" value="${barangId}">
            <input type="hidden" name="rusak[${rowCount}][barcode]" value="${barcode}">
            <input type="hidden" name="rusak[${rowCount}][kuantitas]" value="${kuantitas}">
            <input type="hidden" name="rusak[${rowCount}][kategori_ket]" value="${kategoriKetValue}">
            <input type="hidden" name="rusak[${rowCount}][tanggal_rusak]" value="${tanggalRusak}">
            <input type="hidden" name="rusak[${rowCount}][note]" value="${note}">
        `;
        hiddenRowsDiv.insertAdjacentHTML('beforeend', hiddenInputs);

        // Add event listener to the remove button
        newRow.querySelector('.remove-row').addEventListener('click', function () {
            newRow.remove();

            updateRowNumbers();
            updateNoDataRow();
        });

        updateNoDataRow();

        // Clear the form fields
        document.getElementById('nama_barang').value = '';
        document.getElementById('barang_id').value = '';
        document.getElementById('kuantitas').value = '';
        document.querySelector('textarea').value = '';
    });

    function updateRowNumbers() {
        const rows = rusakTableBody.querySelectorAll('tr');
        rowCount = 0;
        rows.forEach((row, index) => {
            row.cells[0].textContent = index + 1;
            rowCount++;
        });
    }
    const submitBtn = document.getElementById('submitData');
    submitBtn.addEventListener('click', function(e) {
        if (rowCount === 0) {
            e.preventDefault();
            alert('Please add at least one item to the table before submitting');
        }
    })


});
