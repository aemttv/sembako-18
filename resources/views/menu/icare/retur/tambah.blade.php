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
                <h1 class="text-xl font-semibold text-center ">Form Pengajuan Barang Retur</h1>
            </div>
        </div>

        <!-- Form Container -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="border rounded-lg bg-white shadow-sm">
                <div class="border-b px-6 py-3 font-medium text-gray-700">Informasi Detail Barang</div>
                <div class="p-6 space-y-4">
                    <!-- Row 1: Nama & Email -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="relative flex-grow">
                            <label class="block text-sm text-gray-600 mb-1">Penanggung Jawab</label>
                            <input id="nama_akun" type="text" class="w-full border rounded-md px-3 py-2 cursor-no-drop"
                                value="{{ session('user_data.nama', '') }}" placeholder="Search Akun..." autocomplete="off">
                            {{-- <div id="akun-suggestions"
                                class="w-full border rounded-md px-3 py-2 absolute z-10 bg-white mt-1 hidden max-h-60 overflow-auto">
                                <!-- Suggestions will appear here -->
                            </div>
                            <input type="hidden" id="akun_id" name="idAkun" /> --}}
                        </div>
                        <div class="relative flex-grow">
                            <label class="block text-sm text-gray-600 mb-1">Nama / Barcode Barang(*)</label>
                            <div class="flex w-full gap-2">
                                <div class="relative flex-grow">
                                    <input type="text" id="nama_barang" name="nama_barang"
                                class="w-full border rounded-md px-3 py-2"
                                placeholder="Cari nama barang atau barcode barang..." autocomplete="off">
                            <div id="barang-suggestions"
                                class="absolute z-10 w-full bg-white border mt-1 rounded-md hidden max-h-60 overflow-auto">
                                <!-- Suggestions will appear here -->
                            </div>
                            <!-- Hidden input to store supplier ID -->
                            <input type="hidden" id="barang_id" name="barang_id" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Barcode</label>
                            <input id="barcode_field" type="text" class="w-full border rounded-md px-3 py-2 cursor-no-drop" readonly>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Tanggal Kadaluarsa</label>
                            <input id="tglKadaluarsa_field" type="date" class="w-full border rounded-md px-3 py-2 cursor-no-drop" readonly>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Merek</label>
                            <input id="merek_field" type="text" class="w-full border rounded-md px-3 py-2 cursor-no-drop" readonly>
                        </div>
                    </div>

                    <!-- Row 2: Password -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="relative">
                            <label class="block text-sm text-gray-600 mb-1">Supplier ID</label>
                            <input id="supplier_field" type="text" class="w-full border rounded-md px-3 py-2 cursor-no-drop" readonly>
                        </div>
                        <div class="relative">
                            <label class="block text-sm text-gray-600 mb-1">Jumlah Pengeluaran(*)</label>
                            <input type="number" id="kuantitas" class="w-full border rounded-md px-3 py-2" min="1"
                                value="1" max="100"
                            oninput="
                            if(this.value.length > 3) this.value = this.value.slice(0,3);
                            if(this.value == 0) this.value = 1;
                        "/>
                        </div>
                        <div class="relative">
                            <label class="block text-sm text-gray-600 mb-1">Satuan</label>
                            <select id="satuan" name="satuan" disabled
                                class="w-full border rounded-md px-3 py-2">
                                @foreach ($satuan as $sat)
                                    <option value="{{ $sat->value }}"
                                        {{ old('satuan', $barang->satuan?->value ?? null) == $sat->value ? 'selected' : '' }}>
                                        {{ $sat->namaSatuan() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Alasan -->
            <div class="border rounded-lg bg-white shadow-sm flex flex-col justify-between">
                <div>
                    <div class="border-b px-6 py-3 font-medium text-gray-700">Informasi Keterangan</div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="relative">
                                <label class="block text-sm text-gray-600 mb-1">Kategori Keterangan</label>
                                <select name="kategoriKet" id="kategoriKet"
                                    class="w-full border border-gray-300 rounded p-2">
                                    <option value="3">Barang Cacat</option>
                                    <option value="4" selected>Barang Tidak Sesuai</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Tanggal Retur</label>
                                <input type="date" id="tanggal_retur" class="w-full border rounded-md px-3 py-2"
                                    value="{{ now()->format('Y-m-d') }}" min="{{ now()->subMonth()->format('Y-m-d') }}"
                    max="{{ now()->addYear()->format('Y-m-d') }}"/>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Note</label>
                            <textarea class="w-full border border-gray-300 rounded p-2 min-h-[100px] max-h-[150px]"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 border rounded-lg shadow-sm bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 text-center cursor-pointer"
            id="addRow">
            (+) Tambah Data Barang Ke Tabel
        </div>


        <!-- Form action to store data -->
        <form action="{{ route('AjukanBRetur.submit') }}" method="POST" enctype="multipart/form-data" id="returTable">
            @csrf
            <!-- Hidden fields to store row data -->
            <div id="hiddenRows"></div>

            <div class="mt-6 border rounded-lg bg-white shadow-sm">
                <div class="border-b px-6 py-3 font-medium text-gray-700">Daftar Simulasi Pengajuan Barang Retur</div>
                <div class="p-6">
                    <table id="barangTable" class="min-w-full border border-gray-300 text-sm">
                        <thead class="bg-gray-100 uppercase text-md">
                            <tr>
                                <th class="px-4 py-2 border-b">No.</th>
                                <th class="px-4 py-2 border-b">Penaggung Jawab</th>
                                <th class="px-4 py-2 border-b">Barcode</th>
                                <th class="px-4 py-2 border-b">Kuantitas</th>
                                <th class="px-4 py-2 border-b">Supplier ID</th>
                                <th class="px-4 py-2 border-b">Kategori Keterangan</th>
                                <th class="px-4 py-2 border-b">Note</th>
                                <th class="px-4 py-2 border-b">Proses</th>
                            </tr>
                        </thead>
                        <tbody id="returTableBody" class="text-center">
                            <tr id="noDataRow">
                                <td colspan="8" class="text-center text-gray-500 p-2">Tidak ada data</td>
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

        <script src="{{ asset('javascript/retur.js') }}"></script>

    </div>
@endsection
