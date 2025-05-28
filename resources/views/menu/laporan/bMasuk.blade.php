@extends('layout')

@section('content')
    <div class="p-6 space-y-4">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div class="flex-1">
                <h1 class="text-xl font-semibold">Laporan Barang Masuk</h1>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Home > Laporan Barang Masuk</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex items-start sm:items-end gap-4 border rounded-lg p-4 bg-white flex-wrap">
            <!-- Main Filter Form (Dates & Tampilkan) -->
            <form id="laporanForm" action="{{ route('laporan.bMasuk.search') }}" method="get"
                class="flex items-end gap-4 flex-wrap">
                <!-- Tanggal Mulai -->
                <div class="flex flex-col">
                    <label for="tanggal_awal" class="text-sm text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="tanggal_awal" id="tanggal_awal"
                        class="border border-gray-300 rounded-md px-3 py-1.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300 @error('tanggal_awal') border-red-500 @enderror"
                        placeholder="Tanggal Awal" value="{{ request('tanggal_awal', '') }}"
                        max="{{ now()->addYear()->format('Y-m-d') }}" />
                    @error('tanggal_awal')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Tanggal Akhir -->
                <div class="flex flex-col">
                    <label for="tanggal_akhir" class="text-sm text-gray-700 mb-1">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" id="tanggal_akhir"
                        class="border border-gray-300 rounded-md px-3 py-1.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300 @error('tanggal_akhir') border-red-500 @enderror"
                        placeholder="Tanggal Akhir" value="{{ request('tanggal_akhir', '') }}" />
                    @error('tanggal_akhir')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Tampilkan Button -->
                <div class="flex flex-col justify-end">
                    <button type="submit"
                        class="px-4 py-1.5 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 h-[42px]">
                        Tampilkan
                    </button>
                </div>
            </form>

            <!-- PDF Button Form -->
            <form id="pdfForm" action="{{ route('streamPDF.bMasuk.view') }}" method="post"
                class="flex flex-col justify-end">
                @csrf
                <input type="hidden" name="tanggal_awal" id="tanggal_awal_pdf"
                    value="{{ request('tanggal_awal', now()->format('Y-m-d')) }}">
                <input type="hidden" name="tanggal_akhir" id="tanggal_akhir_pdf"
                    value="{{ request('tanggal_akhir', now()->addMonth()->format('Y-m-d')) }}">
                <input type="hidden" name="search" id="search_pdf" value="{{ request('search', '') }}">
                <button type="submit"
                    class="px-4 py-1.5 text-sm font-medium text-white bg-green-500 rounded-md hover:bg-green-600 h-[42px] flex items-center gap-2">
                    <i class="fa-regular fa-file-pdf"></i>
                    View PDF
                </button>
            </form>

            <div class="flex flex-col justify-end">
                <a href="{{route('laporan.bMasuk.view')}}" class="px-4 py-1.5 text-sm font-medium text-white bg-pink-500 rounded-md hover:bg-pink-600 h-[42px] text-center justify-center items-center"> Reset Filter </a>
            </div>

            <!-- Search Form -->
            <div class="flex-1 flex justify-end">
                <form id="searchForm" action="{{ route('laporan.bMasuk.search') }}" method="get"
                    class="w-full sm:w-[280px]">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" placeholder="Nama Barang / ID Barang" name="search" id="search_main"
                            class="block w-full pl-10 pr-3 py-1.5 border border-gray-300 rounded-xl leading-5 bg-gray-50 shadow-sm placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm h-[42px]"
                            value="{{ request('search', '') }}" />
                    </div>
                    <input type="hidden" name="tanggal_awal" id="tanggal_awal_search_hidden"
                        value="{{ request('tanggal_awal', now()->format('Y-m-d')) }}">
                    <input type="hidden" name="tanggal_akhir" id="tanggal_akhir_search_hidden"
                        value="{{ request('tanggal_akhir', now()->addMonth()->format('Y-m-d')) }}">
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="border rounded-lg overflow-x-auto">
            <table class="min-w-full text-md justify-center items-center text-center">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">Nama Barang</th>
                        <th class="px-4 py-2">ID Barang</th>
                        <th class="px-4 py-2">ID Supplier</th>
                        <th class="px-4 py-2">ID Akun</th>
                        <th class="px-4 py-2">Kuantitas</th>
                        <th class="px-4 py-2">Harga Satuan (Rp)</th>
                        <th class="px-4 py-2">Subtotal</th>
                        <th class="px-4 py-2">Tanggal Masuk</th>
                        <th class="px-4 py-2">Tanggal Kadaluarsa</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @php
                        $totalDetails = 0;
                        foreach ($bMasuk as $data) {
                            $totalDetails += $data->detailMasuk->count();
                        }
                    @endphp
                    @if ($totalDetails === 0)
                        <tr>
                            <td colspan="10" class="px-4 py-8 text-center text-gray-500">Data tidak ditemukan</td>
                        </tr>
                    @else
                    @php $no = ($bMasuk->currentPage() - 1) * $bMasuk->perPage() + 1; @endphp
                    @foreach ($bMasuk as $data)
                        @foreach ($data->detailMasuk as $detail)
                            <tr>
                                <td class="px-4 py-2">{{ $no++ }}</td>
                                <td class="px-4 py-2 text-left">
                                    {{ $detail->barangDetail->barang->namaBarang ?? 'Nama Barang Tidak Ditemukan' }}</td>
                                <td class="px-4 py-2">{{ $detail->idBarang }}</td>
                                <td class="px-4 py-2">{{ $data->idSupplier }}</td>
                                <td class="px-4 py-2">{{ $data->idAkun }}</td>
                                <td class="px-4 py-2">{{ $detail->jumlahMasuk }}</td>
                                <td class="px-4 py-2 text-right">Rp.{{ number_format($detail->hargaBeli, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-2 text-right">Rp.{{ number_format($detail->subtotal, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ \Carbon\Carbon::parse($data->tglMasuk)->translatedFormat('d F Y') }}</td>
                                <td class="px-4 py-2">
                                    {{ \Carbon\Carbon::parse($detail->tglKadaluarsa)->translatedFormat('d F Y') }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($bMasuk->hasPages())
            <div class="mt-4">
                {{ $bMasuk->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tanggalAwalInput = document.getElementById('tanggal_awal');
            const tanggalAkhirInput = document.getElementById('tanggal_akhir');
            const searchInput = document.getElementById('search_main'); // Assuming this is your main search input

            // Hidden fields for PDF form
            const tanggalAwalPdfHidden = document.getElementById('tanggal_awal_pdf');
            const tanggalAkhirPdfHidden = document.getElementById('tanggal_akhir_pdf');
            const searchPdfHidden = document.getElementById('search_pdf');

            // Hidden fields for Search form (for dates)
            const tanggalAwalSearchHidden = document.getElementById('tanggal_awal_search_hidden');
            const tanggalAkhirSearchHidden = document.getElementById('tanggal_akhir_search_hidden');

            function updateMinTanggalAkhir() {
                if (tanggalAwalInput.value) {
                    // Set min date for tanggal_akhir to be the day after tanggal_awal
                    let minDate = new Date(tanggalAwalInput.value);
                    minDate.setDate(minDate.getDate() + 1); // Must be at least one day after
                    tanggalAkhirInput.min = minDate.toISOString().split('T')[0];
                } else {
                    // If tanggal_awal is cleared, remove min constraint from tanggal_akhir
                    tanggalAkhirInput.min = '';
                }
            }

            function validateDateRange() {
                // Always remove red border first, then re-add if condition met
                tanggalAkhirInput.classList.remove('border-red-500');
                // Also remove from tanggal_awal if you were highlighting it for other reasons
                // tanggalAwalInput.classList.remove('border-red-500');


                const awalValue = tanggalAwalInput.value;
                const akhirValue = tanggalAkhirInput.value;

                if (awalValue && akhirValue) {
                    const dateAwal = new Date(awalValue);
                    const dateAkhir = new Date(akhirValue);

                    // Highlight tanggal_akhir if tanggal_awal is on or after tanggal_akhir
                    if (dateAwal >= dateAkhir) {
                        tanggalAkhirInput.classList.add('border-red-500');
                    }
                }
            }

            function syncHiddenForms() {
                const awalVal = tanggalAwalInput.value;
                const akhirVal = tanggalAkhirInput.value;
                const searchVal = searchInput ? searchInput.value : '';

                if (tanggalAwalPdfHidden) tanggalAwalPdfHidden.value = awalVal;
                if (tanggalAkhirPdfHidden) tanggalAkhirPdfHidden.value = akhirVal;
                if (searchPdfHidden) searchPdfHidden.value = searchVal;

                if (tanggalAwalSearchHidden) tanggalAwalSearchHidden.value = awalVal;
                if (tanggalAkhirSearchHidden) tanggalAkhirSearchHidden.value = akhirVal;
            }


            // Initial setup
            updateMinTanggalAkhir();
            validateDateRange();
            syncHiddenForms();


            // Event Listeners
            if (tanggalAwalInput) {
                tanggalAwalInput.addEventListener('change', function() {
                    updateMinTanggalAkhir();
                    validateDateRange();
                    syncHiddenForms();
                });
            }

            if (tanggalAkhirInput) {
                tanggalAkhirInput.addEventListener('change', function() {
                    validateDateRange();
                    syncHiddenForms();
                });
            }

            if (searchInput) {
                searchInput.addEventListener('input', syncHiddenForms);
            }

            // Sync on form submit just in case
            const laporanForm = document.getElementById('laporanForm');
            if (laporanForm) {
                laporanForm.addEventListener('submit', syncHiddenForms);
            }
            const pdfForm = document.getElementById('pdfForm');
            if (pdfForm) {
                pdfForm.addEventListener('submit', syncHiddenForms);
            }
            const searchForm = document.getElementById('searchForm');
            if (searchForm) {
                // For the search form, ensure its own hidden date inputs are specifically updated
                // from the main date pickers before it submits.
                searchForm.addEventListener('submit', function() {
                    if (tanggalAwalSearchHidden) tanggalAwalSearchHidden.value = tanggalAwalInput.value;
                    if (tanggalAkhirSearchHidden) tanggalAkhirSearchHidden.value = tanggalAkhirInput.value;
                    // searchInput.value is already part of this form
                });
            }

        });
    </script>
@endsection
