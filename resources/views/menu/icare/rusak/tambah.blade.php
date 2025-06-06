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
                <h1 class="text-xl font-semibold text-center ">Form Pengajuan Barang Rusak</h1>
            </div>
        </div>

        <!-- Form Container -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="border rounded-lg bg-white shadow-sm">
                <div class="border-b px-6 py-3 font-medium text-gray-700">Informasi Detail Barang</div>
                <div class="p-6 space-y-4">
                    <!-- Row 1: Barcode Search & Penanggung Jawab -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Barcode Search -->
                        <div class="relative flex-grow">
                            <label class="block text-sm text-gray-600 mb-1">Barcode Barang</label>
                            <div class="flex w-full gap-2">
                                <div class="relative flex-grow">
                                    <input id="nama_barang" type="text" class="w-full border rounded-md px-3 py-2">
                                    <div id="barang-suggestions"
                                        class="w-full border rounded-md px-3 py-2 absolute z-10 bg-white mt-1 hidden max-h-60 overflow-auto">
                                        <!-- Suggestions will appear here -->
                                    </div>
                                    <input type="hidden" id="barang_id" name="idBarang" />
                                </div>
                                <div class="relative">
                                    <button id="search-barcode-btn" class="bg-blue-500 text-white px-3 py-2 rounded">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <div id="barcode-popup"
                                        class="absolute bg-white border shadow rounded-md p-4 text-sm hidden z-50 left-0 mt-1 w-64">
                                        <strong>Barcode Details</strong>
                                        <div>Barcode: <span id="popup-barcode"></span></div>
                                        <div>Name: <span id="popup-name"></span></div>
                                        <div>Price: <span id="popup-price"></span></div>
                                        <div>Stock: <span id="popup-stock"></span></div>
                                        <div>Satuan: <span id="popup-satuan"></span></div>
                                        <div>Kadaluarsa: <span id="popup-kadaluarsa"></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Penanggung Jawab -->
                        <div class="relative flex-grow">
                            <label class="block text-sm text-gray-600 mb-1">Penanggung Jawab</label>
                            <input id="nama_akun" type="text" class="w-full border rounded-md px-3 py-2"
                                value="{{ old('nama_akun') }}" placeholder="Search Akun..." autocomplete="off">
                            <div id="akun-suggestions"
                                class="w-full border rounded-md px-3 py-2 absolute z-10 bg-white mt-1 hidden max-h-60 overflow-auto">
                                <!-- Suggestions will appear here -->
                            </div>
                            <input type="hidden" id="akun_id" name="idAkun" />
                        </div>
                    </div>

                    <!-- Row 2: Jumlah Pengeluaran & Satuan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Jumlah Pengeluaran -->
                        <div class="relative flex-grow">
                            <label class="block text-sm text-gray-600 mb-1">Jumlah Pengeluaran</label>
                            <input type="number" id="kuantitas" class="w-full border rounded-md px-3 py-2" min="1"
                                value="1" max="100"
                                oninput="
                        if(this.value.length > 3) this.value = this.value.slice(0,3);
                        if(this.value == 0) this.value = 1;
                    " />
                        </div>
                        <!-- Satuan Select -->
                        <div class="relative flex-grow">
                            <label class="block text-sm text-gray-600 mb-1">Satuan</label>
                            <select id="satuan" name="satuan" disabled class="w-full border rounded-md px-3 py-2">
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
                                    <option value="5" selected>Kadaluarsa</option>
                                    <option value="6">Tidak Layak Dipakai</option>
                                </select>
                            </div>
                            <div class="relative">
                                <label class="block text-sm text-gray-600 mb-1">Tanggal Rusak</label>
                                <input type="date" id="tanggal_rusak" class="w-full border rounded-md px-3 py-2"
                                    value="{{ now()->format('Y-m-d') }}" min="{{ now()->subMonth()->format('Y-m-d') }}"
                                    max="{{ now()->addYear()->format('Y-m-d') }}" />
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
        <form action="{{ route('AjukanBRusak.submit') }}" method="POST" enctype="multipart/form-data" id="rusakTable">
            @csrf
            <!-- Hidden fields to store row data -->
            <div id="hiddenRows"></div>

            <div class="mt-6 border rounded-lg bg-white shadow-sm">
                <div class="border-b px-6 py-3 font-medium text-gray-700">Daftar Simulasi Retur</div>
                <div class="p-6">
                    <table id="barangTable" class="min-w-full border border-gray-300 text-sm">
                        <thead class="bg-gray-100 uppercase text-md">
                            <tr>
                                <th class="px-4 py-2 border-b">No.</th>
                                <th class="px-4 py-2 border-b">Penaggung Jawab</th>
                                <th class="px-4 py-2 border-b">Barcode</th>
                                <th class="px-4 py-2 border-b">Kuantitas</th>
                                <th class="px-4 py-2 border-b">Kategori Keterangan</th>
                                <th class="px-4 py-2 border-b">Tanggal Rusak</th>
                                <th class="px-4 py-2 border-b">Note</th>
                                <th class="px-4 py-2 border-b">Proses</th>
                            </tr>
                        </thead>
                        <tbody id="rusakTableBody" class="text-center">
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

        <script src="{{ asset('javascript/rusak.js') }}"></script>

    </div>
@endsection
