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
            <h1 class="text-2xl font-bold text-gray-800">Daftar Barang Masuk</h1>
            <p class="text-sm text-gray-500 mt-1">Home > Pembelian</p>
        </div>
        <div class="flex items-center gap-2 mt-4 sm:mt-0">
            <a href="/barang-masuk" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition text-sm font-medium shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Barang Masuk
            </a>
        </div>
    </div>

    {{-- Main Content Card --}}
    <div class="bg-white rounded-xl shadow-md border border-gray-200">
        {{-- Card Header with Search --}}
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <form action="{{ url('/barang-masuk/list-search') }}" method="GET" class="max-w-lg mx-auto">
                <label for="search" class="sr-only">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                         <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" name="q" id="search"
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           placeholder="Cari ID Transaksi / ID Supplier..."
                           value="{{ request('q') }}">
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Transaksi</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Dicatat Oleh</th>
                        <th class="px-6 py-3 text-right text-sm font-medium text-gray-500 uppercase tracking-wider">Grand Total</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider">Nota</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-500 uppercase tracking-wider">Proses</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    
                    @php
                        $transactions = isOwner() ? $bMasuk : $bMasukStaff;
                    @endphp

                    @forelse ($transactions as $data)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">#{{ $data->idBarangMasuk }}</div>
                                <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($data->tglMasuk)->translatedFormat('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">{{ optional($data->supplier)->nama ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">ID: {{ $data->idSupplier }}</div>
                            </td>
                             <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">{{ optional($data->akun)->nama ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">ID: {{ $data->idAkun }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-semibold text-gray-800">
                                Rp{{ number_format($data->grand_total, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if ($data->nota)
                                    <button onclick="showNotaModal('{{ asset('nota_file/' . $data->nota) }}')" class="px-4 py-1.5 bg-orange-100 text-orange-800 hover:bg-orange-200 rounded-lg text-sm font-semibold">
                                        Lihat Nota
                                    </button>
                                @else
                                    <span class="text-sm text-gray-400 italic">Tidak ada</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <a href="{{ route('detail.bMasuk', ['idBarangMasuk' => $data->idBarangMasuk]) }}" class="px-4 py-1.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-semibold shadow-sm">
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
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Data Pembelian Tidak Ditemukan</h3>
                                <p class="mt-1 text-sm text-gray-500">Coba ubah kata kunci pencarian Anda atau tambahkan data baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 sm:p-6 border-t border-gray-200">
            @if (isOwner())
                {{ $bMasuk->links() }}
            @else
                {{ $bMasukStaff->links() }}
            @endif
        </div>
    </div>
</div>

<!-- Nota Modal -->
<div id="notaModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 transition-opacity duration-300">
    <div class="bg-white rounded-xl p-4 w-full max-w-2xl shadow-2xl m-4 relative">
        <img id="notaImage" src="" alt="Nota Image" class="max-w-full h-auto mx-auto rounded-lg" style="max-height: 85vh;">
        <button onclick="closeNotaModal()" class="absolute -top-3 -right-3 text-white bg-gray-800 rounded-full h-8 w-8 flex items-center justify-center hover:bg-red-600 transition-colors">×</button>
    </div>
</div>

<script>
    function showNotaModal(imageUrl) {
        document.getElementById('notaImage').src = imageUrl;
        document.getElementById('notaModal').classList.remove('hidden');
    }

    function closeNotaModal() {
        document.getElementById('notaModal').classList.add('hidden');
        document.getElementById('notaImage').src = '';
    }
</script>
@endsection