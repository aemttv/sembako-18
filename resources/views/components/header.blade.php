<!-- Top Navbar -->

<header
    class="fixed top-0 left-80 right-0 shadow px-4 py-3 flex justify-between items-center z-10 bg-white border-b border-gray-200">
    <!-- Left section: Menu + Search -->
    <div class="flex items-center gap-3">
        <!-- Menu Button -->
        <button class="border rounded-lg p-4">
            <i class="fas fa-bars text-gray-600"></i>
        </button>

        <!-- Search Input Group -->
        {{-- <div class="flex items-center bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 w-[360px] shadow-sm">
            <i class="fas fa-search text-gray-400 mr-2"></i>
            <input type="text" placeholder="Search or type command..."
                class="bg-transparent border-none focus:ring-0 focus:outline-none w-full text-sm text-gray-700 placeholder-gray-400" />
        </div> --}}
    </div>

    <!-- Right section: Dark mode, Notification, Profile -->
    <div class="flex items-center gap-4">

        <!-- Notification Bell with Dropdown -->
        <div class="relative inline-block" id="notification-wrapper">
            @php
                // $hasNotifications = isset($globalNotifications) && $globalNotifications->count();
                $hasUnread = isset($unreadNotifications) && $unreadNotifications->count();
            @endphp
            <!-- Button -->
            <button id="notification-toggle"
                class="relative w-14 h-14 flex items-center justify-center rounded-full border hover:bg-gray-100 focus:outline-none">
                <i class="fas fa-bell text-gray-500"></i>
                @if($hasUnread)
                    <span id="notif-dot" class="absolute top-1.5 right-1.5 w-2 h-2 bg-orange-500 rounded-full"></span>
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
                                Clear Notification
                            </button>
                        </form>
                    @endif
                </div>

                <ul class="max-h-60 overflow-y-auto divide-y divide-gray-100 text-sm">
                    @if (isset($globalNotifications) && $globalNotifications->count())
                        @foreach ($globalNotifications as $notif)
                            <li class="px-4 py-3 hover:bg-gray-50 cursor-pointer">
                                <p class="text-gray-700">
                                    {{ $notif->title }}
                                    @php
                                        // Decode data if it's a JSON string
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
                                    @endif

                                    @if (isset($data['added_by']))
                                        <span class="text-xs text-gray-500"><br>by {{ $data['added_by'] }}</span>
                                    @elseif(isset($data['staff_nama']))
                                        <span class="text-xs text-gray-500"><br>by {{ $data['staff_nama'] }}</span>
                                    @elseif(isset($data['deleted_by']))
                                        <span class="text-xs text-gray-500"><br>by {{ $data['deleted_by'] }}</span>
                                    @elseif(isset($data['restored_by']))
                                        <span class="text-xs text-gray-500"><br>by {{ $data['restored_by'] }}</span>
                                    @elseif(isset($data['validated_by']))
                                        <span class="text-xs text-gray-500"><br>by {{ $data['validated_by'] }}</span>
                                    @elseif(isset($data['rejected_by']))
                                        <span class="text-xs text-gray-500"><br>by {{ $data['rejected_by'] }}</span>
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
                <div id="profile-toggle" class="flex items-center gap-2 cursor-pointer">
                    {{-- <img src="{{ asset('storage/profile-images/' . session('user_data')->foto) }}" alt="Profile" class="w-14 h-14 rounded-full object-cover"> --}}
                    <span class="text-xl text-gray-800 font-medium">{{ session('user_data')->nama }}</span>
                    <i class="fas fa-chevron-down text-xs text-gray-600"></i>
                </div>
                <!-- Dropdown Menu -->
                <div id="dropdown-menu"
                    class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                    <ul class="text-gray-700 text-sm">
                        <li>
                            <a href="{{ route('profile', session('idAkun')) }}"
                                class="block px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                Profile
                            </a>
                        </li>
                        {{-- <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">Settings</li> --}}
                        <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                            onclick="window.location.href='{{ route('logout') }}'">Logout</li>
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
        const notifDot = document.getElementById('notif-dot');
        let notifMarkedRead = false;

        notifBtn.addEventListener('click', function(event) {
            notifDropdown.classList.toggle('hidden');
            // Only mark as read the first time dropdown is opened
            if (!notifMarkedRead && notifDot && !notifDropdown.classList.contains('hidden')) {
                fetch("{{ route('notifications.markAllRead') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    }
                }).then(response => {
                    notifMarkedRead = true;
                    if (notifDot) notifDot.style.display = 'none';
                });
            }
        });

        document.addEventListener('click', function(event) {
            if (!notifWrapper.contains(event.target)) {
                notifDropdown.classList.add('hidden');
            }
        });
    </script>
</header>
