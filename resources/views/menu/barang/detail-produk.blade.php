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
        <div class="border rounded-lg bg-white shadow-sm" x-data="{ editing: false }" x-transition>
            <div class="flex items-center justify-between border-b px-6 py-4 bg-white rounded-t-lg shadow-sm mb-2">
                <div class="flex items-center gap-3">
                    <a href="{{ url()->previous() }}"
                        class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition text-sm font-medium shadow-sm border border-gray-300">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali
                    </a>
                    <span class="text-lg font-semibold text-gray-700">
                        Daftar Detail Produk
                        <span class="text-base font-normal text-gray-500">({{ $barang->idBarang }})</span>
                    </span>
                </div>
                <button type="button" @click="editing = true" x-show="!editing"
                    class="inline-flex text-right items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition text-sm font-medium shadow-sm border border-gray-300">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.862 3.487a2.25 2.25 0 1 1 3.182 3.182l-12.12 12.12a2 2 0 0 1-.878.513l-4 1a.75.75 0 0 1-.91-.91l1-4a2 2 0 0 1 .513-.878l12.12-12.12z" />
                    </svg>
                    Edit
                </button>
                <button type="button" @click="editing = false" x-show="editing"
                    class="inline-flex text-right items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition text-sm font-medium shadow-sm border border-gray-300">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Batal
                </button>
            </div>

            <form method="POST"
                action="{{ route('detail.barang.update', $barang->idBarang) }} "x-transition.opacity.duration.500ms
                enctype="multipart/form-data">
                @csrf
                {{-- @method('PUT') --}}
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column: Image Section -->
                    <div class="space-y-4" x-data="{ imagePreview: null }">
                        <div class="space-y-4">
                            <label
                                class="flex items-center justify-center border border-dashed bg-gray-50 rounded-md h-64 overflow-hidden relative transition"
                                :class="editing ? 'hover:shadow-lg' : ''">
                                <!-- Image or Placeholder -->
                                <template x-if="!imagePreview">
                                    <template x-if="!editing">
                                        @if ($barang->gambarProduk)
                                            <img src="{{ asset('produk/' . $barang->gambarProduk) }}" alt="Gambar Produk"
                                                class="h-full w-full object-contain text-center items-center justify-center">
                                        @else
                                            <span class="text-gray-400 text-sm">[Gambar Produk]</span>
                                        @endif
                                    </template>
                                </template>
                                <!-- Preview New Image -->
                                <template x-if="imagePreview">
                                    <img :src="imagePreview" alt="Preview Gambar Produk"
                                        class="h-full w-full object-contain text-center items-center justify-center">
                                </template>
                                <!-- Overlay for editing mode -->
                                <template x-if="editing">
                                    <div
                                        class="absolute inset-0 flex flex-col items-center justify-center bg-black/30 text-white text-sm font-medium pointer-events-none">
                                        <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 16v-4m0 0V8m0 4h4m-4 0H8m12 4v4a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-4" />
                                        </svg>
                                        <span>Klik untuk pilih gambar</span>
                                    </div>
                                </template>
                                <!-- Hidden File Input -->
                                <input x-ref="fileInput" type="file" name="gambarProduk" accept="image/*" class="hidden"
                                    :disabled="!editing"
                                    @change="
                                        if ($event.target.files.length) {
                                            const reader = new FileReader();
                                            reader.onload = e => imagePreview = e.target.result;
                                            reader.readAsDataURL($event.target.files[0]);
                                        } else {
                                            imagePreview = null;
                                        }
                                    ">
                                <!-- Click handler to open file input when editing -->
                                <span x-show="editing" class="absolute inset-0 cursor-pointer"
                                    @click.prevent="$refs.fileInput.click()"></span>
                            </label>
                            <template x-if="editing && imagePreview">
                                <div class="text-xs text-gray-500 text-center">Preview gambar baru sebelum disimpan.</div>
                            </template>
                        </div>
                    </div>

                    <!-- Right Column: Product Detail Inputs -->
                    <div class="grid gap-4 font-semibold">
                        {{-- Row 1: Nama Barang --}}
                        <div class="w-full">
                            <label class="block text-sm mb-1">Nama Barang</label>
                            <input type="text" id="nama_barang" name="nama_barang"
                                class="w-full border rounded-md px-3 py-2 transition"
                                :class="!editing ? 'bg-gray-50 text-gray-500 cursor-not-allowed' : 'bg-white text-gray-700'"
                                placeholder="Search Barang..." autocomplete="off" value="{{ $barang->namaBarang }}"
                                :readonly="!editing">
                            <input type="hidden" id="barang_id" name="barang_id" value="{{ $barang->idBarang }}" />
                        </div>

                        {{-- Row 2: Brand/Merek and Kategori --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm mb-1">Brand/Merek</label>
                                <select id="brand" name="idMerek" class="w-full border rounded-md px-3 py-2 transition"
                                    :class="!editing ? 'bg-gray-50 text-gray-500 cursor-not-allowed' : 'bg-white text-gray-700'"
                                    :disabled="!editing">
                                    @foreach ($mereks as $merek)
                                        <option value="{{ $merek->idMerek }}"
                                            {{ $barang->merekBarang == $merek->idMerek ? 'selected' : '' }}>
                                            {{ $merek->namaMerek }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm mb-1">Kategori</label>
                                <select name="kategori" class="w-full border rounded-md px-3 py-2 transition"
                                    :class="!editing ? 'bg-gray-50 text-gray-500 cursor-not-allowed' : 'bg-white text-gray-700'"
                                    :disabled="!editing">
                                    @foreach ($kategori as $kat)
                                        <option value="{{ $kat->value }}"
                                            {{ (old('kategori') ?? optional($barang->kategoriBarang)->value) == $kat->value ? 'selected' : '' }}>
                                            {{ $kat->namaKategori() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Row 3: Harga Jual and Jumlah Stok --}}
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm mb-1">Harga Jual</label>
                                <input type="text" id="harga_satuan" name="harga_satuan"
                                    class="w-full border rounded-md px-3 py-2 transition"
                                    :class="!editing ? 'bg-gray-50 text-gray-500 cursor-not-allowed' : 'bg-white text-gray-700'"
                                    value="Rp.{{ number_format($barang->hargaJual, 0, ',', '.') }}"
                                    :readonly="!editing" />
                            </div>
                            <div>
                                <label class="block text-sm mb-1">Jumlah Stok</label>
                                <input type="text" id="jumlah_stok" name="jumlah_stok"
                                    class="w-full border rounded-md px-3 py-2 transition bg-gray-50 text-gray-500 cursor-not-allowed"
                                    value="{{ $barang->totalStok }}" readonly />
                            </div>
                            <div>
                                <label class="block text-sm mb-1">Satuan</label>
                                <select name="satuan" class="w-full border rounded-md px-3 py-2 transition"
                                    :class="!editing ? 'bg-gray-50 text-gray-500 cursor-not-allowed' : 'bg-white text-gray-700'"
                                    :disabled="!editing">
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
                    <input type="hidden" name="status_produk" value="1" />
                </div>

                <!-- Buttons -->
                <div class="flex justify-between px-6 py-4 border-t bg-gray-50">
                    <div class=" text-white px-4 py-2 rounded "></div>
                    <div class="space-x-2">
                        <button type="submit" x-bind:disabled="!editing"
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 disabled:opacity-50">Simpan</button>
                        <button type="button" @click="editing = false" x-show="editing"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Batal</button>
                        <button type="button" @click="window.location.href='/daftar-produk'" x-show="!editing"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Kembali</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-6 border rounded-lg bg-white shadow-sm">
            <div class="border-b px-6 py-3 text-lg font-semibold mb-2 text-black">Detail Barang <span
                    class="text-green-500">Aktif</span> - ID Barang({{ $barang->idBarang }})</div>
            <div class="p-6">
                <div class="max-h-80 overflow-y-auto relative">
                    <table
                        class="min-w-full table-auto border-separate border-spacing-0 justify-center text-center items-center">
                        <thead class="sticky top-0 bg-white z-10">
                            <tr>
                                <th class="px-4 py-2 border-b border-gray-300 bg-white">No</th>
                                <th class="px-4 py-2 border-b border-gray-300 bg-white">ID Detail</th>
                                <th class="px-4 py-2 border-b border-gray-300 bg-white">ID Supplier</th>
                                <th class="px-4 py-2 border-b border-gray-300 bg-white">QR Code</th>
                                <th class="px-4 py-2 border-b border-gray-300 bg-white">Barcode</th>
                                @if ($barang->satuan->value === 2)
                                    <th class="px-4 py-2 border-b border-gray-300 bg-white">Berat(Kg)</th>
                                @else
                                    <th class="px-4 py-2 border-b border-gray-300 bg-white">Kuantitas</th>
                                @endif
                                <th class="px-4 py-2 border-b border-gray-300 bg-white">Kondisi</th>
                                <th class="px-4 py-2 border-b border-gray-300 bg-white">Tanggal Masuk</th>
                                @if (in_array($barang->kategoriBarang->value, [1, 2, 3]))
                                    <th class="px-4 py-2 border-b border-gray-300 bg-white">Tanggal Kadaluarsa</th>
                                @endif
                                </th>
                                @if (isOwner())
                                    <th class="px-4 py-2 border-b border-gray-300 bg-white">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if ($barang->detailBarang->isEmpty())
                                <tr>
                                    <td class="px-4 py-2 border-b text-center" colspan="10">Detail Barang tidak
                                        ditemukan.
                                    </td>
                                </tr>
                            @endif
                            @foreach ($barang->detailBarang as $index => $detail)
                                <tr class= "hover:bg-gray-50">
                                    <td class="px-4 py-2 border-b">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 border-b">{{ $detail->idDetailBarang }}</td>
                                    <td class="px-4 py-2 border-b">{{ $detail->idSupplier }}</td>
                                    <td class="px-4 py-2 border-b">
                                        <!-- QR Code containing the URL -->
                                        @php
                                            $dns2d = new Milon\Barcode\DNS2D();
                                            $productUrl = url("/barcode/{$detail->barcode}");
                                        @endphp
                                        <a href="{{ route('barcode.view.detail', ['barcode' => $detail->barcode]) }}"
                                            class="mt-1" target="_blank">
                                            <img src="data:image/png;base64, {!! $dns2d->getBarcodePNG($productUrl, 'QRCODE', 4, 4) !!}" alt="QR Code"
                                                class="h-20 w-20">
                                        </a>
                                    </td>
                                    <td class="px-4 py-2 border-b">
                                        <!-- Barcode Generates -->
                                        @php
                                            $dns1d = new Milon\Barcode\DNS1D();
                                            $barcode = $dns1d->getBarcodePNG(
                                                $detail->barcode, // Encode the full URL here
                                                'C128', //barcode type
                                                2, // width scale
                                                40, // height
                                                [0, 0, 0], //black color
                                                false,
                                            );
                                        @endphp

                                        <a href="{{ route('barcode.view.detail', ['barcode' => $detail->barcode]) }}"
                                            class="mt-1" target="_blank">
                                            <img src="data:image/png;base64, {!! $barcode !!}" alt="Barcode"
                                                class="w-full h-auto">
                                        </a>
                                        {{ $detail->barcode }}
                                    </td>
                                    <td class="px-4 py-2 border-b">{{ $detail->quantity }}</td>
                                    <td class="px-4 py-2 border-b">
                                        <span
                                            @if ($detail->kondisiBarang == 'Kadaluarsa') class="text-red-600 font-bold"
                                            @elseif($detail->kondisiBarang == 'Mendekati Kadaluarsa')
                                                class="text-orange-500 font-semibold"
                                            @else
                                                class="text-green-600 font-semibold" @endif>
                                            {{ $detail->kondisiBarang }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 border-b">
                                        {{ \Carbon\Carbon::parse($detail->tglMasuk)->translatedFormat('d F Y') }}</td>
                                    @if (in_array($barang->kategoriBarang->value, [1, 2, 3]))
                                        <td class="px-4 py-2 border-b">
                                            {{ \Carbon\Carbon::parse($detail->tglKadaluarsa)->translatedFormat('d F Y') }}
                                        </td>
                                    @endif
                                    @if (isOwner())
                                        <td class="px-4 py-2 border-b">
                                            <form
                                                action="{{ route('soft.delete.detail', ['idBarang' => $detail->idBarang, 'barcode' => $detail->barcode]) }}"
                                                method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus detail barang ini?')">
                                                @csrf
                                                <button type="submit"
                                                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @if (isOwner())
            @if ($inactiveDetail->count())
                <div class="mt-6 border rounded-lg bg-white shadow-sm">
                    <div class="border-b px-6 py-3 text-lg font-semibold mb-2 text-black">Detail Barang <span
                            class="text-red-400">Tidak Aktif</span> - ID Barang({{ $barang->idBarang }})</div>
                    <div class="p-6">
                        <div class="max-h-80 overflow-y-auto relative">
                            <table
                                class="min-w-full table-auto border-separate border-spacing-0 justify-center text-center items-center">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 border-b">No</th>
                                        <th class="px-4 py-2 border-b">ID Detail</th>
                                        <th class="px-4 py-2 border-b">ID Supplier</th>
                                        <th class="px-4 py-2 border-b">QR Code</th>
                                        <th class="px-4 py-2 border-b">Barcode</th>
                                        <th class="px-4 py-2 border-b">Tanggal Masuk</th>
                                        <th class="px-4 py-2 border-b">Tanggal Kadaluarsa</th>
                                        <th class="px-4 py-2 border-b">Kuantitas</th>
                                        <th class="px-4 py-2 border-b">Kondisi</th>
                                        <th class="px-4 py-2 border-b">Proses</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inactiveDetail as $index => $detailInactive)
                                        <tr class= "hover:bg-gray-50">
                                            <td class="px-4 py-2 border-b">{{ $index + 1 }}</td>
                                            <td class="px-4 py-2 border-b">{{ $detailInactive->idDetailBarang }}</td>
                                            <td class="px-4 py-2 border-b">{{ $detailInactive->idSupplier }}</td>
                                            <td class="px-4 py-2 border-b">
                                                <!-- QR Code containing the URL -->
                                                @php
                                                    $dns2d = new Milon\Barcode\DNS2D();
                                                    $productUrl = url("/barcode/{$detailInactive->barcode}");
                                                @endphp
                                                <a href="{{ route('barcode.view.detail', ['barcode' => $detailInactive->barcode]) }}"
                                                    class="mt-1" target="_blank">
                                                    <img src="data:image/png;base64, {!! $dns2d->getBarcodePNG($productUrl, 'QRCODE', 4, 4) !!}"
                                                        alt="QR Code" class="h-20 w-20">
                                                </a>
                                            </td>
                                            <td class="px-4 py-2 border-b">
                                                <!-- Barcode Generates -->
                                                @php
                                                    $dns1d = new Milon\Barcode\DNS1D();
                                                    $barcode = $dns1d->getBarcodePNG(
                                                        $detailInactive->barcode, // Encode the full URL here
                                                        'C128', //barcode type
                                                        2, // width scale
                                                        40, // height
                                                        [0, 0, 0], //black color
                                                        false,
                                                    );
                                                @endphp

                                                <a href="{{ route('barcode.view.detail', ['barcode' => $detailInactive->barcode]) }}"
                                                    class="mt-1" target="_blank">
                                                    <img src="data:image/png;base64, {!! $barcode !!}"
                                                        alt="Barcode" class="w-full h-auto">
                                                </a>
                                                {{ $detailInactive->barcode }}
                                            </td>
                                            <td class="px-4 py-2 border-b">
                                                {{ \Carbon\Carbon::parse($detailInactive->tglMasuk)->translatedFormat('d F Y') }}
                                            </td>
                                            <td class="px-4 py-2 border-b">
                                                {{ \Carbon\Carbon::parse($detailInactive->tglKadaluarsa)->translatedFormat('d F Y') }}
                                            </td>
                                            <td class="px-4 py-2 border-b">{{ $detailInactive->quantity }}</td>
                                            <td class="px-4 py-2 border-b">
                                                <span
                                                    @if ($detailInactive->kondisiBarang == 'Kadaluarsa') class="text-red-600 font-bold"
                                                            @elseif($detailInactive->kondisiBarang == 'Mendekati Kadaluarsa')
                                                                class="text-orange-500 font-semibold"
                                                            @else
                                                                class="text-green-600 font-semibold" @endif>
                                                    {{ $detailInactive->kondisiBarang }}
                                                </span>
                                            </td>

                                            <td class="px-4 py-2 border-b">
                                                <form
                                                    action="{{ route('soft.update.detail', ['idBarang' => $detailInactive->idBarang, 'barcode' => $detailInactive->barcode]) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus detail barang ini?')">
                                                    @csrf
                                                    <button type="submit"
                                                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                                                        Kembalikan
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        @endif

    </div>
@endsection
