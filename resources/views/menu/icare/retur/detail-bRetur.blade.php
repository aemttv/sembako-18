@extends('layout')

@section('content')
<div class="p-6 lg:p-8 bg-gray-50 min-h-screen">

    {{-- Session Alerts (Kept from your original code) --}}
    @if (session('success'))
        <x-ui.alert type="success" :message="session('success')" />
    @elseif (session('error'))
        <x-ui.alert type="error" :message="session('error')" />
    @endif

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Retur Barang</h1>
            <p class="text-sm text-gray-500 mt-1">Home > Retur > Detail</p>
        </div>
        <div class="flex items-center gap-2 mt-4 sm:mt-0">
            <a href="{{ url()->previous() }}"
               class="inline-flex items-center px-4 py-2 bg-white hover:bg-gray-100 text-gray-700 rounded-lg transition text-sm font-medium shadow-sm border border-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
            {{-- <button class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition text-sm font-medium shadow-sm">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Bukti Retur
            </button> --}}
        </div>
    </div>

    {{-- Return Info Card --}}
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
        <div class="flex flex-col md:flex-row justify-between items-start border-b border-gray-200 pb-4 mb-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">
                    No. Retur <span class="text-blue-600">#{{ $bRetur->idBarangRetur }}</span>
                </h2>
                <p class="text-sm text-gray-500">
                    {{-- Assuming the date is in the 'created_at' field of the $bRetur object --}}
                    Tanggal Pengajuan: {{ \Carbon\Carbon::parse($bRetur->created_at ?? now())->translatedFormat('d F Y, H:i') }}
                </p>
            </div>
            <div class="mt-4 md:mt-0 text-left md:text-right">
                <span class="px-3 py-1 text-sm font-medium text-gray-500">Status Pengajuan</span><br>
                {{-- This is a placeholder, you might have a status on the main $bRetur object --}}
                <span class="px-3 py-1 text-sm font-semibold text-yellow-800 bg-yellow-100 rounded-full">
                    Menunggu Persetujuan
                </span>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <label class="text-xs text-gray-500">Supplier</label>
                {{-- Assuming you have a relationship for the source of the return --}}
                <p class="text-md font-semibold text-gray-900">{{ $bRetur->supplier->nama ?? 'Nama Pengaju' }}</p>
            </div>
            <div>
                <label class="text-xs text-gray-500">Petugas</label>
                {{-- Assuming you have a user relationship --}}
                <p class="text-md font-semibold text-gray-900">{{ $bRetur->akun->nama ?? 'Nama Petugas' }}</p>
            </div>
             <div>
                <label class="text-xs text-gray-500">Jumlah Item Diretur</label>
                <p class="text-md font-semibold text-gray-900">{{ $bRetur->detailRetur->count() }} Jenis Barang</p>
            </div>
        </div>
    </div>

    {{-- Item Details Table --}}
    <div class="mt-8 bg-white rounded-xl shadow-md border border-gray-200">
        <div class="p-6">
             <h3 class="text-lg font-semibold text-gray-800 mb-4">Rincian Barang Retur</h3>
             <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">No.</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">BARANG</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">ALASAN & KETERANGAN</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-600">JUMLAH</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-600">STATUS</th>
                            {{-- Action column is conditional based on your isOwner() logic --}}
                            @if (isOwner())
                                <th class="px-4 py-3 text-center font-semibold text-gray-600">PROSES</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($bRetur->detailRetur as $index => $detail)
                            <tr>
                                <td class="px-4 py-4 text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-4 py-4">
                                    {{-- Product column using your exact optional() helper --}}
                                    <div class="font-medium text-gray-800">{{ mb_strimwidth(optional(optional($detail->detailBarangRetur)->barang)->namaBarang ?? 'Barang tidak ditemukan', 0, 40, '...') }}</div>
                                    <div class="text-xs text-gray-500">Barcode: {{ $detail->barcode }}</div>
                                </td>
                                <td class="px-4 py-4">
                                    {{-- Reason column using your exact variables and logic --}}
                                    <div class="font-semibold
                                        @if ($detail->kategoriAlasan === \App\enum\Alasan::cacat) text-blue-700
                                        @elseif($detail->kategoriAlasan) text-orange-700 @endif">
                                        {{ $detail->kategoriAlasan?->alasan() ?? '-' }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $detail->keterangan }}</div>
                                </td>
                                <td class="px-4 py-4 text-center text-gray-700 font-medium">
                                    {{-- Quantity column --}}
                                    {{ $detail->jumlah }}
                                    @if (optional($detail->detailBarangRetur->barang)->satuan?->namaSatuan() == 'pcs/eceran')
                                        Pcs
                                    @elseif($detail->detailBarangRetur->barang->satuan->namaSatuan() == 'kg')
                                        Gram
                                    @elseif($detail->detailBarangRetur->barang->satuan->namaSatuan() == 'dus')
                                        Dus
                                    @else
                                        {{ $detail->detailBarangRetur->barang->satuan->namaSatuan() }}
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-center">
                                    {{-- Status Badges using your exact logic --}}
                                    @if ($detail->statusReturDetail == 2)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                    @elseif ($detail->statusReturDetail == 1)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Approved</span>
                                    @elseif ($detail->statusReturDetail == 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Rejected</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Unknown</span>
                                    @endif
                                </td>
                                {{-- Actions column, keeping your forms and logic exactly as they were --}}
                                @if (isOwner())
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <form action="{{ route('detail.bRetur.approve', ['idDetailRetur' => $detail->idDetailRetur]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui detail barang ini?')">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded-md text-xs font-semibold shadow-sm">Setuju</button>
                                        </form>
                                        <form action="{{ route('detail.bRetur.reject', ['idDetailRetur' => $detail->idDetailRetur]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak detail barang ini?')">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded-md text-xs font-semibold shadow-sm">Tolak</button>
                                        </form>
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                {{-- Adjust colspan based on whether the owner is viewing --}}
                                <td colspan="{{ isOwner() ? '6' : '5' }}" class="text-center py-10 text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada rincian retur</h3>
                                    <p class="mt-1 text-sm text-gray-500">Belum ada barang yang diajukan untuk retur pada transaksi ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
             </div>
        </div>
    </div>
</div>
@endsection