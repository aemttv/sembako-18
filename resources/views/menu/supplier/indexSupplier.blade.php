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
            <h1 class="text-2xl font-bold text-gray-800">Daftar Supplier</h1>
            <p class="text-sm text-gray-500 mt-1">Home > Supplier</p>
        </div>
        <div class="flex items-center gap-2 mt-4 sm:mt-0">
            <a href="/tambah-supplier" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition text-sm font-medium shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Supplier
            </a>
        </div>
    </div>

    {{-- Main Content Card --}}
    <div class="bg-white rounded-xl shadow-md border border-gray-200">
        {{-- Card Header with Search --}}
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <form action="{{ url('/suppliers/list-search') }}" method="GET" class="max-w-lg mx-auto">
                <label for="search" class="sr-only">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                         <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" name="q" id="search"
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="Cari Nama / ID Supplier..."
                           value="{{ request('q') }}">
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full text-md">
                <thead class="bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-md font-medium text-gray-200 uppercase tracking-wider">No.</th>
                        <th class="px-6 py-3 text-left text-md font-medium text-gray-200 uppercase tracking-wider">ID Supplier</th>
                        <th class="px-6 py-3 text-left text-md font-medium text-gray-200 uppercase tracking-wider">Nama Lengkap</th>
                        <th class="px-6 py-3 text-left text-md font-medium text-gray-200 uppercase tracking-wider">No. HP</th>
                        <th class="px-6 py-3 text-left text-md font-medium text-gray-200 uppercase tracking-wider">Alamat</th>
                        <th class="px-6 py-3 text-center text-md font-medium text-gray-200 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-md font-medium text-gray-200 uppercase tracking-wider">Proses</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($supplier as $index => $data)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $supplier->firstItem() + $index }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-500">{{ $data->idSupplier }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-semibold">{{ $data->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $data->nohp }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ mb_strimwidth($data->alamat, 0, 50, '...') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if ($data->status == 1)
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                @else
                                    <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button onclick="openEditModal('{{ $data->idSupplier }}')" data-id="{{ $data->idSupplier }}" class="px-4 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg text-sm font-semibold">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-16 text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                  <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197M15 21a6 6 0 006-5.197M15 15a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Data Supplier Tidak Ditemukan</h3>
                                <p class="mt-1 text-sm text-gray-500">Coba ubah kata kunci pencarian Anda atau tambahkan supplier baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($supplier->hasPages())
            <div class="p-4 sm:p-6 border-t border-gray-200">
                {{ $supplier->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center transition-opacity duration-300">
    <div class="bg-white rounded-xl p-6 w-full max-w-lg shadow-2xl m-4">
        <h3 class="text-lg font-bold text-gray-800 mb-6">Edit Data Supplier</h3>

        <form method="POST" enctype="multipart/form-data" id="editSupplierForm">
            @csrf
            <input type="hidden" id="editIdSupplier" name="idSupplier">
            <div class="space-y-4">
                <div>
                    <label for="editNama" class="block text-sm font-medium text-gray-700">Nama Supplier</label>
                    <input type="text" id="editNama" name="nama" class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" maxlength="100">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="editNoHp" class="block text-sm font-medium text-gray-700">No. HP</label>
                        <input type="text" id="editNoHp" name="nohp" maxlength="15" class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="editStatus" class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="editStatus" name="status" class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="editAlamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea id="editAlamat" name="alamat" maxlength="255" rows="3" class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 resize-none"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 text-sm font-medium">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium" id="saveChanges">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('javascript/supplier.js') }}"></script>
@endsection