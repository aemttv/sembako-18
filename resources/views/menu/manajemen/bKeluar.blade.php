@extends('layout')

@section('content')
    <div class="p-6 space-y-6">
        @if (session('success'))
            <x-ui.alert type="success" :message="session('success')" />
        @elseif (session('error'))
            <x-ui.alert type="error" :message="session('error')" />
        @endif
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold">Barang Keluar</span></h1>
                {{-- <p class="text-sm text-gray-600">Transaction > Sales</p> --}}
            </div>

        </div>

        <!-- Form Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Date, Cashier, Customer -->
            <div class="space-y-2 bg-white rounded-md shadow p-4">
                <label class="block text-sm font-medium">Tanggal Pencatatan</label>
                <input type="date" id="tanggal_keluar_external"
                    class="w-full border border-gray-300 rounded p-2 cursor-pointer" value="{{ now()->format('Y-m-d') }}"
                    min="{{ now()->subMonth()->format('Y-m-d') }}" max="{{ now()->addYear()->format('Y-m-d') }}">

                <div class="relative flex-grow">
                    <label class="block text-sm font-medium mb-2">Staff</label>
                    <input id="nama_akun" type="text"
                        class="w-full border border-gray-300 rounded p-2 pointer-events-none"
                        value="{{ session('user_data.nama', '') }}" readonly>
                    {{-- <div id="staff-suggestions"
                        class="w-full border rounded-md px-3 py-2 absolute z-10 bg-white mt-1 hidden max-h-60 overflow-auto">
                        <!-- Suggestions will appear here -->
                    </div> --}}
                    {{-- <input type="hidden" id="akun_id" name="idAkun" /> --}}
                </div>
            </div>

            <!-- Barcode & Qty Input -->
            <div class="bg-white rounded-md shadow p-4 flex flex-col justify-between h-full min-h-[220px]">
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Nama Barang / Barcode (*) <span id="hargaBarang"></span></label>
                    <div class="flex w-full gap-2">
                        <div class="relative flex-grow">
                            {{-- <input id="nama_barang" type="text" class="w-full border border-gray-300 rounded p-2">
                            <div id="barang-suggestions"
                                class="w-full border rounded-md px-3 py-2 absolute z-10 bg-white mt-1 hidden max-h-60 overflow-auto">
                                <!-- Suggestions will appear here -->
                            </div>
                            <input type="hidden" id="barang_id" name="idBarang" /> --}}
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
                        <button id="search-barcode-btn" class="bg-blue-500 text-white px-3 py-2 rounded">
                            <i class="fas fa-info"></i>
                        </button>

                        <!-- Popup container -->
                        <div id="barcode-popup" class="absolute bg-white border shadow rounded-md p-4 text-sm hidden z-50"
                            style="min-width: 200px;">
                            <strong>Barcode Details</strong>
                            <div>Barcode: <span id="popup-barcode"></span></div>
                            <div>Name: <span id="popup-name"></span></div>
                            <div>Price: Rp.<span id="popup-price"></span></div>
                            <div>Kuantitas/Berat: <span id="popup-stock"></span> <span id="popup-satuan"></span></div>
                        </div>

                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Barcode</label>
                            <input id="barcode_field" type="text" class="w-full border border-gray-300 rounded p-2 pointer-events-none cursor-no-drop" >
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Tanggal Kadaluarsa</label>
                            <input id="tglKadaluarsa_field" type="date" class="w-full border border-gray-300 rounded p-2 pointer-events-none cursor-no-drop" >
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Merek</label>
                            <input id="merek_field" type="text" class="w-full border border-gray-300 rounded p-2 pointer-events-none cursor-no-drop">
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Kuantitas/Berat(gr) (*)</label>
                            <input id="qty" type="number" class="w-full border border-gray-300 rounded p-2"
                                min="0" max="10000"
                                oninput="
                                        if(this.value.length > 6) this.value = this.value.slice(0,5);
                                        if(this.value == 0) this.value = 1;
                                    ">
                        </div>
                    </div>
                </div>

                {{-- <div class="flex justify-end mt-4">
                    <button id="add-barcode-btn" class="bg-blue-600 text-white px-4 py-2 rounded">+ Tambah</button>
                </div> --}}
            </div>

            <!-- Invoice Total -->
            <div class="bg-white rounded-md shadow p-4 flex flex-col justify-between">
                <div class="flex justify-center">
                    <span class="text-lg font-semibold text-center">Grand Total</span>
                </div>
                <div class="flex justify-center items-center flex-1">
                    <span id="invoice-total" class="text-6xl font-bold text-gray-700">Rp.0</span>
                </div>
            </div>

        </div>

        <div class="mt-6 border rounded-lg shadow-sm bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 text-center cursor-pointer"
                id="add-barcode-btn">
                (+) Tambah Data Barang Ke Tabel
            </div>


        <form id="form-bkeluar" action="{{ route('barang-keluar.submit') }}" method="POST">
            @csrf
            <input type="hidden" id="tanggal_keluar" name="tanggal_keluar">
            <!-- Table Section -->
            <div class="overflow-x-auto bg-white rounded-md shadow p-4 my-4">
                <table class="min-w-full border border-gray-300 text-sm">
                    <thead class="bg-gray-100 uppercase text-md">
                        <tr>
                            <th class="p-2 border">#</th>
                            <th class="p-2 border">Barcode</th>
                            <th class="p-2 border">Nama Barang</th>
                            <th class="p-2 border">Harga</th>
                            <th class="p-2 border">Kuantitas/Berat(Kg)</th>
                            <th class="p-2 border">Subtotal</th>
                            <th class="p-2 border">Proses</th>
                        </tr>
                    </thead>
                    <tbody id="transaction-table-body">
                        <tr id="noDataRow">
                            <td colspan="7" class="text-center text-gray-500 p-2">Tidak ada data barang keluar saat ini..
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Payment Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 ">

                <div class="space-y-2 bg-white rounded-md shadow p-4">
                    <label class="block text-sm font-medium">Grand Total</label>
                    <input id="invoice-total-input" value="" type="text"
                        class="w-full border border-gray-300 rounded p-2 pointer-events-none" readonly>

                    <label class="block text-sm font-medium">Uang</label>
                    <input id="cash-input" type="text" value="0"
                        class="w-full border border-gray-300 rounded p-2">

                    <label class="block text-sm font-medium">Uang Kembali</label>
                    <input id="change-output" type="text"
                        class="w-full border border-gray-300 rounded p-2 pointer-events-none" readonly>

                </div>

                <div class="space-y-2 bg-white rounded-md shadow p-4">
                    <label for="kategoriKet">Keterangan Pengeluaran</label>
                    <select name="kategoriKet" id="kategoriKet" class="w-full border border-gray-300 rounded p-2">
                        <option value="1">Jual</option>
                        <option value="2">Pribadi</option>
                    </select>
                    <label class="block text-sm font-medium">Note</label>
                    <textarea class="w-full border border-gray-300 rounded p-2"></textarea>


                    <button id="process-button"
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex gap-2 mt-2 w-full text-center justify-center items-center">Proses</button>

                </div>

            </div>

            <div id="hiddenRows"></div>
        </form>
    </div>

    <script src="{{ asset('javascript/bKeluar.js') }}"></script>
@endsection
