@extends('layout')

@section('content')
<div class="p-6 lg:p-8 bg-gray-50 min-h-screen">

    {{-- Session Alerts --}}
    @if (session('success'))
        <x-ui.alert type="success" :message="session('success')" />
    @elseif (session('error'))
        <x-ui.alert type="error" :message="session('error')" />
    @endif

    {{-- Page Header & Actions --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Produk</h1>
            <p class="text-sm text-gray-500 mt-1">Home > Produk</p>
        </div>
        <div class="flex items-center gap-2 mt-4 sm:mt-0">
            <button onclick="openModal('merekModal')" class="inline-flex items-center px-4 py-2 bg-white hover:bg-gray-100 text-gray-700 rounded-lg transition text-sm font-medium shadow-sm border border-gray-300">
                Tambah Merek
            </button>
            <a href="/tambah-produk-form" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition text-sm font-medium shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Produk
            </a>
        </div>
    </div>

    {{-- Main Content Card --}}
    <div class="bg-white rounded-xl shadow-md border border-gray-200">
        {{-- Card Header with Search --}}
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <form action="{{ url('/daftar-produk/list-search') }}" method="GET" class="max-w-lg mx-auto">
                <label for="search" class="sr-only">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                         <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" name="q" id="search"
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="Cari Nama / ID Barang..."
                           value="{{ request('q') }}">
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full text-md">
                <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-md font-medium text-gray-200 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-3 text-left text-md font-medium text-gray-200 uppercase tracking-wider">Merek</th>
                        <th class="px-6 py-3 text-left text-md font-medium text-gray-200 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-center text-md font-medium text-gray-200 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-3 text-center text-md font-medium text-gray-200 uppercase tracking-wider">Kondisi</th>
                        <th class="px-6 py-3 text-center text-md font-medium text-gray-200 uppercase tracking-wider">Proses</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($barang as $data)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">{{ mb_strimwidth($data->namaBarang, 0, 65, '...') }}</div>
                                <div class="text-sm text-gray-500">ID: {{ $data->idBarang }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $data->merekBarangName }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ optional($data->kategoriBarang)->namaKategori() ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-gray-700 font-medium">{{ $data->totalStok }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if ($data->kondisiBarangText === 'Baik')
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $data->kondisiBarangText }}
                                    </span>
                                @elseif ($data->kondisiBarangText === 'Mendekati Kadaluarsa')
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        {{ $data->kondisiBarangText }}
                                    </span>
                                @elseif ($data->kondisiBarangText === 'Kadaluarsa')
                                     <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        {{ $data->kondisiBarangText }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ $data->kondisiBarangText ?? '-' }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <a href="{{ route('detail.produk', ['idBarang' => $data->idBarang]) }}" class="px-4 py-1.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-semibold shadow-sm">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-16 text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                  <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Data Produk Tidak Ditemukan</h3>
                                <p class="mt-1 text-sm text-gray-500">Coba ubah kata kunci pencarian Anda atau tambahkan produk baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($barang->hasPages())
            <div class="p-4 sm:p-6 border-t border-gray-200">
                {{ $barang->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Merek Modal  -->
<div id="merekModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
    <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-2xl m-4">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Tambah Merek Baru</h2>
        <form action="/tambah-merek" method="POST">
            @csrf
            <div>
                <label for="merekBaru" class="block text-sm font-medium text-gray-700">Nama Merek</label>
                <input type="text" name="merekBaru" id="merekBaru" placeholder="Contoh: Indofood"
                       class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('merekModal')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 text-sm font-medium">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">Simpan Merek</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
</script>
@endsection