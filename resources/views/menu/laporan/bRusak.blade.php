@extends('layout')

@section('content')
    <div class="p-6 space-y-4">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div class="flex-1">
                <h1 class="text-xl font-semibold">Laporan Barang Rusak</h1>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Home > Laporan Barang Rusak</p>
            </div>
        </div>


        <!-- Tabs -->

        <div class="flex items-end gap-4 border rounded-lg p-4 bg-white flex-wrap">
            <!-- Filter & Actions -->
            <form action="{{ route('laporan.bRusak.search') }}" method="get" class="flex items-end gap-4 flex-wrap">
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
            <!-- PDF Button -->
            <form action="{{ route('streamPDF.bRusak.view') }}" method="post" class="flex flex-col justify-end">
                @csrf
                <input type="hidden" name="tanggal_awal" value="{{ request('tanggal_awal', '') }}">
                <input type="hidden" name="tanggal_akhir" value="{{ request('tanggal_akhir', '') }}">
                <input type="hidden" name="search" value="{{ request('search', '') }}">
                <button type="submit"
                    class="px-4 py-1.5 text-sm font-medium text-white bg-green-500 rounded-md hover:bg-green-600 h-[42px] flex items-center gap-2">
                    <i class="fa-regular fa-file-pdf"></i>
                    View PDF
                </button>
            </form>

             <div class="flex flex-col justify-end">
                <a href="{{route('laporan.bRusak.view')}}" class="px-4 py-1.5 text-sm font-medium text-white bg-pink-500 rounded-md hover:bg-pink-600 h-[42px] text-center justify-center items-center"> Reset Filter </a>
            </div>

            <!-- Search Input (Right aligned) -->
            <div class="flex-1 flex justify-end">
                <form action="{{ route('laporan.bRusak.search') }}" method="get" class="w-[280px]">
                    <div class="flex items-center bg-gray-50 border border-gray-200 rounded-xl px-3 shadow-sm">
                        <i class="fas fa-search text-gray-400 mr-2"></i>
                        <input type="text" placeholder="Nama Barang / ID Barang" name="search"
                            class="bg-transparent border-none focus:ring-0 focus:outline-none w-full text-sm text-gray-700 placeholder-gray-400"
                            value="{{ request('search', '') }}" />
                    </div>
                    <!-- Keep the date filters in sync when searching -->
                    <input type="hidden" name="tanggal_awal" value="{{ request('tanggal_awal', '') }}">
                    <input type="hidden" name="tanggal_akhir" value="{{ request('tanggal_akhir', '') }}">
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="border rounded-lg overflow-x-auto">
            <table class="min-w-full text-md justify-center items-center text-center">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">Invoice</th>
                        <th class="px-4 py-2">ID Barang</th>
                        <th class="px-4 py-2">Nama Barang</th>
                        <th class="px-4 py-2">Kuantitas</th>
                        <th class="px-4 py-2">Subtotal</th>
                        <th class="px-4 py-2">Kategori Keterangan</th>
                        <th class="px-4 py-2">Tanggal Keluar</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @php
                        $totalDetails = 0;
                        foreach ($bRusak as $data) {
                            $totalDetails += $data->detailRusak->count();
                        }
                    @endphp
                    @if ($totalDetails === 0)
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-500">Data tidak ditemukan</td>
                        </tr>
                    @else
                    @php $no = 1; @endphp
                    @foreach ($bRusak as $data)
                        @foreach ($data->detailRusak as $detail)
                            <tr>
                                <td class="px-4 py-2">{{ $no++ }}</td>
                                <td class="px-4 py-2">{{ $data->invoice }}</td>
                                <td class="px-4 py-2">{{ $detail->idBarang }}</td>
                                <td class="px-4 py-2  text-left">{{ $detail->barang->namaBarang }}</td>
                                <td class="px-4 py-2">{{ $detail->jumlahKeluar }}</td>
                                <td class="px-4 py-2 text-right">Rp.{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                <td>{{ $detail->kategoriAlasan->alasan() }}</td>
                                <td class="px-4 py-2"> {{ \Carbon\Carbon::parse($data->tglKeluar)->translatedFormat('d F Y') }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center text-sm text-gray-800">
            {{ $bRusak->links() }}
        </div>
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
