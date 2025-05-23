@php
    use Illuminate\Support\Facades\Request;
@endphp

<!-- Sidebar -->
<aside class="w-80 shadow-md">
    <div class="fixed top-0 bottom-0 flex flex-col justify-between border-e border-gray-100 bg-white w-80">
        <div class="px-4 py-2">
            <div class="flex justify-center">
                <img src="{{ asset('assets/images/logo_1.jpg') }}"
                    class="grid h-40 w-60 place-content-center rounded-lg bg-gray-100 text-xs text-gray-600"></img>
            </div>

            <ul class="mt-6 space-y-1">
                <h2 class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wide">Menu</h2>
                <ul class="mt-2 space-y-1">
                    <li>
                        {{-- Request::is, to check whether it is on that url or not --}}
                        <a href="/dashboard"
                            class="flex items-center gap-3 rounded-lg pl-6 pr-4 py-2 text-sm font-medium
                                {{ Request::is('dashboard') ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">
                            <i
                                class="fas fa-tachometer-alt w-3 {{ Request::is('dashboard') ? 'text-gray-700' : 'text-gray-500' }}"></i>
                            Dashboard
                        </a>
                    </li>

                    <li>
                        <a href="/daftar-produk"
                            class="flex items-center gap-3 rounded-lg pl-6 pr-4 py-2 text-sm font-medium
                                {{ Request::is('daftar-produk') ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">
                            <i
                                class="fas fa-box w-3 {{ Request::is('daftar-produk') ? 'text-gray-700' : 'text-gray-500' }}"></i>
                            Daftar Produk
                        </a>
                    </li>
                    <li>
                        <a href="/daftar-supplier"
                            class="flex items-center gap-3 rounded-lg pl-6 pr-4 py-2 text-sm font-medium
                                {{ Request::is('daftar-supplier') ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">
                            <i
                                class="fas fa-truck w-3 {{ Request::is('daftar-supplier') ? 'text-gray-700' : 'text-gray-500' }}"></i>
                            Daftar Supplier
                        </a>
                    </li>

                    @php
                        $stokOpen = Request::is('barang-masuk') || Request::is('barang-keluar');
                    @endphp

                    <li>
                        <details class="group [&_summary::-webkit-details-marker]:hidden" {{ $stokOpen ? 'open' : '' }}>
                            <summary
                                class="flex cursor-pointer items-center justify-between rounded-lg px-4 py-2 pl-6 pr-4
                {{ $stokOpen ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">

                                <span class="flex items-center gap-3 text-sm font-medium">
                                    <i
                                        class="fas fa-warehouse w-3 {{ $stokOpen ? 'text-gray-700' : 'text-gray-500' }}"></i>
                                    Manajemen Stok
                                </span>

                                <span class="shrink-0 transition duration-300 group-open:-rotate-180">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </summary>

                            <ul class="mt-2 space-y-1 px-4">
                                <li>
                                    <a href="/daftar-barang-masuk"
                                        class="block rounded-lg px-4 py-2 pl-8 pr-4 text-sm font-medium
                        {{ Request::is('daftar-barang-masuk') ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">
                                        Barang Masuk
                                    </a>
                                </li>
                                <li>
                                    <a href="/daftar-barang-keluar"
                                        class="block rounded-lg px-4 py-2 pl-8 pr-4 text-sm font-medium
                        {{ Request::is('barang-keluar') ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">
                                        Barang Keluar
                                    </a>
                                </li>
                                {{-- <li>
                                    <a href="/barang-masuk"
                                        class="block rounded-lg px-4 py-2 pl-8 pr-4 text-sm font-medium
                        {{ Request::is('barang-masuk') ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">
                                        Form Barang Masuk
                                    </a>
                                </li>
                                <li>
                                    <a href="/barang-keluar"
                                        class="block rounded-lg px-4 py-2 pl-8 pr-4 text-sm font-medium
                        {{ Request::is('barang-keluar') ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">
                                        Form Barang Keluar
                                    </a>
                                </li> --}}
                            </ul>
                        </details>
                    </li>

                    @php
                        $inventoryCareOpen = Request::is('retur-barang') || Request::is('barang-rusak');
                    @endphp

                    <li>
                        <details class="group [&_summary::-webkit-details-marker]:hidden"
                            {{ $inventoryCareOpen ? 'open' : '' }}>
                            <summary
                                class="flex cursor-pointer items-center justify-between rounded-lg px-4 py-2 pl-6 pr-4
                {{ $inventoryCareOpen ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">

                                <span class="flex items-center gap-3 text-sm font-medium">
                                    <i
                                        class="fas fa-undo-alt w-3 {{ $inventoryCareOpen ? 'text-gray-700' : 'text-gray-500' }}"></i>
                                    Inventory Care
                                </span>

                                <span class="shrink-0 transition duration-300 group-open:-rotate-180">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </summary>

                            <ul class="mt-2 space-y-1 px-4">
                                <li>
                                    <a href="/konfirmasi-retur"
                                        class="block rounded-lg px-4 py-2 pl-8 pr-4 text-sm font-medium
                    {{ Request::is('retur-barang') ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">
                                        Retur Barang
                                    </a>
                                </li>

                                <li>
                                    <a href="/konfirmasi-rusak"
                                        class="block rounded-lg px-4 py-2 pl-8 pr-4 text-sm font-medium
                    {{ Request::is('barang-rusak') ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">
                                        Barang Rusak
                                    </a>
                                </li>
                            </ul>
                        </details>
                    </li>

                    @if (isOwner())
                        @php
                            $laporanOpen =
                                Request::is('laporan-stok') ||
                                Request::is('laporan-barang-masuk') ||
                                Request::is('laporan-barang-keluar');
                        @endphp

                        <li>
                            <details class="group [&_summary::-webkit-details-marker]:hidden"
                                {{ $laporanOpen ? 'open' : '' }}>
                                <summary
                                    class="flex cursor-pointer items-center justify-between rounded-lg px-4 py-2 pl-6 pr-4
            {{ $laporanOpen ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">

                                    <span class="flex items-center gap-3 text-sm font-medium">
                                        <i
                                            class="fas fa-chart-line w-3 {{ $laporanOpen ? 'text-gray-700' : 'text-gray-500' }}"></i>
                                        Laporan
                                    </span>

                                    <span class="shrink-0 transition duration-300 group-open:-rotate-180">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </summary>

                                <ul class="mt-2 space-y-1 px-4">
                                    <li>
                                        <a href="/laporan-stok"
                                            class="block rounded-lg px-4 py-2 pl-8 pr-4 text-sm font-medium
                {{ Request::is('laporan-stok') ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">
                                            L.Stok
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/laporan-barang-masuk"
                                            class="block rounded-lg px-4 py-2 pl-8 pr-4 text-sm font-medium
                {{ Request::is('laporan-barang-masuk') ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">
                                            L.Barang Masuk
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/laporan-barang-keluar"
                                            class="block rounded-lg px-4 py-2 pl-8 pr-4 text-sm font-medium
                {{ Request::is('laporan-barang-keluar') ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">
                                            L.Barang Keluar
                                        </a>
                                    </li>
                                </ul>
                            </details>
                        </li>
                </ul>


                <h2 class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wide">Account</h2>
                <ul class="mt-2 space-y-1">
                    <li>
                        <a href="/daftar-akun"
                            class="flex items-center gap-3 rounded-lg pl-6 pr-4 py-2 text-sm font-medium
                                    {{ Request::is('daftar-akun') ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">
                            <i
                                class="fas fa-user-cog w-3 {{ Request::is('daftar-akun') ? 'text-gray-700' : 'text-gray-500' }}"></i>
                            Manajemen Akun
                        </a>
                    </li>
                </ul>

                <h2 class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wide">Others</h2>
                <ul class="mt-2 space-y-1">
                    {{-- <li>
                            <a href="/backup-database"
                                class="flex items-center gap-3 rounded-lg pl-6 pr-4 py-2 text-sm font-medium
                                    {{ Request::is('backup-database') ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">
                                <i
                                    class="fas fa-database w-3 {{ Request::is('backup-database') ? 'text-gray-700' : 'text-gray-500' }}"></i>
                                Backup Database
                            </a>
                        </li> --}}
                    <li>
                        <a href="/log"
                            class="flex items-center gap-3 rounded-lg pl-6 pr-4 py-2 text-sm font-medium
                                    {{ Request::is('log') ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">
                            <i
                                class="fas fa-scroll w-3 {{ Request::is('log') ? 'text-gray-700' : 'text-gray-500' }}"></i>
                            Log
                        </a>
                    </li>
                </ul>
                @endif
        </div>

        <div class="sticky inset-x-0 bottom-0 border-t border-gray-100 bg-white p-4 flex items-center gap-4">
            {{-- <img alt="Clock Icon"
                src="https://cdn-icons-png.flaticon.com/512/2920/2920244.png"
                class="size-10 rounded-full object-cover" /> --}}
            <img alt="Clock Icon" src="{{ asset('assets/images/clock.jpg') }}"
                class="size-10 rounded-full object-cover" />

            <div>
                <p class="text-sm font-medium text-gray-800" id="current-time">--:--:--</p>
                <p class="text-xs text-gray-500" id="current-date">Loading date...</p>
            </div>
        </div>

    </div>
</aside>

<script>
    function updateClock() {
        const now = new Date();
        const time = now.toLocaleTimeString('en-US', {
            hour12: false
        });
        const date = now.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        document.getElementById('current-time').textContent = time;
        document.getElementById('current-date').textContent = date;
    }

    updateClock(); // Initial call
    setInterval(updateClock, 1000); // Update every second
</script>
