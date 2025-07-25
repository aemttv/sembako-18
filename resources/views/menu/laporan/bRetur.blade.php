@extends('layout')

@section('content')
    <div class="p-6 space-y-4">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div class="flex-1">
                <h1 class="text-xl font-semibold">Laporan Retur Barang</h1>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Home > Laporan Retur Barang</p>
            </div>
        </div>

        <!-- Tabs -->

        <div
            class="flex flex-col sm:flex-row items-stretch sm:items-end gap-4 border rounded-lg p-4 bg-white flex-wrap w-full">
            <!-- Filter & Actions Form (Dates & Tampilkan) -->
            <form action="{{ route('laporan.bRetur.search') }}" method="get"
                class="flex flex-col sm:flex-row items-stretch sm:items-end gap-4 w-full sm:w-auto sm:flex-wrap">
                <!-- Tanggal Mulai -->
                <div class="flex flex-col w-full sm:w-auto">
                    <label for="tanggal_awal" class="text-sm text-gray-700 mb-1 text-center sm:text-left">Tanggal
                        Mulai</label>
                    <input type="date" name="tanggal_awal" id="tanggal_awal"
                        class="border border-gray-300 rounded-md px-3 py-1.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300 @error('tanggal_awal') border-red-500 @enderror w-full"
                        placeholder="Tanggal Awal" value="{{ request('tanggal_awal', '') }}"
                        max="{{ now()->addYear()->format('Y-m-d') }}" />
                    @error('tanggal_awal')
                        <span class="text-red-500 text-xs mt-1 text-center sm:text-left">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Tanggal Akhir -->
                <div class="flex flex-col w-full sm:w-auto">
                    <label for="tanggal_akhir" class="text-sm text-gray-700 mb-1 text-center sm:text-left">Tanggal
                        Akhir</label>
                    <input type="date" name="tanggal_akhir" id="tanggal_akhir"
                        class="border border-gray-300 rounded-md px-3 py-1.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300 @error('tanggal_akhir') border-red-500 @enderror w-full"
                        placeholder="Tanggal Akhir" value="{{ request('tanggal_akhir', '') }}" />
                    @error('tanggal_akhir')
                        <span class="text-red-500 text-xs mt-1 text-center sm:text-left">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Tampilkan Button -->
                <div class="flex flex-col justify-end w-full sm:w-auto">
                    <button type="submit"
                        class="px-4 py-1.5 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 h-[42px] w-full sm:w-auto">
                        Tampilkan
                    </button>
                </div>
            </form>

            <!-- PDF Button Form -->
            <form id="pdfForm" action="{{ route('streamPDF.bRetur.view') }}" method="post"
                class="flex flex-col justify-end w-full sm:w-auto">
                @csrf
                <input type="hidden" name="tanggal_awal" value="{{ request('tanggal_awal', '') }}">
                <input type="hidden" name="tanggal_akhir" value="{{ request('tanggal_akhir', '') }}">
                <input type="hidden" name="search" value="{{ request('search', '') }}">
                <button type="submit"
                    class="px-4 py-1.5 text-sm font-medium text-white bg-green-500 rounded-md hover:bg-green-600 h-[42px] flex items-center justify-center gap-2 w-full sm:w-auto">
                    <i class="fa-regular fa-file-pdf"></i>
                    View PDF
                </button>
            </form>

            <!-- Reset Filter Link/Button -->
            <div class="flex flex-col justify-end w-full sm:w-auto">
                <a href="{{ route('laporan.bRetur.view') }}"
                    class="px-4 py-1.5 text-sm font-medium text-white bg-pink-500 rounded-md hover:bg-pink-600 h-[42px] flex items-center justify-center w-full sm:w-auto">
                    Reset Filter </a>
            </div>

            <!-- Search Input Wrapper (for right alignment on desktop) -->
            <div class="w-full sm:flex-1 sm:flex sm:justify-end">
                <form action="{{ route('laporan.bRetur.search') }}" method="get" class="w-full sm:w-[280px]">
                    <div class="flex items-center bg-gray-50 border border-gray-200 rounded-xl px-3 shadow-sm h-[42px]">
                        <i class="fas fa-search text-gray-400 mr-2"></i>
                        <input type="text" placeholder="Nama Barang / ID Barang" name="search"
                            class="bg-transparent border-none focus:ring-0 focus:outline-none w-full text-sm text-gray-700 placeholder-gray-400 h-full"
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
                        <th class="px-4 py-2">ID Retur</th>
                        <th class="px-4 py-2">Supplier</th>
                        <th class="px-4 py-2">Staff</th>
                        <th class="px-4 py-2">Barcode</th>
                        <th class="px-4 py-2">Nama Barang</th>
                        <th class="px-4 py-2">Kuantitas</th>
                        <th class="px-4 py-2">Keterangan</th>
                        <th class="px-4 py-2">Tanggal Retur</th>
                        <th class="px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @php
                        $totalDetails = 0;
                        foreach ($details as $data) {
                            $totalDetails += $data->count();
                        }
                    @endphp
                    @if ($totalDetails === 0)
                        <tr>
                            <td colspan="10" class="px-4 py-8 text-center text-gray-500">Data tidak ditemukan</td>
                        </tr>
                    @else
                        @php $no = ($details->currentPage() - 1) * $details->perPage() + 1; @endphp
                        @foreach ($details as $detail)
                            <tr class="hover:bg-blue-50 even:bg-gray-50" >
                                <td class="px-4 py-2">{{ $no++ }}</td>
                                <td class="px-4 py-2">{{ $detail->returBarang->idBarangRetur ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $detail->returBarang->supplier->nama ?? '-' }} ({{ $detail->returBarang->idSupplier }})</td>
                                <td class="px-4 py-2">{{ $detail->returBarang->akun->nama ?? '-' }} ({{ $detail->returBarang->penanggungJawab }})</td>
                                <td class="px-4 py-2">{{ $detail->barcode ?? '-' }}</td>
                                <td class="px-4 py-2  text-left">
                                    {{ $detail->detailBarangRetur->barang->namaBarang ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    {{ $detail->jumlah ?? '-' }}
                                    @if ($detail->detailBarangRetur->barang->satuan->namaSatuan() == 'pcs/eceran')
                                        Pcs
                                    @elseif($detail->detailBarangRetur->barang->satuan->namaSatuan() == 'kg')
                                        Kg
                                    @elseif($detail->detailBarangRetur->barang->satuan->namaSatuan() == 'dus')
                                        Dus
                                    @endif
                                </td>
                                <td>{{ $detail->kategoriAlasan->alasan() ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    {{ \Carbon\Carbon::parse($detail->returBarang->tglRetur)->translatedFormat('d F Y') ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    @if ($detail->statusReturDetail == 2)
                                        <span class="text-yellow-500 font-semibold">Pending</span>
                                    @elseif ($detail->statusReturDetail == 1)
                                        <span class="text-green-500 font-semibold">Approved</span>
                                    @elseif ($detail->statusReturDetail == 0)
                                        <span class="text-red-500 font-semibold">Rejected</span>
                                    @else
                                        <span class="text-gray-500">Unknown</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        {{ $details->links() }}
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
                pdfForm.addEventListener('submit', function(e) {
                    const totalDetails = {{ $totalDetails ?? 0 }};
                    if (totalDetails === 0) {
                        e.preventDefault();
                        alert('Tidak ada data untuk diunduh sebagai PDF.');
                        return; // Don't proceed to syncHiddenForms
                    }
                    syncHiddenForms(e); // Only call if data exists
                });
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
