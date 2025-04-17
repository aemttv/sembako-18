@extends('layout')

@section('content')
    <div class="">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Card Component -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border">
                <div class="bg-blue-100 px-6 py-3 text-blue-700 font-semibold border-b">Aktivitas - Barang Masuk</div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-700">
                        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Di Masukkan Oleh</th>
                                <th class="px-6 py-3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-3">12 Maret 2025</td>
                                <td class="px-6 py-3">Nadia Salsabila</td>
                                <td class="px-6 py-3">Susu UHT</td>
                            </tr>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-3">12 Maret 2025</td>
                                <td class="px-6 py-3">Nadia Salsabila</td>
                                <td class="px-6 py-3">Beras 5Kg</td>
                            </tr>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-3">13 Maret 2025</td>
                                <td class="px-6 py-3">Fadhil Aryansyah</td>
                                <td class="px-6 py-3">Minyak Goreng</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3">13 Maret 2025</td>
                                <td class="px-6 py-3">Fadhil Aryansyah</td>
                                <td class="px-6 py-3">Ballpoin 0.5</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Duplicate this block with changes for other logs -->

            <!-- Barang Keluar -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border">
                <div class="bg-red-100 px-6 py-3 text-red-700 font-semibold border-b">Aktivitas - Barang Keluar</div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-700">
                        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Di Keluarkan Oleh</th>
                                <th class="px-6 py-3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Reuse same rows -->
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-3">12 Maret 2025</td>
                                <td class="px-6 py-3">Nadia Salsabila</td>
                                <td class="px-6 py-3">Susu UHT</td>
                            </tr>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-3">12 Maret 2025</td>
                                <td class="px-6 py-3">Nadia Salsabila</td>
                                <td class="px-6 py-3">Beras 5Kg</td>
                            </tr>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-3">13 Maret 2025</td>
                                <td class="px-6 py-3">Fadhil Aryansyah</td>
                                <td class="px-6 py-3">Minyak Goreng</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3">13 Maret 2025</td>
                                <td class="px-6 py-3">Fadhil Aryansyah</td>
                                <td class="px-6 py-3">Ballpoin 0.5</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Barang Retur -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border">
                <div class="bg-yellow-100 px-6 py-3 text-yellow-700 font-semibold border-b">Aktivitas - Barang Retur</div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-700">
                        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Diajukan Oleh</th>
                                <th class="px-6 py-3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Same rows -->
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-3">12 Maret 2025</td>
                                <td class="px-6 py-3">Nadia Salsabila</td>
                                <td class="px-6 py-3">Susu UHT</td>
                            </tr>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-3">12 Maret 2025</td>
                                <td class="px-6 py-3">Nadia Salsabila</td>
                                <td class="px-6 py-3">Beras 5Kg</td>
                            </tr>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-3">13 Maret 2025</td>
                                <td class="px-6 py-3">Fadhil Aryansyah</td>
                                <td class="px-6 py-3">Minyak Goreng</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3">13 Maret 2025</td>
                                <td class="px-6 py-3">Fadhil Aryansyah</td>
                                <td class="px-6 py-3">Ballpoin 5Kg</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Barang Rusak -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border">
                <div class="bg-gray-200 px-6 py-3 text-gray-800 font-semibold border-b">Aktivitas - Barang Rusak</div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-700">
                        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Diajukan Oleh</th>
                                <th class="px-6 py-3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Same rows -->
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-3">12 Maret 2025</td>
                                <td class="px-6 py-3">Nadia Salsabila</td>
                                <td class="px-6 py-3">Susu UHT</td>
                            </tr>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-3">12 Maret 2025</td>
                                <td class="px-6 py-3">Nadia Salsabila</td>
                                <td class="px-6 py-3">Beras 5Kg</td>
                            </tr>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-3">13 Maret 2025</td>
                                <td class="px-6 py-3">Fadhil Aryansyah</td>
                                <td class="px-6 py-3">Minyak Goreng</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3">13 Maret 2025</td>
                                <td class="px-6 py-3">Fadhil Aryansyah</td>
                                <td class="px-6 py-3">Ballpoin 5Kg</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
