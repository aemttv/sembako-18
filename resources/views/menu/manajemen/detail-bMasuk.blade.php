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


        <div class="mt-6 border rounded-lg bg-white shadow-sm">
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
                        Daftar Detail Barang Masuk
                        <span class="text-base font-normal text-gray-500">({{ $bMasuk->idBarangMasuk }})</span>
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div class="max-h-80 overflow-y-auto relative">
                    <table class="min-w-full table-auto border-separate border-spacing-0">
                        <thead class="sticky top-0 bg-white z-10">
                            <tr>
                                <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">No</th>
                                <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">ID Detail Barang Keluar</th>
                                <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">ID Barang Keluar</th>
                                <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">ID Barang</th>
                                <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">Harga Beli
                                <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">Kuantitas
                                <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @if ($bKeluar->detailKeluar>isEmpty())
                                <tr>
                                    <td class="px-4 py-2 border-b text-center" colspan="8">Detail Barang tidak ditemukan.
                                    </td>
                                </tr>
                            @endif --}}
                            @foreach ($bMasuk->detailMasuk as $index => $detail)
                                <tr>
                                    <td class="px-4 py-2 border-b">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 border-b">{{ $detail->idDetailBM }}</td>
                                    <td class="px-4 py-2 border-b">{{ $detail->idBarangMasuk }}</td>
                                    <td class="px-4 py-2 border-b">{{ $detail->idBarang }}</td>
                                    <td class="px-4 py-2 border-b">Rp.{{ number_format($detail->hargaBeli, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 border-b">{{ $detail->jumlahMasuk }}</td>
                                    <td class="px-4 py-2 border-b">Rp.{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                    {{-- <td class="px-4 py-2 border-b">
                                        <button
                                                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Edit</button>
                                        <form
                                            action="{{ route('soft.delete.detail', ['idBarang' => $detail->idBarang, 'barcode' => $detail->barcode]) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                                Hapus
                                            </button>
                                        </form>
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
