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
                    <h1 class="text-xl font-semibold">Konfirmasi Retur Barang</h1>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Home > Retur Barang</p>
                </div>
            </div>


            <!-- Tabs -->
            <div class="flex items-center justify-between border rounded-lg p-2 bg-white">
                <!-- Centered Search Form -->
                <div class="flex-1 flex justify-center">
                    <form action="{{ route('bRetur.search') }}" method="GET"
                        class="flex items-center bg-gray-50 border border-gray-200 rounded-xl px-3 w-[360px] shadow-sm h-11">
                        <i class="fas fa-search text-gray-400 mr-2"></i>
                        <input type="text" name="q" placeholder="ID Retur / ID Supplier / Penanggung Jawab..."
                            value="{{ request('q') }}"
                            class="bg-transparent border-none focus:ring-0 focus:outline-none w-full text-sm text-gray-700 placeholder-gray-400 h-full" />
                    </form>
                </div>
                <!-- Button Far Right -->
                <a href="/ajukan-retur"
                    class="px-4 py-2 text-sm font-medium text-white bg-green-500 rounded-md hover:bg-green-600 h-11 flex items-center ml-4">
                    Ajukan Retur Barang
                </a>
            </div>


            <!-- Table -->
            <div class="border rounded-lg overflow-x-auto">
                <table class="min-w-full text-lg text-center items-center">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">ID Retur</th>
                            <th class="px-4 py-2">ID Supplier</th>
                            <th class="px-4 py-2">Penanggung Jawab</th>
                            <th class="px-4 py-2">Tanggal Retur</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Proses</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">

                        @if ($bRetur->isEmpty())
                            <tr>
                                <td class="px-4 py-2 border-b text-center" colspan="8">Tidak ada barang yang diajukan
                                    saat ini..</td>
                            </tr>
                        @endif
                        @foreach ($bRetur as $retur)
                            <tr class="hover:bg-blue-50 even:bg-gray-50">
                                <td class="px-4 py-2 border-b">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2 border-b">{{ $retur->idBarangRetur }}</td>
                                <td class="px-4 py-2 border-b">{{ explode(' ', trim($retur->supplier->nama))[0] }} ({{ $retur->idSupplier ?? 'N/A' }})</td>
                                <td class="px-4 py-2 border-b"> {{ explode(' ', trim($retur->akun->nama))[0] }} ({{ $retur->penanggungJawab ?? 'N/A' }}) </td>
                                <td class="px-4 py-2 border-b">
                                    {{ \Carbon\Carbon::parse($retur->tglRetur)->format('d M Y') }}
                                </td>
                                <td class="px-4 py-2 border-b">
                                    @if ($retur->statusRetur == 2)
                                        <span class="text-yellow-500 font-semibold">Pending</span>
                                    @elseif ($retur->statusRetur == 1)
                                        <span class="text-green-500 font-semibold">Approved</span>
                                    @elseif ($retur->statusRetur == 0)
                                        <span class="text-red-500 font-semibold">Rejected</span>
                                    @else
                                        <span class="text-gray-500">Unknown</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 border-b flex gap-1 items-center justify-center">
                                    <a href="{{ route('detail.bRetur', ['idBarangRetur' => $retur->idBarangRetur]) }}"
                                        class="px-2 py-1 bg-blue-500 text-white rounded text-xs leading-5 h-8">Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            {{ $bRetur->links() }}
        @else
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div class="flex-1">
                    <h1 class="text-xl font-semibold">Halaman Retur Barang</h1>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Home > Retur Barang</p>
                </div>
            </div>

            <!-- Tabs -->
            <div class="flex items-center justify-between border rounded-lg p-2 bg-white">
                <!-- Centered Search Form -->
                <div class="flex-1 flex justify-center">
                    <form action="{{ route('bRetur.search') }}" method="GET"
                        class="flex items-center bg-gray-50 border border-gray-200 rounded-xl px-3 w-[360px] shadow-sm h-11">
                        <i class="fas fa-search text-gray-400 mr-2"></i>
                        <input type="text" name="q" placeholder="ID Retur / ID Supplier / Penanggung Jawab..."
                            value="{{ request('q') }}"
                            class="bg-transparent border-none focus:ring-0 focus:outline-none w-full text-sm text-gray-700 placeholder-gray-400 h-full" />
                    </form>
                </div>
                <!-- Button Far Right -->
                <a href="/ajukan-retur"
                    class="px-4 py-2 text-sm font-medium text-white bg-green-500 rounded-md hover:bg-green-600 h-11 flex items-center ml-4">
                    Ajukan Retur Barang
                </a>
            </div>
            <!-- Table -->
            <div class="border rounded-lg overflow-x-auto">
                <table class="min-w-full text-lg text-center items-center">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">ID Retur</th>
                            <th class="px-4 py-2">Supplier</th>
                            <th class="px-4 py-2">Penanggung Jawab</th>
                            <th class="px-4 py-2">Tanggal Retur</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Proses</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">

                        @if ($staffBRetur->isEmpty())
                            <tr>
                                <td class="px-4 py-2 border-b text-center" colspan="8">Tidak ada barang yang diajukan
                                    saat ini..</td>
                            </tr>
                        @endif
                        @foreach ($staffBRetur as $retur)
                            <tr>
                                <td class="px-4 py-2 border-b">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2 border-b">{{ $retur->idBarangRetur }}</td>
                                <td class="px-4 py-2 border-b">{{ $retur->supplier->nama }} ({{ $retur->idSupplier ?? 'N/A' }})</td>
                                <td class="px-4 py-2 border-b">
                                    {{ $retur->akun->nama ?? 'N/A' }} ({{ $retur->penanggungJawab ?? 'N/A' }})
                                </td>
                                <td class="px-4 py-2 border-b">
                                    {{ \Carbon\Carbon::parse($retur->tglRetur)->format('d M Y') }}
                                </td>
                                <td class="px-4 py-2 border-b">
                                    @if ($retur->statusRetur == 2)
                                        <span class="text-yellow-500 font-semibold">Pending</span>
                                    @elseif ($retur->statusRetur == 1)
                                        <span class="text-green-500 font-semibold">Approved</span>
                                    @elseif ($retur->statusRetur == 0)
                                        <span class="text-red-500 font-semibold">Rejected</span>
                                    @else
                                        <span class="text-gray-500">Unknown</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 border-b flex gap-1 items-center justify-center">
                                    <a href="{{ route('detail.bRetur', ['idBarangRetur' => $retur->idBarangRetur]) }}"
                                        class="px-2 py-1 bg-blue-500 text-white rounded text-xs">Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            {{ $staffBRetur->links() }}
        @endif

    </div>
@endsection
