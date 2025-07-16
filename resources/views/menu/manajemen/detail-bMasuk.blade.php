@extends('layout')

@section('content')
<div class="p-6 lg:p-8 bg-gray-50 min-h-screen">

    {{-- Session Alerts --}}
    @if (session('success'))
        <x-ui.alert type="success" :message="session('success')" />
    @elseif (session('error'))
        <x-ui.alert type="error" :message="session('error')" />
    @endif

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Pemasukan Barang</h1>
            <p class="text-sm text-gray-500 mt-1">Home > Barang Masuk > Detail</p>
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
                Cetak Bukti
            </button> --}}
        </div>
    </div>

    {{-- Purchase Info Card --}}
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
        <div class="flex flex-col md:flex-row justify-between items-start border-b border-gray-200 pb-4 mb-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">
                    No. Barang Masuk <span class="text-blue-600">#{{ $bMasuk->idBarangMasuk }}</span>
                </h2>
                <p class="text-sm text-gray-500">
                    Tanggal Pencatatan Transaksi: {{ \Carbon\Carbon::parse($bMasuk->created_at ?? now())->translatedFormat('d F Y, H:i') }}
                </p>
                <p class="text-sm text-gray-500">
                    Tanggal Barang Masuk: {{ \Carbon\Carbon::parse($bMasuk->tglMasuk ?? now())->translatedFormat('d F Y') }}
                </p>
            </div>
            <div class="mt-4 md:mt-0 text-left md:text-right">
                <span class="px-3 py-1 text-sm font-medium text-gray-500">Status</span><br>
                <span class="px-3 py-1 text-sm font-semibold text-green-800 bg-green-100 rounded-full">
                    Selesai
                </span>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <label class="text-xs text-gray-500">Supplier</label>
                <p class="text-md font-semibold text-gray-900">{{ $bMasuk->supplier->nama ?? 'Nama Supplier' }}</p>
            </div>
            <div>
                <label class="text-xs text-gray-500">Petugas</label>
                <p class="text-md font-semibold text-gray-900">{{ $bMasuk->akun->nama ?? 'Nama Petugas' }}</p>
            </div>
             <div>
                <label class="text-xs text-gray-500">Total Harga Barang</label>
                <p class="text-md font-bold text-blue-600">Rp.{{ number_format($bMasuk->detailMasuk->sum('hargaBeli'), 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- Item Details Table --}}
    <div class="mt-8 bg-white rounded-xl shadow-md border border-gray-200">
        <div class="p-6">
             <h3 class="text-lg font-semibold text-gray-800 mb-4">Rincian Barang Diterima</h3>
             <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">No.</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">NAMA BARANG</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-600">KUANTITAS</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-600">HARGA SATUAN</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-600">SUBTOTAL BELI</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-600">Tgl. Kadaluarsa</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        
                        @php $grandTotal = 0; @endphp 

                        @forelse ($bMasuk->detailMasuk as $index => $detail)
                            @php $grandTotal += $detail->hargaBeli; @endphp
                            <tr>
                                <td class="px-4 py-4 text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-4 py-4">
                                    <div class="font-medium text-gray-800">{{ $detail->barangDetail->barang->namaBarang }}</div>
                                    <div class="text-xs text-gray-500">ID: {{ $detail->idBarang }}</div>
                                </td>
                                <td class="px-4 py-4 text-center text-gray-700">
                                    {{ $detail->jumlahMasuk }}
                                    @if ($detail->barangDetail->barang->satuan->namaSatuan() == 'pcs/eceran')
                                        Pcs
                                    @elseif($detail->barangDetail->barang->satuan->namaSatuan() == 'kg')
                                        Gram
                                    @elseif($detail->barangDetail->barang->satuan->namaSatuan() == 'dus')
                                        Dus
                                    @else
                                        {{ $detail->barangDetail->barang->satuan->namaSatuan() }}
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-right text-gray-700">
                                    Rp.{{ number_format($detail->hargaBeli / $detail->jumlahMasuk, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-right font-semibold text-gray-800">
                                    Rp.{{ number_format($detail->hargaBeli, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-center text-gray-600">
                                    @if (in_array($detail->barangDetail->barang->kategoriBarang->value, [1, 2, 3]))
                                        {{ \Carbon\Carbon::parse($detail->tglKadaluarsa)->translatedFormat('d M Y') }}
                                    @else
                                        <span class="text-sm text-gray-400 italic">Tidak tersedia</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 text-gray-500">
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
                    @if (!$bMasuk->detailMasuk->isEmpty())
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-right font-bold text-gray-800 text-base">Grand Total</td>
                                <td class="px-4 py-4 text-right font-bold text-blue-600 text-base">
                                    Rp.{{ number_format($grandTotal, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4"></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
             </div>
        </div>
    </div>
</div>
@endsection