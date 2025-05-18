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
                <h1 class="text-xl font-semibold">Halaman Detail Produk</h1>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Home > Detail Barang</p>
            </div>
        </div>

        <!-- Form Container -->
        @foreach ($barang as $data)
            <div class="border rounded-lg bg-white shadow-sm">
                <div class="border-b px-6 py-3 font-medium text-gray-700">Detail Produk ({{ $data->idBarang }})</div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column: Image Section -->
                    <div class="space-y-4">
                        <!-- Product Image Placeholder -->
                        <div class="flex items-center justify-center border border-dashed bg-gray-50 rounded-md h-64">
                            <span class="text-gray-400 text-sm">[Gambar Produk]</span>
                        </div>
                    </div>

                    <!-- Right Column: Product Detail Inputs -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Nama Barang</label>
                            <input type="text" id="nama_barang" name="nama_barang"
                                class="w-full border rounded-md px-3 py-2" placeholder="Search Barang..." autocomplete="off"
                                value="{{ $data->namaBarang }}">
                            <input type="hidden" id="barang_id" name="barang_id" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Brand/Merek</label>
                            <input type="text" id="brand" name="brand" class="w-full border rounded-md px-3 py-2"
                                placeholder="Brand Barang" value="{{ $data->merekBarangName }}">
                        </div>

                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Kategori</label>
                            <input type="text" id="kategoriBarang" class="w-full border rounded-md px-3 py-2"
                                value="{{ $data->kategoriBarang->namaKategori() ?? '-' }}" />
                        </div>

                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Harga Jual</label>
                            <input type="text" id="harga_satuan" class="w-full border rounded-md px-3 py-2"
                                value="Rp.{{ number_format($data->hargaJual, 0, ',', '.') }}" />
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Jumlah Stok</label>
                            <input type="number" id="jumlah_stok" class="w-full border rounded-md px-3 py-2"
                                value="{{ $data->totalStok }}" />

                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Status Produk</label>
                            <select class="w-full border rounded-md px-3 py-2">
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-between px-6 py-4 border-t bg-gray-50">
                    <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Hapus Produk</button>
                    <div class="space-x-2">
                        <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Simpan</button>
                        <button class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Kembali</button>
                    </div>
                </div>
            </div>

            <div class="mt-6 border rounded-lg bg-white shadow-sm">
                <div class="border-b px-6 py-3 font-medium text-gray-700">Daftar Detail Barang ({{ $data->idBarang }})</div>
                <div class="p-6">
                    <div class="max-h-80 overflow-y-auto relative">
                        <table class="min-w-full table-auto border-separate border-spacing-0">
                            <thead class="sticky top-0 bg-white z-10">
                                <tr>
                                    <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">No</th>
                                    <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">Barang ID</th>
                                    <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">Barcode</th>
                                    <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">Tanggal Masuk</th>
                                    <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">Tanggal Kadaluarsa
                                    <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">Kondisi</th>
                                    </th>
                                    <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">Kuantitas</th>
                                    <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($data->detailBarang->isEmpty())
                                    <tr>
                                        <td class="px-4 py-2 border-b text-center" colspan="8">Detail Barang tidak ditemukan.</td>
                                    </tr>
                                @endif
                                @foreach ($data->detailBarang as $index => $detail)
                                    <tr>
                                        <td class="px-4 py-2 border-b">{{ $index + 1 }}</td>
                                        <td class="px-4 py-2 border-b">{{ $detail->idBarang }}</td>
                                        <td class="px-4 py-2 border-b">{{ $detail->barcode }}</td>
                                        <td class="px-4 py-2 border-b">
                                            {{ \Carbon\Carbon::parse($detail->tglMasuk)->translatedFormat('d F Y') }}</td>
                                        <td class="px-4 py-2 border-b">
                                            {{ \Carbon\Carbon::parse($detail->tglKadaluarsa)->translatedFormat('d F Y') }}
                                        </td>
                                        <td class="px-4 py-2 border-b">{{ $detail->kondisiBarang }}</td>
                                        <td class="px-4 py-2 border-b">{{ $detail->quantity }}</td>
                                        <td class="px-4 py-2 border-b">
                                            {{-- <button
                                                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Edit</button> --}}
                                            <form
                                                action="{{ route('soft.delete.detail', ['idBarang' => $detail->idBarang, 'barcode' => $detail->barcode]) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
