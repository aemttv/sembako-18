<!-- Top Navbar -->
<header
    class="fixed top-0 left-0 md:left-80 right-0 shadow px-4 py-3 flex justify-between items-center z-10 bg-white border-b border-gray-200">
    
    <!-- Left section: (Can be empty or for a mobile brand/title) -->
    <div>
        <!-- Example: <span class="text-lg font-semibold md:hidden">Your App</span> -->
        <!-- This div ensures the right section is pushed to the far right -->
    </div>

    <!-- Right section: Notification, Profile -->
    <div class="flex items-center gap-x-2 sm:gap-x-4"> <!-- Adjusted gap for mobile -->

        <!-- Notification Bell with Dropdown -->
        <div class="relative inline-block" id="notification-wrapper">
            @php
                $hasUnread = isset($unreadNotifications) && $unreadNotifications->count();
            @endphp
            <!-- Button -->
            <button id="notification-toggle"
                class="relative w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center rounded-full border hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-bell text-gray-500 text-lg"></i>
                @if($hasUnread)
                    <span id="notif-dot" class="absolute top-1 right-1 w-2 h-2 bg-orange-500 rounded-full ring-1 ring-white"></span>
                @endif
            </button>

            <!-- Dropdown Menu -->
            <div id="notification-dropdown"
                class="hidden absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-lg shadow-lg z-50 overflow-hidden">
                <div class="p-4 border-b font-medium text-gray-700 flex items-center justify-between">
                    <span class="text-base">Notifications</span>
                    @if (isset($globalNotifications) && $globalNotifications->count())
                        <form action="{{ route('notifications.clear') }}" method="POST"
                            onsubmit="return confirm('Clear all notifications?');" class="h-full flex items-center">
                            @csrf
                            <button type="submit"
                                class="text-xs text-red-500 hover:text-red-700 h-full py-0 px-2 leading-none flex items-center"
                                title="Clear All Notifications">
                                Clear All
                            </button>
                        </form>
                    @endif
                </div>

                <ul class="max-h-60 overflow-y-auto divide-y divide-gray-100 text-sm">
                    @if (isset($globalNotifications) && $globalNotifications->count())
                        @foreach ($globalNotifications as $notif)
                            <li class="px-4 py-3 hover:bg-gray-50 {{-- cursor-pointer --}}"> {{-- Removed cursor-pointer if not clickable directly --}}
                                <p class="text-gray-700">
                                    {{ $notif->title }}
                                    @php
                                        $data = is_array($notif->data) ? $notif->data : json_decode($notif->data, true);
                                    @endphp
                                    @if (isset($data['nama_barang']))
                                        : <span class="font-semibold">{{ $data['nama_barang'] }}
                                            ({{ $data['id_barang'] }})</span>
                                    @elseif (isset($data['nama_supplier']))
                                        : <span class="font-semibold">{{ $data['nama_supplier'] }}
                                            ({{ $data['id_supplier'] }})</span>
                                    @endif

                                    @if (isset($data['idBarangRetur']))
                                        <span class="font-semibold">: ID Retur ({{ $data['idBarangRetur'] }})</span>
                                    @elseif (isset($data['idBarangRusak']))
                                        <span class="font-semibold">: ID Rusak ({{ $data['idBarangRusak'] }})</span>
                                    @endif

                                    @if (isset($data['added_by']))
                                        <span class="text-xs text-gray-500 block pt-0.5">by {{ $data['added_by'] }}</span>
                                    @elseif(isset($data['staff_nama']))
                                        <span class="text-xs text-gray-500 block pt-0.5">by {{ $data['staff_nama'] }}</span>
                                    @elseif(isset($data['deleted_by']))
                                        <span class="text-xs text-gray-500 block pt-0.5">by {{ $data['deleted_by'] }}</span>
                                    @elseif(isset($data['restored_by']))
                                        <span class="text-xs text-gray-500 block pt-0.5">by {{ $data['restored_by'] }}</span>
                                    @elseif(isset($data['validated_by']))
                                        <span class="text-xs text-gray-500 block pt-0.5">by {{ $data['validated_by'] }}</span>
                                    @elseif(isset($data['rejected_by']))
                                        <span class="text-xs text-gray-500 block pt-0.5">by {{ $data['rejected_by'] }}</span>
                                    @endif
                                </p>
                                <span class="text-xs text-gray-400">
                                    {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                                </span>
                            </li>
                        @endforeach
                    @else
                        <li class="px-4 py-3 text-gray-500 text-center">No notifications</li>
                    @endif
                </ul>
            </div>
        </div>

        @if (session('user_logged_in'))
            <!-- Profile Section -->
            <div class="relative inline-block" id="profile-container">
                <!-- Clickable Profile Area -->
                <div id="profile-toggle" class="flex items-center gap-1 sm:gap-2 cursor-pointer p-2 rounded-md hover:bg-gray-100">
                    {{-- <img src="{{ asset('storage/profile-images/' . session('user_data')->foto) }}" alt="Profile" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover"> --}}
                    <span class="text-sm sm:text-base text-gray-800 font-medium whitespace-nowrap">{{ session('user_data')->nama }}</span>
                    <i class="fas fa-chevron-down text-xs text-gray-600"></i>
                </div>
                <!-- Dropdown Menu -->
                <div id="dropdown-menu"
                    class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                    <ul class="text-gray-700 text-sm">
                        <li>
                            <a href="{{ route('profile', session('idAkun')) }}"
                                class="block px-4 py-2 hover:bg-gray-100">
                                Profile
                            </a>
                        </li>
                        <li class="hover:bg-gray-100">
                            <a href="{{ route('logout') }}" class="block px-4 py-2">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        @endif
    </div>

    <!-- JavaScript for Profile Dropdown -->
    <script>
        const profileToggle = document.getElementById('profile-toggle');
        const dropdownMenu = document.getElementById('dropdown-menu');
        const profileContainer = document.getElementById('profile-container'); // Get the container

        if (profileToggle && dropdownMenu && profileContainer) {
            profileToggle.addEventListener('click', function(event) {
                event.stopPropagation(); // Prevent click from bubbling to document
                dropdownMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', function(event) {
                // Check if the click is outside the profile container AND the dropdown is not hidden
                if (!profileContainer.contains(event.target) && !dropdownMenu.classList.contains('hidden')) {
                    dropdownMenu.classList.add('hidden');
                }
            });
        }
    </script>

    <!-- JavaScript for Notification Dropdown -->
    <script>
        const notifBtn = document.getElementById('notification-toggle');
        const notifDropdown = document.getElementById('notification-dropdown');
        const notifWrapper = document.getElementById('notification-wrapper');
        const notifDot = document.getElementById('notif-dot');
        let notifMarkedRead = false;

        if (notifBtn && notifDropdown && notifWrapper) {
            notifBtn.addEventListener('click', function(event) {
                event.stopPropagation(); // Prevent click from bubbling to document
                notifDropdown.classList.toggle('hidden');
                
                // Only mark as read the first time dropdown is opened AND it's actually visible
                if (!notifMarkedRead && notifDot && !notifDropdown.classList.contains('hidden')) {
                    fetch("{{ route('notifications.markAllRead') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json",
                            "Content-Type": "application/json" // Good practice
                        }
                    }).then(response => {
                        if (response.ok) {
                             notifMarkedRead = true; // Mark as read only on success
                             if (notifDot) notifDot.style.display = 'none';
                        }
                        // else { console.error("Failed to mark notifications as read"); } // Optional: error handling
                    }).catch(error => {
                        // console.error("Error marking notifications as read:", error); // Optional: error handling
                    });
                }
            });

            document.addEventListener('click', function(event) {
                 // Check if the click is outside the notification wrapper AND the dropdown is not hidden
                if (!notifWrapper.contains(event.target) && !notifDropdown.classList.contains('hidden')) {
                    notifDropdown.classList.add('hidden');
                }
            });
        }
    </script>
</header>