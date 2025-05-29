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
                <h1 class="text-xl font-semibold text-center ">Form Pemasukan Supplier Baru</h1>
            </div>
        </div>

        <!-- Form Container -->
        <div class="max-w-2xl mx-auto">
            <div class="border rounded-lg bg-white shadow-sm">
                <div class="border-b px-6 py-3 font-medium text-gray-700">Informasi Detail Supplier</div>
                <div class="p-6 space-y-4">
                    <!-- Row 1: Nama & Email -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" class="w-full border rounded-md px-3 py-2"
                                placeholder="Nama Lengkap" autocomplete="off" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">No.HP/No.WA</label>
                            <input type="text" id="no_hp"name="no_hp" class="w-full border rounded-md px-3 py-2" maxlength="15"
                                placeholder="08xxxxxxxx/6281xxxxx" />
                        </div>
                    </div>

                    <!-- Row 3: No Alamat & Status -->
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Status Keaktifan</label>
                        <select id="status" class="w-full border rounded-md px-3 py-2">
                            <option selected value="1">Aktif</option>
                            <option value="2">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Alamat</label>
                            <textarea type="text" id="alamat"name="alamat" class="w-full border rounded-md px-3 py-2 min-h-[100px] max-h-[200px]"
                                placeholder="Jl..." > </textarea>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="pt-4 flex justify-end gap-4">
                        <button id="addRow" type="button"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Masukkan
                            Informasi Supplier</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Form action to store data -->
        <form action="{{ route('supplier.submit') }}" method="POST" enctype="multipart/form-data" id="barangMasukForm">
            @csrf
            <!-- Hidden fields to store row data -->
            <div id="hiddenRows"></div>

            <div class="mt-6 border rounded-lg bg-white shadow-sm">
                <div class="border-b px-6 py-3 font-medium text-gray-700">Daftar Simulasi Barang Masuk</div>
                <div class="p-6">
                    <table id="barangTable" class="min-w-full table-auto">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 border-b">No.</th>
                                <th class="px-4 py-2 border-b">Nama Lengkap</th>
                                <th class="px-4 py-2 border-b">No.Hp/Wa</th>
                                <th class="px-4 py-2 border-b">Alamat</th>
                                <th class="px-4 py-2 border-b">Status</th>
                                <th class="px-4 py-2 border-b">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="supplierTableBody">
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
            // Menambahkan baris baru ke dalam tabel
            document.getElementById('addRow').addEventListener('click', function() {
                var namaLengkap = document.getElementById('nama').value;
                var noHp = document.getElementById('no_hp').value;
                var alamat = document.getElementById('alamat').value;
                var status = document.getElementById('status').value;

                // Add new row to the table
                var tableBody = document.getElementById('supplierTableBody');
                var newRow = tableBody.insertRow();

                if(!namaLengkap || !noHp || !alamat || !status) {
                    alert('Please fill in all required fields');
                    return;
                }

                newRow.innerHTML = `
                    <td class="px-4 py-2 border-b text-center">${tableBody.rows.length}</td>
                    <td class="px-4 py-2 border-b text-center">${namaLengkap}</td>
                    <td class="px-4 py-2 border-b text-center">${noHp}</td>
                    <td class="px-4 py-2 border-b text-center">${alamat}</td>
                    <td class="px-4 py-2 border-b text-center">${status == 1 ? 'Aktif' : 'Tidak Aktif'} </td>
                    <td class="px-4 py-2 border-b text-center">
                        <button class="text-red-500 hover:text-red-700">Hapus</button>
                    </td>
                `;

                // Menambahkan input tersembunyi untuk setiap row ke dalam form
                var hiddenRows = document.getElementById('hiddenRows');
                var hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = `supplier_input[]`;
                hiddenInput.value = JSON.stringify({
                    nama: namaLengkap,
                    no_hp: noHp,
                    alamat: alamat,
                    status: status
                });
                hiddenRows.appendChild(hiddenInput);

                // Clear form fields
                document.getElementById('clearFields').addEventListener('click', function () {
                    document.getElementById('nama').value = '';
                    document.getElementById('no_hp').value = '';
                    document.getElementById('alamat').value = '';
                    document.getElementById('status').value = '';
                });

            });

            // Kosongkan semua field
            document.getElementById('clearFields').addEventListener('click', function () {
                document.getElementById('nama').value = '';
                    document.getElementById('no_hp').value = '';
                    document.getElementById('alamat').value = '';
                    document.getElementById('status').value = '1';
            });


            // Pastikan form bisa submit ke backend
            document.getElementById('submitData').addEventListener('click', function() {
                
            });

            
            // Formatting and live validation for No HP
            const inputNoHp = document.getElementById('no_hp');

            // Create or get the error message element
            let noHpError = document.getElementById('no_hp_error');
            if (!noHpError && inputNoHp) {
                noHpError = document.createElement('div');
                noHpError.id = 'no_hp_error';
                noHpError.className = 'text-red-500 text-xs mt-1';
                noHpError.style.display = 'none';
                inputNoHp.parentNode.appendChild(noHpError);
            }

            if (inputNoHp) {
                inputNoHp.addEventListener('input', function() {
                    const value = this.value.trim();
                    if (!(value.startsWith('08') || value.startsWith('628'))) {
                        this.classList.add('border-red-500');
                        noHpError.textContent = 'No HP harus dimulai dengan 08 atau 628';
                        noHpError.style.display = '';
                    } else {
                        this.classList.remove('border-red-500');
                        noHpError.textContent = '';
                        noHpError.style.display = 'none';
                    }
                });
            }
        </script>

    </div>
@endsection
