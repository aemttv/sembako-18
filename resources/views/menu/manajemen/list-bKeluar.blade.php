@extends('layout')

@section('content')
    <div class="p-6 space-y-4">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div class="flex-1">
                <h1 class="text-xl font-semibold">Tabel Barang Keluar </h1>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Home > Daftar Produk</p>
            </div>
        </div>


        <!-- Tabs -->
        <div class="flex justify-between items-center gap-2 border rounded-lg p-2 bg-white">
            <!-- Search Input Group -->
            <div class="flex-1 flex justify-center">
                <form action="{{ route('bkeluar.search') }}" method="GET"
                    class="flex items-center bg-gray-50 border border-gray-200 rounded-xl px-3 w-[360px] shadow-sm h-11">
                    <i class="fas fa-search text-gray-400 mr-2"></i>
                    <input type="text" name="q" placeholder="ID Barang Keluar / Invoice / ID Akun."
                        value="{{ request('q') }}"
                        class="bg-transparent border-none focus:ring-0 focus:outline-none w-full text-sm text-gray-700 placeholder-gray-400 h-full" />
                </form>
            </div>
            <a href="/barang-keluar"
                class="px-4 py-2 text-sm font-medium text-white bg-green-500 rounded-md hover:bg-green-600">Catat Barang
                Keluar</a>
        </div>


        <!-- Table -->
        <div class="border rounded-lg overflow-x-auto">
            <table class="min-w-full text-lg text-center items-center">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2">ID Barang Keluar</th>
                        <th class="px-4 py-2">Invoice</th>
                        <th class="px-4 py-2">Grand Total</th>
                        <th class="px-4 py-2">ID Akun</th>
                        <th class="px-4 py-2">Tanggal Keluar</th>
                        <th class="px-4 py-2">Proses</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @if (isOwner())
                        @if ($bKeluar->isEmpty())
                            <tr>
                                <td class="px-4 py-2 text-center" colspan="6">
                                    Data Barang Masuk tidak ditemukan.
                                </td>
                            </tr>
                        @endif
                        @foreach ($bKeluar as $data)
                            <tr class="hover:bg-blue-50 even:bg-gray-50">
                                <td class="px-4 py-2">{{ $data->idBarangKeluar }}</td>
                                <td class="px-4 py-2">{{ $data->invoice }}</td>
                                <td class="px-4 py-2">
                                    Rp{{ number_format($data->grand_total, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-2">{{ explode(' ', trim($data->akun->nama))[0] }} ({{ $data->idAkun }})
                                </td>
                                <td class="px-4 py-2">
                                    {{ \Carbon\Carbon::parse($data->tglKeluar)->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <a href="{{ route('detail.bKeluar', ['idBarangKeluar' => $data->idBarangKeluar]) }}"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        @if ($bKeluarStaff->isEmpty())
                            <tr>
                                <td class="px-4 py-2 text-center" colspan="6">
                                    Data Barang Masuk tidak ditemukan.
                                </td>
                            </tr>
                        @endif
                        @foreach ($bKeluarStaff as $data)
                            <tr class="hover:bg-blue-50 even:bg-gray-50">
                                <td class="px-4 py-2">{{ $data->idBarangKeluar }}</td>
                                <td class="px-4 py-2">{{ $data->invoice }}</td>
                                <td class="px-4 py-2">
                                    Rp{{ number_format($data->grand_total, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-2">{{ explode(' ', trim($data->akun->nama))[0] }} ({{ $data->idAkun }})
                                </td>
                                <td class="px-4 py-2">
                                    {{ \Carbon\Carbon::parse($data->tglKeluar)->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <a href="{{ route('detail.bKeluar', ['idBarangKeluar' => $data->idBarangKeluar]) }}"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        {{ $bKeluar->links() }}
    </div>
@endsection
