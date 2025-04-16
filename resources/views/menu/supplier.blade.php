@extends('layout')

@section('content')
    <div class="p-6 space-y-4">

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
            <div class="flex items-center bg-gray-50 border border-gray-200 rounded-xl px-3 w-[360px] shadow-sm mx-auto">
                <i class="fas fa-search text-gray-400 mr-2"></i>
                <input type="text" placeholder="Search or type command..."
                    class="bg-transparent border-none focus:ring-0 focus:outline-none w-full text-sm text-gray-700 placeholder-gray-400" />
            </div>
            <a href="/tambah-produk" class="px-4 py-2 text-sm font-medium text-white bg-green-500 rounded-md hover:bg-green-600">Tambah Supplier</a>
        </div>


        <!-- Table -->
        <div class="border rounded-lg overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2">ID Supplier</th>
                        <th class="px-4 py-2">Nama Lengkap</th>
                        <th class="px-4 py-2">No.HP</th>
                        <th class="px-4 py-2">Alamat</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Proses</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    {{-- @foreach ($suppliers as $supplier) --}}
                        <tr>
                            <td class="px-4 py-2">S0001</td>
                            <td class="px-4 py-2">Dewi Purnamasari</td>
                            <td class="px-4 py-2">085298765433</td>
                            <td class="px-4 py-2">Jl. Mayjen Sungkono No.34, Surabaya</td>
                            <td class="px-4 py-2">Aktif</td>
                            <td class="px-4 py-2 flex gap-1">
                                <a href="#" class="px-2 py-1 bg-blue-500 text-white rounded text-xs">Edit</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2">S0002</td>
                            <td class="px-4 py-2">Natalia Anggraini</td>
                            <td class="px-4 py-2">082234567891</td>
                            <td class="px-4 py-2">Jl. Manyar kertoarjo no.77, Surabaya</td>
                            <td class="px-4 py-2">Aktif</td>
                            <td class="px-4 py-2 flex gap-1">
                                <a href="#" class="px-2 py-1 bg-blue-500 text-white rounded text-xs">Edit</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2">S0003</td>
                            <td class="px-4 py-2">Siti Rahmawati</td>
                            <td class="px-4 py-2">082198765432</td>
                            <td class="px-4 py-2">Jl. Ahmad Yani no.23, Surabaya</td>
                            <td class="px-4 py-2">Aktif</td>
                            <td class="px-4 py-2 flex gap-1">
                                <a href="#" class="px-2 py-1 bg-blue-500 text-white rounded text-xs">Edit</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2">S0004</td>
                            <td class="px-4 py-2">Arif Pratama</td>
                            <td class="px-4 py-2">082234567890</td>
                            <td class="px-4 py-2">Jl. Gubeng Kertajaya No.10, Surabaya</td>
                            <td class="px-4 py-2">Tidak Aktif</td>
                            <td class="px-4 py-2 flex gap-1">
                                <a href="#" class="px-2 py-1 bg-blue-500 text-white rounded text-xs">Edit</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2">S0005</td>
                            <td class="px-4 py-2">Indra Mahardika</td>
                            <td class="px-4 py-2">087643218765</td>
                            <td class="px-4 py-2">Jl. Rungkut Industri No. 88, Surabaya</td>
                            <td class="px-4 py-2">Tidak Aktif</td>
                            <td class="px-4 py-2 flex gap-1">
                                <a href="#" class="px-2 py-1 bg-blue-500 text-white rounded text-xs">Edit</a>
                            </td>
                        </tr>
                        
                    {{-- @endforeach --}}
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center text-sm text-gray-800">
            <p>Showing 1 to 10 of 59 entries</p>
            <div class="flex gap-1">
                <button class="px-3 py-1 border rounded">Previous</button>
                <button class="px-3 py-1 border rounded bg-blue-200">1</button>
                <button class="px-3 py-1 border rounded">2</button>
                <button class="px-3 py-1 border rounded">3</button>
                <button class="px-3 py-1 border rounded">4</button>
                <button class="px-3 py-1 border rounded">Next</button>
            </div>
        </div>
    </div>

@endsection
