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
                <h1 class="text-xl font-semibold">Halaman Detail Barang Rusak</h1>
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
                        Daftar Detail Barang Rusak
                        <span class="text-base font-normal text-gray-500">({{ $bRusak->idBarangRusak }})</span>
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div class="max-h-80 overflow-y-auto relative">
                    <table class="min-w-full table-auto border-separate border-spacing-0 text-lg text-center items-center">
                        <thead class="sticky top-0 bg-white">
                            <tr>
                                <th class="px-4 py-2 border-b border-gray-300">No</th>
                                <th class="px-4 py-2 border-b border-gray-300">Detail ID</th>
                                <th class="px-4 py-2 border-b border-gray-300">Rusak ID</th>
                                <th class="px-4 py-2 border-b border-gray-300">Nama Barang</th>
                                <th class="px-4 py-2 border-b border-gray-300">Barcode</th>
                                <th class="px-4 py-2 border-b border-gray-300">Jumlah/Berat(kg)</th>
                                <th class="px-4 py-2 border-b border-gray-300">Kategori Keterangan
                                <th class="px-4 py-2 border-b border-gray-300">Keterangan</th>
                                </th>
                                <th class="px-4 py-2 border-b border-gray-300">Status</th>
                                @if (isOwner())
                                    <th class="px-4 py-2 border-b border-gray-300">Proses</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="odd:bg-gray-100">
                            @if ($bRusak->detailRusak->isEmpty())
                                <tr>
                                    <td class="px-4 py-2 border-b text-center" colspan="9">Detail Barang tidak ditemukan.
                                    </td>
                                </tr>
                            @endif
                            @foreach ($bRusak->detailRusak as $index => $detail)
                                <tr class="hover:bg-blue-50">
                                    <td class="px-4 py-2 border-b">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 border-b">{{ $detail->idDetailBR }}</td>
                                    <td class="px-4 py-2 border-b">{{ $detail->idBarangRusak }}</td>
                                    <td class="px-4 py-2 border-b">
                                        {{ mb_strimwidth(optional(optional($detail->detailBarangRusak)->barang)->namaBarang ?? '-', 0, 40, '...') }}
                                    </td>
                                    <td class="px-4 py-2 border-b">{{ $detail->barcode }}</td>
                                    <td class="px-4 py-2 border-b">{{ $detail->jumlah }}</td>
                                    <td class="px-4 py-2 border-b 
                                        @if ($detail->kategoriAlasan === \App\enum\Alasan::Terjual) text-green-700
                                        @elseif($detail->kategoriAlasan) text-orange-700 @endif ">
                                        {{ $detail->kategoriAlasan?->alasan() ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2 border-b">{{ $detail->keterangan }}</td>
                                    <td class="px-4 py-2 border-b">
                                        @if ($detail->statusRusakDetail == 2)
                                            <span class="text-yellow-500 font-semibold">Pending</span>
                                        @elseif ($detail->statusRusakDetail == 1)
                                            <span class="text-green-500 font-semibold">Approved</span>
                                        @elseif ($detail->statusRusakDetail == 0)
                                            <span class="text-red-500 font-semibold">Rejected</span>
                                        @else
                                            <span class="text-gray-500">Unknown</span>
                                        @endif
                                    </td>
                                    @if (isOwner())
                                        <td class="px-4 py-2 border-b flex gap-1 items-center justify-center">
                                            <form
                                                action="{{ route('detail.bRusak.approve', ['idDetailBR' => $detail->idDetailBR]) }}"
                                                method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui detail barang ini?')">
                                                @csrf
                                                <button type="submit"
                                                    class="px-2 py-1 bg-green-500 text-white rounded text-xs">Setuju</button>
                                            </form>
                                            <form
                                                action="{{ route('detail.bRusak.reject', ['idDetailBR' => $detail->idDetailBR]) }}"
                                                method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak detail barang ini?')">
                                                @csrf
                                                <button type="submit"
                                                    class="px-2 py-1 bg-yellow-500 text-white rounded text-xs">Tolak</button>
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
    </div>
@endsection
