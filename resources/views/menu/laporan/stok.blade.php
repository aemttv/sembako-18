@extends('layout')

@section('content')
    <div class="p-6 space-y-4">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div class="flex-1">
                <h1 class="text-xl font-semibold">Laporan Stok</h1>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Home > Laporan Stok</p>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex items-end gap-4 border rounded-lg p-4 bg-white flex-wrap">
            <!-- Filter & Actions -->
            <form action="{{ route('laporan.StokBarang.search') }}" method="get" class="flex items-end gap-4 flex-wrap">
                <div class="flex flex-col">
                    <label for="tanggal_awal" class="text-sm text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="tanggal_awal"
                        class="border border-gray-300 rounded-md px-3 py-1.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300"
                        placeholder="Tanggal Awal" value="{{ request('tanggal_awal', '') }}" />
                </div>
                <div class="flex flex-col">
                    <label for="tanggal_akhir" class="text-sm text-gray-700 mb-1">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir"
                        class="border border-gray-300 rounded-md px-3 py-1.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300"
                        placeholder="Tanggal Akhir" value="{{ request('tanggal_akhir', '') }}" />
                </div>
                <div class="flex flex-col justify-end">
                    <button type="submit"
                        class="px-4 py-1.5 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 h-[42px]">
                        Tampilkan
                    </button>
                </div>
            </form>
            <!-- PDF Button -->
            <form action="{{ route('streamPDF.StokBarang.view') }}" method="post" class="flex flex-col justify-end">
                @csrf
                <input type="hidden" name="tanggal_awal" value="{{ request('tanggal_awal', '') }}">
                <input type="hidden" name="tanggal_akhir" value="{{ request('tanggal_akhir', '') }}">
                <input type="hidden" name="search" value="{{ request('search', '') }}">
                <button type="submit"
                    class="px-4 py-1.5 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 h-[42px] flex items-center gap-2">
                    <i class="fa-regular fa-file-pdf"></i>
                    View PDF
                </button>
            </form>
            <!-- Search Input (Right aligned) -->
            <div class="flex-1 flex justify-end">
                <form action="{{ route('laporan.StokBarang.search') }}" method="get" class="w-[280px]">
                    <div class="flex items-center bg-gray-50 border border-gray-200 rounded-xl px-3 shadow-sm">
                        <i class="fas fa-search text-gray-400 mr-2"></i>
                        <input type="text" placeholder="Nama Barang / ID Barang" name="search"
                            class="bg-transparent border-none focus:ring-0 focus:outline-none w-full text-sm text-gray-700 placeholder-gray-400"
                            value="{{ request('search', '') }}" />
                    </div>
                    <!-- Keep the date filters in sync when searching -->
                    <input type="hidden" name="tanggal_awal" value="{{ request('tanggal_awal', '') }}">
                    <input type="hidden" name="tanggal_akhir" value="{{ request('tanggal_akhir', '') }}">
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="border rounded-lg overflow-x-auto">
            <table class="min-w-full text-md justify-center items-center text-center">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">ID Barang</th>
                        <th class="px-4 py-2">Nama Barang</th>
                        <th class="px-4 py-2">Kategori</th>
                        <th class="px-4 py-2">Merek</th>
                        <th class="px-4 py-2">Stok Total</th>
                        <th class="px-4 py-2">Harga Jual</th>
                        <th class="px-4 py-2">Tanggal Awal Masuk</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @php $no = 1; @endphp
                    @foreach ($barang as $data)
                        <tr>
                            <td class="px-4 py-2">{{ $no++ }}</td>
                            <td class="px-4 py-2">{{ $data->idBarang }}</td>
                            <td class="px-4 py-2">{{ $data->namaBarang ?? 'Nama Barang' }}</td>
                            <td class="px-4 py-2">{{ $data->kategoriBarang->namaKategori() }}</td>
                            <td class="px-4 py-2">{{ $data->merekBarangName }}</td>
                            <td class="px-4 py-2">{{ $data->totalStok }}</td>
                            <td class="px-4 py-2">Rp.{{ number_format($data->hargaJual, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">
                                {{ optional($data->detailBarang->sortBy('tglMasuk')->first())->tglMasuk
                                    ? \Carbon\Carbon::parse($data->detailBarang->sortBy('tglMasuk')->first()->tglMasuk)->translatedFormat('d F Y')
                                    : '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center text-sm text-gray-800">
            {{ $barang->links() }}
        </div>
    </div>
@endsection
