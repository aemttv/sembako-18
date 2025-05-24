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
            <div class="border-b px-6 py-3 font-medium text-gray-700">
                <div class="flex items-center gap-3">
                    <a href="{{ url()->previous() }}"
                        class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition text-sm font-medium shadow-sm border border-gray-300">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali
                    </a>
                    <span class="text-lg font-semibold text-gray-700">
                        Daftar Detail Barang
                        <span class="text-base font-normal text-gray-500">({{ $bKeluar->idBarangKeluar }})</span>
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div class="max-h-80 overflow-y-auto relative">
                    <table class="min-w-full table-auto border-separate border-spacing-0">
                        <thead class="sticky top-0 bg-white z-10">
                            <tr>
                                <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">No</th>
                                <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">ID Detail Barang Keluar
                                </th>
                                <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">ID Barang Keluar</th>
                                <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">ID Barang</th>
                                <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">Quantity
                                <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">Subtotal</th>
                                <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">Kategori Alasan</th>
                                <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @if ($bKeluar->detailKeluar > isEmpty())
                                <tr>
                                    <td class="px-4 py-2 border-b text-center" colspan="8">Detail Barang tidak ditemukan.
                                    </td>
                                </tr>
                            @endif --}}
                            @foreach ($bKeluar->detailKeluar as $index => $detail)
                                <tr>
                                    <td class="px-4 py-2 border-b">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 border-b">{{ $detail->idDetailBK }}</td>
                                    <td class="px-4 py-2 border-b">{{ $detail->idBarangKeluar }}</td>
                                    <td class="px-4 py-2 border-b">{{ $detail->idBarang }}</td>
                                    <td class="px-4 py-2 border-b">{{ $detail->jumlahKeluar }}</td>
                                    <td class="px-4 py-2 border-b">{{ $detail->subtotal }}</td>
                                    <td class="px-4 py-2 border-b">{{ $detail->kategoriAlasan }}</td>
                                    <td class="px-4 py-2 border-b">{{ $detail->keterangan }}</td>
                                    <td class="px-4 py-2 border-b">
                                        {{-- <button
                                                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Edit</button> --}}
                                        {{-- <form
                                            action="{{ route('soft.delete.detail', ['idBarang' => $detail->idBarang, 'barcode' => $detail->barcode]) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                                Hapus
                                            </button>
                                        </form> --}}
                                    </td>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- @endforeach --}}
    </div>
@endsection
