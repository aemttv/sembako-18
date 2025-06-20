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
                <h1 class="text-xl font-semibold">Tabel Produk</h1>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Home > Daftar Produk</p>
            </div>
        </div>


        <!-- Tabs -->
        <div class="flex justify-between items-center gap-2 border rounded-lg p-2 bg-white">
            <!-- Search Input Group -->
            <form action="{{ url('/daftar-produk/list-search') }}" method="GET" class="flex items-center bg-gray-50 border border-gray-200 rounded-xl px-3 w-[360px] shadow-sm mx-auto">
                <i class="fas fa-search text-gray-400 mr-2"></i>
                <input
                    type="text"
                    name="q"
                    placeholder="Nama Barang / ID Barang"
                    class="bg-transparent border-none focus:ring-0 focus:outline-none w-full text-sm text-gray-700 placeholder-gray-400"
                    value="{{ request('q') }}"
                />
            </form>
            <a href="/tambah-produk-form"
                class="px-4 py-2 text-sm font-medium text-white bg-green-500 rounded-md hover:bg-green-600">Tambah
                Produk</a>
            <!-- Trigger Buttons -->
            <button onclick="openModal('merekModal')"
                class="px-4 py-2 text-sm font-medium text-white bg-orange-500 rounded-md hover:bg-orange-600">
                Tambah Merek
            </button>

            <!-- Merek Modal -->
            <div id="merekModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-md p-6 w-full max-w-md">
                    <h2 class="text-lg font-semibold mb-4">Tambah Merek</h2>
                    <form action="/tambah-merek" method="POST">
                        @csrf
                        <input type="text" name="merekBaru" placeholder="Nama Merek"
                            class="w-full border p-2 rounded mb-4">
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="closeModal('merekModal')"
                                class="px-4 py-2 bg-gray-400 text-white rounded">Batal</button>
                            <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Table -->
        <div class="border rounded-lg overflow-x-auto">
            <table class="min-w-full text-lg text-center items-center">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2">ID Barang</th>
                        <th class="px-4 py-2">Nama Barang</th>
                        <th class="px-4 py-2">Merek</th>
                        <th class="px-4 py-2">Kategori</th>
                        <th class="px-4 py-2">Stok</th>
                        <th class="px-4 py-2">Kondisi</th>
                        <th class="px-4 py-2">Proses</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @if ($barang->isEmpty())
                        <td>
                            <td class="px-4 py-2 text-center" colspan="9">Data Barang tidak ditemukan.</td>
                        </td>
                    @endif
                    @foreach ($barang as $data)
                        <tr class="hover:bg-blue-50 even:bg-gray-50">
                            <td class="px-4 py-2">{{ $data->idBarang }}</td>
                            <td class="px-4 py-2 text-left">{{ mb_strimwidth($data->namaBarang, 0, 65, '...') }}</td>
                            <td class="px-4 py-2">{{ $data->merekBarangName }}</td>
                            <td class="px-4 py-2">{{ $data->kategoriBarang->namaKategori() ?? '-'}}</td>
                            <td class="px-4 py-2">{{ $data->totalStok }}</td>
                            <td class="px-4 py-2 
        @if ($data->kondisiBarangText === 'Baik') text-green-600
        @elseif ($data->kondisiBarangText === 'Mendekati Kadaluarsa') text-orange-500
        @elseif ($data->kondisiBarangText === 'Kadaluarsa') text-red-600
        @endif
    ">
        {{ $data->kondisiBarangText ?? '-' }}
    </td>
                            <td class="px-4 py-2 flex gap-1 justify-center ">
                                <a href="{{ route('detail.produk', ['idBarang' => $data->idBarang]) }}"
                                    class="px-2 py-1 bg-blue-500 text-white rounded text-xs">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        {{ $barang->links() }}
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
