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
                <h1 class="text-xl font-semibold">Halaman Detail Retur Barang</h1>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Home > Detail Barang</p>
            </div>
        </div>

        <div class="mt-6 border rounded-lg bg-white shadow-sm">
            <div class="border-b px-6 py-3 font-medium text-gray-700">Daftar Detail Barang Retur - ({{ $bRetur->idBarangRetur }})</div>
            <div class="p-6">
                <div class="max-h-80 overflow-y-auto relative">
                    <table class="min-w-full table-auto border-separate border-spacing-0">
                        <thead class="sticky top-0 bg-white z-10">
                            <tr>
                                <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">No</th>
                                <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">Detail Retur ID</th>
                                <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">Barang Retur ID</th>
                                <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">Barang Barcode</th>
                                <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">Jumlah</th>
                                <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">Kategori Keterangan
                                <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">Keterangan</th>
                                </th>
                                <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">Status</th>
                                <th class="px-4 py-2 border-b border-gray-300 text-left bg-white">Proses</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($bRetur->detailRetur->isEmpty())
                                <tr>
                                    <td class="px-4 py-2 border-b text-center" colspan="8">Detail Barang tidak ditemukan.
                                    </td>
                                </tr>
                            @endif
                            @foreach ($bRetur->detailRetur as $index => $detail)
                                <tr>
                                    <td class="px-4 py-2 border-b">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 border-b">{{ $detail->idDetailRetur }}</td>
                                    <td class="px-4 py-2 border-b">{{ $detail->idBarangRetur }}</td>
                                    <td class="px-4 py-2 border-b">{{ $detail->barcode }}</td>
                                    <td class="px-4 py-2 border-b">{{ $detail->jumlah }}</td>
                                    <td class="px-4 py-2 border-b">{{ $detail->kategoriAlasan }}</td>
                                    <td class="px-4 py-2 border-b">{{ $detail->keterangan }}</td>
                                    <td class="px-4 py-2 border-b">
                                        @if ($detail->statusReturDetail == 2)
                                            <span class="text-yellow-500 font-semibold">Pending</span>
                                        @elseif ($detail->statusReturDetail == 1)
                                            <span class="text-green-500 font-semibold">Approved</span>
                                        @elseif ($detail->statusReturDetail == 0)
                                            <span class="text-red-500 font-semibold">Rejected</span>
                                        @else
                                            <span class="text-gray-500">Unknown</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 border-b flex gap-1 items-center justify-center">
                                        <form action="{{ route('detail.bRetur.approve', ['idDetailRetur' => $detail->idDetailRetur]) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="px-2 py-1 bg-green-500 text-white rounded text-xs">Setuju</button>
                                        </form>
                                        <form action="{{route('detail.bRetur.reject', ['idDetailRetur' => $detail->idDetailRetur])}}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="px-2 py-1 bg-yellow-500 text-white rounded text-xs">Tolak</button>
                                        </form>
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
