@php
    use Illuminate\Support\Facades\Request;
@endphp

<!-- Hamburger Menu Button (for mobile) -->
<button id="mobileMenuButton"
    class="fixed top-4 left-4 z-50 p-2 bg-gray-500 text-white rounded-md md:hidden hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
    aria-label="Toggle sidebar" aria-expanded="false">
    <svg id="menuIcon" class="h-6 w-6 block" stroke="currentColor" fill="none" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
    <svg id="closeIcon" class="h-6 w-6 hidden" stroke="currentColor" fill="none" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
    </svg>
</button>

<!-- Overlay (for mobile when menu is open) -->
<div id="sidebarOverlay" class="fixed inset-0 z-30 bg-gray-600 bg-opacity-50 md:hidden hidden" aria-hidden="true"></div>

<!-- Sidebar -->
<aside id="sidebar"
    class="w-80 shadow-md fixed top-0 bottom-0 left-0 z-40 transform -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0">
    <div class="flex flex-col justify-between h-full border-e border-gray-100 bg-white">
        <div> <!-- Container for logo and menu items -->
            <div class="px-4 py-2"> <!-- Logo area -->
                <a href="/dashboard" class="flex justify-center">
                    <img src="{{ asset('assets/images/logo_1.jpg') }}"
                        class="grid h-40 w-60 place-content-center rounded-lg bg-gray-100 text-xs text-gray-600"></img>
                </a>
            </div>
            <ul class="mt-6 space-y-1 overflow-y-auto px-2" style="max-height: calc(100vh - 240px);"> {{-- Adjusted max-height, added px-2 for consistent padding with headers --}}
                <h2 class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wide">Menu</h2>
                <ul class="mt-2 space-y-1">
                    <li>
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
                        $stokOpen = Request::is('barang-masuk') || Request::is('barang-keluar') || Request::is('daftar-barang-masuk') || Request::is('daftar-barang-keluar');
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
                        {{ Request::is('daftar-barang-masuk') || Request::is('barang-masuk') ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">
                                        Barang Masuk
                                    </a>
                                </li>
                                <li>
                                    <a href="/daftar-barang-keluar"
                                        class="block rounded-lg px-4 py-2 pl-8 pr-4 text-sm font-medium
                        {{ Request::is('daftar-barang-keluar') || Request::is('barang-keluar') ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">
                                        Barang Keluar
                                    </a>
                                </li>
                            </ul>
                        </details>
                    </li>

                    @php
                        $inventoryCareOpen = Request::is('retur-barang') || Request::is('barang-rusak') || Request::is('konfirmasi-retur') || Request::is('konfirmasi-rusak');
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
                    {{ Request::is('konfirmasi-retur') || Request::is('retur-barang') ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">
                                        Retur Barang
                                    </a>
                                </li>

                                <li>
                                    <a href="/konfirmasi-rusak"
                                        class="block rounded-lg px-4 py-2 pl-8 pr-4 text-sm font-medium
                    {{ Request::is('konfirmasi-rusak') || Request::is('barang-rusak') ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">
                                        Barang Rusak
                                    </a>
                                </li>
                            </ul>
                        </details>
                    </li>

                    @if (isOwner()) {{-- Assuming isOwner() is a helper function you have --}}
                        @php
                            $laporanOpen =
                                Request::is('laporan-stok') ||
                                Request::is('laporan-barang-masuk') ||
                                Request::is('laporan-barang-keluar') ||
                                Request::is('laporan-retur-barang') ||  // Corrected this
                                Request::is('laporan-barang-rusak');
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
                                    <li>
                                        <a href="/laporan-barang-retur" {{-- Assuming this is the correct URL --}}
                                            class="block rounded-lg px-4 py-2 pl-8 pr-4 text-sm font-medium
                {{ Request::is('laporan-retur-barang') ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">
                                            L.Retur Barang
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/laporan-barang-rusak"
                                            class="block rounded-lg px-4 py-2 pl-8 pr-4 text-sm font-medium
                {{ Request::is('laporan-barang-rusak') ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">
                                            L.Barang Rusak
                                        </a>
                                    </li>
                                </ul>
                            </details>
                        </li>


                        <h2 class="px-2 pt-4 text-xs font-semibold text-gray-400 uppercase tracking-wide">Account</h2> {{-- Added px-2 to align with menu item padding --}}
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

                        <h2 class="px-2 pt-4 text-xs font-semibold text-gray-400 uppercase tracking-wide">Others</h2> {{-- Added px-2 to align --}}
                        <ul class="mt-2 space-y-1">
                            {{-- <li>
                                <a href="/backup"
                                    class="flex items-center gap-3 rounded-lg pl-6 pr-4 py-2 text-sm font-medium
                                            {{ Request::is('backup') ? 'bg-gray-100 text-gray-700' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700' }}">
                                    <i class="fas fa-scroll w-3 {{ Request::is('backup') ? 'text-gray-700' : 'text-gray-500' }}"></i>
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
                </ul>
            </ul>
        </div>

        <div class="sticky inset-x-0 bottom-0 border-t border-gray-100 bg-white p-4 flex items-center gap-4">
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
    // Clock script
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

        const timeEl = document.getElementById('current-time');
        const dateEl = document.getElementById('current-date');
        if (timeEl) timeEl.textContent = time;
        if (dateEl) dateEl.textContent = date;
    }

    updateClock(); // Initial call
    setInterval(updateClock, 1000); // Update every second

    // Sidebar toggle script
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const menuIcon = document.getElementById('menuIcon');
        const closeIcon = document.getElementById('closeIcon');

        function openSidebar() {
            if (sidebar && sidebarOverlay && mobileMenuButton && menuIcon && closeIcon) {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                sidebarOverlay.classList.remove('hidden');
                mobileMenuButton.setAttribute('aria-expanded', 'true');
                menuIcon.classList.add('hidden');
                menuIcon.classList.remove('block');
                closeIcon.classList.remove('hidden');
                closeIcon.classList.add('block');
            }
        }

        function closeSidebar() {
            if (sidebar && sidebarOverlay && mobileMenuButton && menuIcon && closeIcon) {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
                sidebarOverlay.classList.add('hidden');
                mobileMenuButton.setAttribute('aria-expanded', 'false');
                menuIcon.classList.remove('hidden');
                menuIcon.classList.add('block');
                closeIcon.classList.add('hidden');
                closeIcon.classList.remove('block');
            }
        }

        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', () => {
                // Check if sidebar is currently open by looking at overlay or transform class
                const isOpen = sidebar && !sidebar.classList.contains('-translate-x-full');
                if (isOpen) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });
        }
        
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', closeSidebar);
        }

        // Optional: Close sidebar on 'Escape' key press
        document.addEventListener('keydown', function(event) {
            const isOpen = sidebar && !sidebar.classList.contains('-translate-x-full');
            if (event.key === 'Escape' && isOpen) {
                closeSidebar();
            }
        });
    });
</script>