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
            <h1 class="text-2xl font-bold text-gray-800">Detail Barang Keluar</h1>
            <p class="text-sm text-gray-500 mt-1">Home > Barang Keluar > Detail</p>
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
                Cetak Laporan
            </button> --}}
        </div>
    </div>

    {{-- Outgoing Goods Info Card --}}
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
        <div class="flex flex-col md:flex-row justify-between items-start border-b border-gray-200 pb-4 mb-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">
                    No. Transaksi <span class="text-blue-600">#{{$bKeluar->invoice}} ({{ $bKeluar->idBarangKeluar }})</span>
                </h2>
                <p class="text-sm text-gray-500">
                    {{-- Assuming the date is in the 'created_at' field of the $bKeluar object --}}
                    Tanggal Pencatatan Transaksi: {{ \Carbon\Carbon::parse($bKeluar->created_at ?? now())->translatedFormat('d F Y, H:i') }}
                </p>
                <p class="text-sm text-gray-500">
                    {{-- Assuming the date is in the 'created_at' field of the $bKeluar object --}}
                    Tanggal Barang Keluar: {{ \Carbon\Carbon::parse($bKeluar->tglKeluar ?? now())->translatedFormat('d F Y') }}
                </p>
            </div>
            <div class="mt-4 md:mt-0 text-left md:text-right">
                <span class="px-3 py-1 text-sm font-medium text-gray-500">Status</span><br>
                <span class="px-3 py-1 text-sm font-semibold text-blue-800 bg-blue-100 rounded-full">
                    Tercatat
                </span>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <label class="text-xs text-gray-500">Dibuat Oleh</label>
                {{-- Assuming you have a user relationship --}}
                <p class="text-md font-semibold text-gray-900">{{ $bKeluar->akun->nama ?? 'Nama Petugas' }}</p>
            </div>
            <div>
                <label class="text-xs text-gray-500">Jumlah Item</label>
                <p class="text-md font-semibold text-gray-900">{{ $bKeluar->detailKeluar->count() }} Jenis Barang</p>
            </div>
            <div>
                <label class="text-xs text-gray-500">Total Harga Barang</label>
                {{-- We will calculate this total from your loop --}}
                <p class="text-md font-bold text-red-600">
                    Rp.{{ number_format($bKeluar->detailKeluar->sum('subtotal'), 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Item Details Table --}}
    <div class="mt-8 bg-white rounded-xl shadow-md border border-gray-200">
        <div class="p-6">
             <h3 class="text-lg font-semibold text-gray-800 mb-4">Rincian Barang Keluar</h3>
             <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">No.</th>
                            {{-- MERGED: Product Name + ID + Barcode --}}
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">BARANG</th>
                            {{-- MERGED: Reason + Description --}}
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">ALASAN & KETERANGAN</th>
                            {{-- MERGED: Quantity + Unit --}}
                            <th class="px-4 py-3 text-center font-semibold text-gray-600">KUANTITAS</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-600">SUBTOTAL</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        
                        {{-- Kept your Grand Total logic exactly --}}
                        @php $grandTotal = 0; @endphp 

                        @forelse ($bKeluar->detailKeluar as $index => $detail)
                            @php $grandTotal += $detail->subtotal; @endphp
                            <tr>
                                <td class="px-4 py-4 text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-4 py-4">
                                    {{-- Product column using your exact variables --}}
                                    <div class="font-medium text-gray-800">{{ $detail->barangDetailKeluar->barang->namaBarang }}</div>
                                    <div class="text-xs text-gray-500">ID: {{ $detail->idBarang }} | Barcode: {{ $detail->barangDetailKeluar->barcode }}</div>
                                </td>
                                <td class="px-4 py-4">
                                    {{-- Reason column using your exact variables and logic --}}
                                    <div class="font-semibold
                                        @if ($detail->kategoriAlasan === \App\enum\Alasan::Terjual) text-green-700
                                        @elseif($detail->kategoriAlasan) text-orange-700 @endif">
                                        {{ $detail->kategoriAlasan?->alasan() ?? 'Tidak ada alasan' }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $detail->keterangan ?? 'Tidak ada keterangan' }}</div>
                                </td>
                                <td class="px-4 py-4 text-center text-gray-700">
                                    {{-- Quantity column using your exact variables and logic --}}
                                    {{ $detail->jumlahKeluar }}
                                    @if ($detail->barang->satuan->namaSatuan() == 'pcs/eceran')
                                        Pcs
                                    @elseif($detail->barang->satuan->namaSatuan() == 'kg')
                                        Gram
                                    @elseif($detail->barang->satuan->namaSatuan() == 'dus')
                                        Dus
                                    @else
                                        {{ $detail->barang->satuan->namaSatuan() }}
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-right font-semibold text-gray-800">
                                    {{-- Subtotal column using your exact variable --}}
                                    Rp.{{ number_format($detail->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-10 text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada rincian barang</h3>
                                    <p class="mt-1 text-sm text-gray-500">Belum ada barang yang ditambahkan untuk transaksi ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    {{-- Only show the footer if there are items --}}
                    @if (!$bKeluar->detailKeluar->isEmpty())
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-right font-bold text-gray-800 text-base">Grand Total</td>
                                <td class="px-4 py-4 text-right font-bold text-red-600 text-base">
                                    {{-- Grand Total display using your exact logic --}}
                                    Rp.{{ number_format($grandTotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
             </div>
        </div>
    </div>
</div>
@endsection