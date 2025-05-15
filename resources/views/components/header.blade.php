<!-- Top Navbar -->

<header class="fixed top-0 left-80 right-0  bg-white shadow px-4 py-3 flex justify-between items-center z-10">
    <!-- Left section: Menu + Search -->
    <div class="flex items-center gap-3">
        <!-- Menu Button -->
        <button class="border rounded-lg p-4">
            <i class="fas fa-bars text-gray-600"></i>
        </button>

        <!-- Search Input Group -->
        <div class="flex items-center bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 w-[360px] shadow-sm">
            <i class="fas fa-search text-gray-400 mr-2"></i>
            <input type="text" placeholder="Search or type command..."
                class="bg-transparent border-none focus:ring-0 focus:outline-none w-full text-sm text-gray-700 placeholder-gray-400" />
        </div>
    </div>

    <!-- Right section: Dark mode, Notification, Profile -->
    <div class="flex items-center gap-4">

        <!-- Notification Bell with Dropdown -->
        <div class="relative inline-block" id="notification-wrapper">
            <!-- Button -->
            <button id="notification-toggle"
                class="relative w-14 h-14 flex items-center justify-center rounded-full border hover:bg-gray-100 focus:outline-none">
                <i class="fas fa-bell text-gray-500"></i>
                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-orange-500 rounded-full"></span>
            </button>

            <!-- Dropdown Menu -->
            <div id="notification-dropdown"
                class="hidden absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-lg z-50 overflow-hidden">
                <div class="p-4 border-b font-medium text-gray-700">Notifications</div>
                <ul class="max-h-60 overflow-y-auto divide-y divide-gray-100 text-sm">
                    <!-- Dummy Notifications -->
                    <li class="px-4 py-3 hover:bg-gray-50 cursor-pointer">
                        <p class="text-gray-700">New item added: <span class="font-semibold">Susu UHT</span></p>
                        <span class="text-xs text-gray-400">2 minutes ago</span>
                    </li>
                    <li class="px-4 py-3 hover:bg-gray-50 cursor-pointer">
                        <p class="text-gray-700">Item returned: <span class="font-semibold">Ballpoint 0.5</span></p>
                        <span class="text-xs text-gray-400">10 minutes ago</span>
                    </li>
                    <li class="px-4 py-3 hover:bg-gray-50 cursor-pointer">
                        <p class="text-gray-700">Item damaged: <span class="font-semibold">Beras 5kg</span></p>
                        <span class="text-xs text-gray-400">1 hour ago</span>
                    </li>
                    <li class="px-4 py-3 hover:bg-gray-50 cursor-pointer">
                        <p class="text-gray-700">Stock low on <span class="font-semibold">Minyak Goreng</span></p>
                        <span class="text-xs text-gray-400">3 hours ago</span>
                    </li>
                </ul>
                <div class="text-center text-sm p-2 text-blue-500 hover:underline cursor-pointer">View all</div>
            </div>
        </div>
        @if(session('user_logged_in'))
            <!-- Profile Section -->
            <div class="relative inline-block" id="profile-container">
                <!-- Clickable Profile Area -->
                <div id="profile-toggle" class="flex items-center gap-2 cursor-pointer">
                    {{-- <img src="{{ asset('storage/profile-images/' . session('user_data')->foto) }}" alt="Profile" class="w-14 h-14 rounded-full object-cover"> --}}
                    <span class="text-xl text-gray-800 font-medium">{{ session('user_data')->nama }}</span>
                    <i class="fas fa-chevron-down text-xs text-gray-600"></i>
                </div>
                <!-- Dropdown Menu -->
                <div id="dropdown-menu"
                    class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                    <ul class="text-gray-700 text-sm">
                        <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Profile</li>
                        <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Settings</li>
                        <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer" onclick="window.location.href='{{ route('logout') }}'">Logout</li>
                    </ul>
                </div>
            </div>
        @endif


    </div>
    <!-- JavaScript -->
    <script>
        const profileToggle = document.getElementById('profile-toggle');
        const dropdownMenu = document.getElementById('dropdown-menu');

        document.addEventListener('click', function(event) {
            const isClickInside = document.getElementById('profile-container').contains(event.target);

            if (isClickInside) {
                dropdownMenu.classList.toggle('hidden');
            } else {
                dropdownMenu.classList.add('hidden');
            }
        });
    </script>

    <!-- JavaScript -->
    <script>
        const notifBtn = document.getElementById('notification-toggle');
        const notifDropdown = document.getElementById('notification-dropdown');
        const notifWrapper = document.getElementById('notification-wrapper');

        document.addEventListener('click', function(event) {
            const isClickInside = notifWrapper.contains(event.target);

            if (isClickInside) {
                notifDropdown.classList.toggle('hidden');
            } else {
                notifDropdown.classList.add('hidden');
            }
        });
    </script>
</header>
