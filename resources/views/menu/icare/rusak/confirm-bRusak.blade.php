@extends('layout')

@section('content')
    <div class="p-6 space-y-4">
        @if (session('success'))
            <x-ui.alert type="success" :message="session('success')" />
        @elseif (session('error'))
            <x-ui.alert type="error" :message="session('error')" />
        @endif

        @if (isOwner())
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div class="flex-1">
                    <h1 class="text-xl font-semibold">Konfirmasi Barang Rusak</h1>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Home > Barang Rusak</p>
                </div>
            </div>

            <!-- Tabs -->
            <div class="flex justify-between items-center gap-2 border rounded-lg p-2 bg-white">
                <!-- Search Input Group -->
                <div class="flex items-center bg-gray-50 border border-gray-200 rounded-xl px-3 w-[360px] shadow-sm mx-auto">
                    <i class="fas fa-search text-gray-400 mr-2"></i>
                    <input type="text" placeholder="Search or type command..."
                        class="bg-transparent border-none focus:ring-0 focus:outline-none w-full text-sm text-gray-700 placeholder-gray-400" />
                </div>
                <a href="/ajukan-barang-rusak"
                    class="px-4 py-2 text-sm font-medium text-white bg-green-500 rounded-md hover:bg-green-600">Ajukan
                    Barang Rusak</a>
            </div>

            <!-- Table -->
            <div class="border rounded-lg overflow-x-auto">
                <table class="min-w-full text-lg text-center items-center justify-center">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">ID Barang Rusak</th>
                            <th class="px-4 py-2">Penanggung Jawab</th>
                            <th class="px-4 py-2">Tanggal Rusak</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Proses</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                        @if ($bRusak->isEmpty())
                            <tr>
                                <td colspan="6" class="px-4 py-2 text-center">Tidak ada data barang rusak yang diajukan
                                    saat ini..</td>
                            </tr>
                        @endif
                        @foreach ($bRusak as $rusak)
                            <tr>
                                <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2">{{ $rusak->idBarangRusak }}</td>
                                <td class="px-4 py-2">{{ $rusak->penanggungJawab }}</td>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($rusak->tglRusak)->format('d M Y') }}</td>
                                <td class="px-4 py-2">
                                    @if ($rusak->statusRusak == 2)
                                        <span class="text-yellow-500 font-semibold">Pending</span>
                                    @elseif ($rusak->statusRusak == 1)
                                        <span class="text-green-500 font-semibold">Approved</span>
                                    @elseif ($rusak->statusRusak == 0)
                                        <span class="text-red-500 font-semibold">Rejected</span>
                                    @else
                                        <span class="text-gray-500">Unknown</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 flex gap-1 text-lg text-center items-center justify-center">
                                    <a href="{{ route('detail.bRusak', ['idBarangRusak' => $rusak->idBarangRusak]) }}"
                                        class="px-2 py-1 bg-blue-500 text-white rounded">Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div class="flex-1">
                    <h1 class="text-xl font-semibold">Halaman Barang Rusak</h1>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Home > Barang Rusak</p>
                </div>
            </div>

            <!-- Tabs -->
            <div class="flex justify-between items-center gap-2 border rounded-lg p-2 bg-white">
                <!-- Search Input Group -->
                <div
                    class="flex items-center bg-gray-50 border border-gray-200 rounded-xl px-3 w-[360px] shadow-sm mx-auto">
                    <i class="fas fa-search text-gray-400 mr-2"></i>
                    <input type="text" placeholder="Search or type command..."
                        class="bg-transparent border-none focus:ring-0 focus:outline-none w-full text-sm text-gray-700 placeholder-gray-400" />
                </div>
                <a href="/ajukan-barang-rusak"
                    class="px-4 py-2 text-sm font-medium text-white bg-green-500 rounded-md hover:bg-green-600">Ajukan
                    Barang Rusak</a>
            </div>

            <!-- Table -->
            <div class="border rounded-lg overflow-x-auto">
                <table class="min-w-full text-lg text-center items-center justify-center">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">ID Barang Rusak</th>
                            <th class="px-4 py-2">Penanggung Jawab</th>
                            <th class="px-4 py-2">Tanggal Rusak</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Proses</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                        @if ($staffBRusak->isEmpty())
                            <tr>
                                <td colspan="6" class="px-4 py-2 text-center">Tidak ada data barang rusak yang diajukan
                                    saat ini..</td>
                            </tr>
                        @endif
                        @foreach ($staffBRusak as $rusakStaff)
                            <tr>
                                <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2">{{ $rusakStaff->idBarangRusak }}</td>
                                <td class="px-4 py-2">{{ $rusakStaff->penanggungJawab }}</td>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($rusakStaff->tglRusak)->format('d M Y') }}</td>
                                <td class="px-4 py-2">
                                    @if ($rusakStaff->statusRusak == 2)
                                        <span class="text-yellow-500 font-semibold">Pending</span>
                                    @elseif ($rusakStaff->statusRusak == 1)
                                        <span class="text-green-500 font-semibold">Approved</span>
                                    @elseif ($rusakStaff->statusRusak == 0)
                                        <span class="text-red-500 font-semibold">Rejected</span>
                                    @else
                                        <span class="text-gray-500">Unknown</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 flex gap-1 text-lg text-center items-center justify-center">
                                    <a href="{{ route('detail.bRusak', ['idBarangRusak' => $rusakStaff->idBarangRusak]) }}"
                                        class="px-2 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded">Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Pagination -->
        {{ $bRusak->links() }}
    </div>


@endsection
