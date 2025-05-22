@extends('layout')

@section('content')
    <div class="p-6 space-y-4">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div class="flex-1">
                <h1 class="text-xl font-semibold">Tabel Akun</h1>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Home > Manajemen Akun</p>
            </div>
        </div>
        @if (session('success'))
            <x-ui.alert type="success" :message="session('success')" />
        @elseif (session('error'))
            <x-ui.alert type="error" :message="session('error')" />
        @endif

        <!-- Tabs -->
        <div class="flex justify-between items-center gap-2 border rounded-lg p-2 bg-white">

            <!-- Search Input Group -->
            <div class="flex items-center bg-gray-50 border border-gray-200 rounded-xl px-3 w-[360px] shadow-sm mx-auto">
                <i class="fas fa-search text-gray-400 mr-2"></i>
                <input type="text" placeholder="Search or type command..."
                    class="bg-transparent border-none focus:ring-0 focus:outline-none w-full text-sm text-gray-700 placeholder-gray-400" />
            </div>
            <a href="/tambah-akun"
                class="px-4 py-2 text-sm font-medium text-white bg-green-500 rounded-md hover:bg-green-600">Tambah Akun</a>
        </div>


        <!-- Table -->
        <div class="border rounded-lg overflow-x-auto">
            <table class="min-w-full text-lg text-left">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2">ID Akun</th>
                        <th class="px-4 py-2">Nama Lengkap</th>
                        <th class="px-4 py-2">No.HP</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Peran</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Proses</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @if ($akun->isEmpty())
                        <tr>
                            <td class="px-4 py-2 border-b text-center" colspan="8">Data akun tidak ditemukan.</td>
                        </tr>
                    @endif
                    @foreach ($akun as $data)
                        <tr>
                            <td class="px-4 py-2">{{ $data->idAkun }}</td>
                            <td class="px-4 py-2">{{ $data->nama }}</td>
                            <td class="px-4 py-2">{{ $data->nohp }}</td>
                            <td class="px-4 py-2">{{ $data->email }}</td>
                            <td class="px-4 py-2">{{ $data->peran == 1 ? 'Owner' : 'Staff' }}</td>
                            <td class="px-4 py-2">{{ $data->statusAkun == 1 ? 'Aktif' : 'Tidak Aktif' }}</td>
                            <td class="px-4 py-2 flex gap-1">
                                <!-- Edit Button -->
                                <button onclick="openEditModal('<?= $data->idAkun ?>')"
                                    class="px-2 py-1 bg-blue-500 text-white rounded text-xs">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        {{ $akun->links() }}

        <!-- The Modal -->
        <!-- Modal -->
<div id="editModal" class="fixed hidden inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-full max-w-md">
        <h3 class="text-lg font-bold mb-4">Edit Akun</h3>
        
        <form id="editForm" action="{{ route('akun.update' , ['idAkun' => $data->idAkun]) }}" method="POST">
            @csrf
            <input type="hidden" id="editIdAkun">
            
            <!-- Row 1: Nama and Password -->
            <div class="flex gap-4 mb-4">
                <div class="flex-1">
                    <label class="block text-gray-700 mb-2">Nama</label>
                    <input type="text" id="editNama" class="w-full px-3 py-2 border rounded">
                </div>
                <div class="flex-1">
                    <label class="block text-gray-700 mb-2">Password</label>
                    <input type="password" id="editPassword" class="w-full px-3 py-2 border rounded" placeholder="Kosongkan jika tidak diubah">
                </div>
            </div>
            
            <!-- Row 2: No HP and Email -->
            <div class="flex gap-4 mb-4">
                <div class="flex-1">
                    <label class="block text-gray-700 mb-2">No HP</label>
                    <input type="text" id="editNoHp" class="w-full px-3 py-2 border rounded">
                </div>
                <div class="flex-1">
                    <label class="block text-gray-700 mb-2">Email</label>
                    <input type="email" id="editEmail" class="w-full px-3 py-2 border rounded">
                </div>
            </div>
            
            <!-- Row 3: Peran and Status -->
            <div class="flex gap-4 mb-4">
                <div class="flex-1">
                    <label class="block text-gray-700 mb-2">Peran</label>
                    <select id="editPeran" class="w-full px-3 py-2 border rounded">
                        <option value="1">Owner</option>
                        <option value="2">Staff</option>
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-gray-700 mb-2">Status</label>
                    <select id="editStatus" class="w-full px-3 py-2 border rounded">
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>
                </div>
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()" 
                        class="px-4 py-2 bg-gray-300 rounded">
                    Batal
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-500 text-white rounded">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

    </div>

    <script src="{{ asset('javascript/akun.js') }}"></script>

    
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
