<!-- Top Navbar -->
<header class="bg-white shadow px-4 py-3 flex justify-between items-center">
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

        <!-- Notification icon with red dot -->
        <button class="relative w-14 h-14 flex items-center justify-center rounded-full border hover:bg-gray-100">
            <i class="fas fa-bell text-gray-500"></i>
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-orange-500 rounded-full"></span>
        </button>

        <!-- Profile -->
        <div class="flex items-center gap-2">
            <img src="https://i.pravatar.cc/100?img=12" alt="Profile"
                class="w-14 h-14 rounded-full object-cover">
            <span class="text-xl text-gray-800 font-medium">Adam</span>
            <i class="fas fa-chevron-down text-xs text-gray-600"></i>
        </div>
    </div>
</header>
