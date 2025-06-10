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
                <h1 class="text-xl font-semibold text-center ">Form Pemasukan Staff Baru</h1>
            </div>
        </div>

        <!-- Form Container -->
        <div class="max-w-2xl mx-auto">
            <div class="border rounded-lg bg-white shadow-sm">
                <div class="border-b px-6 py-3 font-medium text-gray-700">Informasi Detail Staff</div>
                <div class="p-6 space-y-4">
                    <!-- Row 1: Nama & Email -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" class="w-full border rounded-md px-3 py-2"
                                placeholder="Nama Lengkap" autocomplete="off" maxlength="50"/>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Email</label>
                            <input type="email" id="email" name="email" class="w-full border rounded-md px-3 py-2" maxlength="150"
                                placeholder="Email" />
                        </div>
                    </div>

                    <!-- Row 2: Password -->
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Password (Default: sembako18)</label>
                        <div x-data="{ showPassword: false }" class="relative">
                            <input :type="showPassword ? 'text' : 'password'" name="password" placeholder="Enter password" id="password"
                                class="h-11 w-full rounded-md border text-gray-600 py-2 pl-4 pr-11 border-black" />
                            <span @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-3 flex items-center cursor-pointer">
                                <svg x-show="!showPassword" class="fill-current" width="20" height="20"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M10 13.86C7.23 13.86 4.87 12.14 3.92 9.7 4.87 7.27 7.23 5.54 10 5.54s5.13 1.73 6.08 4.16c-.95 2.44-3.31 4.16-6.08 4.16zM10 4.04C6.48 4.04 3.49 6.31 2.42 9.46a1 1 0 0 0 0 .48C3.5 13.1 6.48 15.36 10 15.36c3.52 0 6.51-2.26 7.58-5.38a1 1 0 0 0 0-.48C16.51 6.31 13.52 4.04 10 4.04zM10 7.84a1.86 1.86 0 1 0 0 3.72 1.86 1.86 0 0 0 0-3.72z"
                                        fill="#98A2B3" />
                                </svg>
                                <svg x-show="showPassword" class="fill-current" width="20" height="20"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M4.64 3.58a1 1 0 0 0-1.06 1.06l1.28 1.28C3.75 6.84 2.89 8.06 2.42 9.46a1 1 0 0 0 0 .48c1.08 3.15 4.06 5.38 7.58 5.38 1.26 0 2.46-.28 3.5-.8l1.86 1.86a1 1 0 0 0 1.41-1.42L4.64 3.58zM10 5.54c2.77 0 5.13 1.73 6.08 4.16a7.68 7.68 0 0 1-1.26 2.02L10 7.84a1.86 1.86 0 0 0-2.15 2.15L5.92 6.98A7.76 7.76 0 0 1 10 5.54z"
                                        fill="#98A2B3" />
                                </svg>
                            </span>
                        </div>
                    </div>

                    <!-- Row 3: No HP & Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">No.HP/No.WA</label>
                            <input type="text" id="no_hp"name="no_hp" class="w-full border rounded-md px-3 py-2" maxlength="15"
                                placeholder="08xxxxxxxx" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Status Peran</label>
                            <select id="statusPeran" class="w-full border rounded-md px-3 py-2">
                                <option selected value="1">Admin</option>
                                <option value="2">Staff</option>
                            </select>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="pt-4 flex justify-end gap-4">
                        <button id="addRow"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Masukkan
                            Informasi Akun</button>
                        <button type="button"
                            class="px-4 py-2 border border-gray-400 text-gray-700 rounded-md hover:bg-gray-100 transition"
                            id="clearFields">Kosongkan Field</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Form action to store data -->
        <form action="{{ route('akun.submit') }}" method="POST" enctype="multipart/form-data" id="barangMasukForm">
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
                                <th class="px-4 py-2 border-b">Email</th>
                                <th class="px-4 py-2 border-b">Password</th>
                                <th class="px-4 py-2 border-b">No.Hp/Wa</th>
                                <th class="px-4 py-2 border-b">Status</th>
                                <th class="px-4 py-2 border-b">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="akunTableBody">
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
                var email = document.getElementById('email').value;
                var password = document.getElementById('password').value;
                var noHp = document.getElementById('no_hp').value;
                var statusPeran = document.getElementById('statusPeran').value;

                // Add new row to the table
                var tableBody = document.getElementById('akunTableBody');
                var newRow = tableBody.insertRow();

                if(password == '') {
                    password = 'sembako18';
                }

                newRow.innerHTML = `
                    <td class="px-4 py-2 border-b text-center">${tableBody.rows.length}</td>
                    <td class="px-4 py-2 border-b text-center">${namaLengkap}</td>
                    <td class="px-4 py-2 border-b text-center">${email}</td>
                    <td class="px-4 py-2 border-b text-center">
                        <span
                            class="bg-gray-300 text-white px-2 py-1 rounded cursor-pointer"
                            data-password="${password}"
                            onclick="
                            if (this.innerText === '•••••••') {
                                this.innerText = this.dataset.password;
                                this.classList.remove('bg-gray-300', 'text-white');
                                this.classList.add('bg-transparent', 'text-black');
                            } else {
                                this.innerText = '•••••••';
                                this.classList.add('bg-gray-300', 'text-white');
                                this.classList.remove('bg-transparent', 'text-black');
                            }
                            ">
                            •••••••
                        </span>
                    </td>

                    <td class="px-4 py-2 border-b text-center">${noHp}</td>
                    <td class="px-4 py-2 border-b text-center">${statusPeran}</td>
                    <td class="px-4 py-2 border-b text-center">
                        <button class="text-red-500 hover:text-red-700">Hapus</button>
                    </td>
                `;

                // Menambahkan input tersembunyi untuk setiap row ke dalam form
                var hiddenRows = document.getElementById('hiddenRows');
                var hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = `staff_input[]`;
                hiddenInput.value = JSON.stringify({
                    nama: namaLengkap,
                    email: email,
                    password: password,
                    no_hp: noHp,
                    status_peran: statusPeran
                });
                hiddenRows.appendChild(hiddenInput);

                // Clear form fields
                document.getElementById('clearFields').addEventListener('click', function () {
                    document.getElementById('nama').value = '';
                    document.getElementById('email').value = '';
                    document.getElementById('password').value = '';
                    document.getElementById('no_hp').value = '';
                    document.getElementById('statusPeran').value = '1';
                });

            });

            // Kosongkan semua field
            document.getElementById('clearFields').addEventListener('click', function () {
                document.getElementById('nama').value = '';
                document.getElementById('email').value = '';
                document.getElementById('password').value = '';
                document.getElementById('no_hp').value = '';
                document.getElementById('statusPeran').value = '1';
            });


            // Pastikan form bisa submit ke backend
            document.getElementById('submitData').addEventListener('click', function() {
                // Di sini bisa tambahkan validasi jika diperlukan sebelum submit
            });

            // Formatting and live validation for No HP
            const inputNoHp = document.getElementById('no_hp')

            // Create or get the error message element
            let noHpError = document.getElementById('no_hp_error')
            if (!noHpError && inputNoHp) {
                noHpError = document.createElement('div')
                noHpError.id = 'no_hp_error'
                noHpError.className = 'text-red-500 text-xs mt-1'
                noHpError.style.display = 'none'
                inputNoHp.parentNode.appendChild(noHpError)
            }

            if (inputNoHp) {
                inputNoHp.addEventListener('input', function () {
                    const value = this.value.trim()
                    if (!(value.startsWith('08') || value.startsWith('628'))) {
                        this.classList.add('border-red-500')
                        noHpError.textContent = 'No HP harus dimulai dengan 08 atau 628'
                        noHpError.style.display = ''
                    } else {
                        this.classList.remove('border-red-500')
                        noHpError.textContent = ''
                        noHpError.style.display = 'none'
                    }
                })
            }

        </script>

    </div>
@endsection
