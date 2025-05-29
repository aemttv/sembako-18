@extends('layout')

@section('content')
    <div class="p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Barang Masuk -->
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
                            @forelse ($barangMasukLogs as $log)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-3">
                                        {{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('d F Y') }}</td>
                                    <td class="px-6 py-3">{{ $log->akun->nama ?? '-' }}</td>
                                    <td class="px-6 py-3"> Barang Masuk </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-3 text-center text-gray-400">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

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
                            @forelse ($barangKeluarLogs as $log)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-3">
                                        {{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('d F Y') }}</td>
                                    <td class="px-6 py-3">{{ $log->akun->nama ?? '-' }}</td>
                                    <td class="px-6 py-3">
                                        @php
                                            $firstDetail = $log->detailKeluar->first();
                                        @endphp
                                        {{ $firstDetail?->kategoriAlasan?->alasan() ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-3 text-center text-gray-400">Tidak ada data</td>
                                </tr>
                            @endforelse
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
                            @forelse ($barangReturLogs as $log)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-3">
                                        {{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('d F Y') }}</td>
                                    <td class="px-6 py-3">{{ $log->akun->nama ?? '-'  }}</td>
                                    <td class="px-6 py-3">
                                        @php
                                            $firstDetail = $log->detailRetur->first();
                                        @endphp
                                        {{ $firstDetail?->kategoriAlasan?->alasan() ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-3 text-center text-gray-400">Tidak ada data</td>
                                </tr>
                            @endforelse
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
                            @forelse ($barangRusakLogs as $log)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-3">
                                        {{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('d F Y') }}</td>
                                    <td class="px-6 py-3">{{$log->akun->nama ?? '-' }}</td>
                                    <td class="px-6 py-3">
                                        @php
                                            $firstDetail = $log->detailRusak->first();
                                        @endphp
                                        {{ $firstDetail?->kategoriAlasan?->alasan() ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-3 text-center text-gray-400">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
