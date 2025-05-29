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
                <h1 class="text-xl font-semibold">Tabel Supplier</h1>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Home > Daftar Supplier</p>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex justify-between items-center gap-2 border rounded-lg p-2 bg-white">
            <!-- Search Input Group -->
            <form action="{{ url('/suppliers/list-search') }}" method="GET"
                class="flex items-center bg-gray-50 border border-gray-200 rounded-xl px-3 w-[360px] shadow-sm mx-auto">
                <i class="fas fa-search text-gray-400 mr-2"></i>
                <input type="text" name="q" placeholder="Nama Supplier / ID Supplier" value="{{ request('q') }}"
                    class="bg-transparent border-none focus:ring-0 focus:outline-none w-full text-sm text-gray-700 placeholder-gray-400" />
            </form>
            <a href="/tambah-supplier"
                class="px-4 py-2 text-sm font-medium text-white bg-green-500 rounded-md hover:bg-green-600">Tambah
                Supplier</a>
        </div>


        <!-- Table -->
        <div class="border rounded-lg overflow-x-auto">
            <table class="min-w-full text-lg text-center">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2">No.</th>
                        <th class="px-4 py-2">ID Supplier</th>
                        <th class="px-4 py-2">Nama Lengkap</th>
                        <th class="px-4 py-2">No.HP</th>
                        <th class="px-4 py-2">Alamat</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Proses</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @if ($supplier->isEmpty())
                        <td class="px-4 py-2 text-center" colspan="7">Data Supplier tidak ditemukan.</td>
                    @endif
                    @foreach ($supplier as $index => $data)
                        <tr class="hover:bg-blue-50 even:bg-gray-50">
                            <td class="px-4 py-2">{{ $supplier->firstItem() + $index }}</td>
                            <td class="px-4 py-2">{{ $data->idSupplier }}</td>
                            <td class="px-4 py-2 text-left">{{ $data->nama }}</td>
                            <td class="px-4 py-2">{{ $data->nohp }}</td>
                            <td class="px-4 py-2">{{ mb_strimwidth($data->alamat, 0, 50, '...') }}</td>
                            <td class="px-4 py-2">{{ $data->status == 1 ? 'Aktif' : 'Tidak Aktif' }}</td>
                            <td class="px-4 py-2 text-center">
                                <!-- Edit Button -->
                                <button onclick="openEditModal('{{ $data->idSupplier }}')" data-id="{{ $data->idSupplier }}"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        {{ $supplier->links() }}
    </div>

    <!-- Modal -->
    <div id="editModal" class="fixed hidden inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-full max-w-md">
            <h3 class="text-lg font-bold mb-4">Edit Akun</h3>

            <form method="POST" enctype="multipart/form-data" id="editSupplierForm">
                @csrf
                <input type="hidden" id="editIdSupplier" name="idSupplier">

                <!-- Row 1: Nama and Password -->
                <div class="flex gap-4 mb-4">
                    <div class="flex-1">
                        <label class="block text-gray-700 mb-2">Nama</label>
                        <input type="text" id="editNama" name="nama" class="w-full px-3 py-2 border rounded"
                            maxlength="100">
                    </div>
                </div>

                <!-- Row 2: No HP and Email -->
                <div class="flex gap-4 mb-4">
                    <div class="flex-1">
                        <label class="block text-gray-700 mb-2">No HP</label>
                        <input type="text" id="editNoHp" name="nohp" maxlength="15"
                            class="w-full px-3 py-2 border rounded">
                        <!-- Example: max 20 characters for phone number -->
                    </div>

                    <div class="flex-1">
                        <label class="block text-gray-700 mb-2">Status</label>
                        <select id="editStatus" class="w-full px-3 py-2 border rounded" name="status">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>

                <!-- Row 3: Peran and Status -->
                <div class="flex gap-4 mb-4">
                    <div class="flex-1">
                        <label class="block text-gray-700 mb-2">Alamat</label>
                        <textarea id="editAlamat" name="alamat" maxlength="255" class="w-full px-3 py-2 border rounded resize-none"
                            style="max-height: 100px; min-height: 40px;">{{ old('alamat', $alamat ?? '') }}</textarea>
                        <!-- Max 255 characters, max height 100px -->
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded" id="saveChanges">
                        Simpan
                    </button>
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 rounded">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('javascript/supplier.js') }}"></script>

    <style>
        /* Simple transition for the modal */
        #editModal {
            transition: opacity 0.3s ease;
        }

        #editModal:not(.hidden) {
            display: flex;
        }
    </style>
@endsection
