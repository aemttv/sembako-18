<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    {{-- <link rel="icon" type="image/png" href="{{ asset('assets/images/logo_1png.png') }}?v=123"> --}}

    <title>Toko Sembako 18</title>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>


    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        primary: "#F68A1F",
                        white: "#FFFFFF",
                        black: "#000000",
                        background: "#F7F7F7",
                        hoverColor: "#f77b00",
                        pale: "#f9eeef",
                        pale2: "#e7e4eb",
                        pale3: "#cadce9",
                    }
                }
            }
        }
    </script>


</head>

<body class="bg-gray-100 m-0 p-0">

    <!-- Wrapper -->
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <x-sidebar />

        <!-- Main content wrapper -->
        <div class="flex-1 flex flex-col">

            <!-- Header -->
            <x-header /> 

            <!-- Page content -->
            <main class="flex-1 pt-20 md:ml-80 bg-[#F9FAFB]">
                <!-- Content wrapper with padding -->
                <div class="px-4 pb-4 pt-0 md:px-6 md:pb-6"> {{-- pt-0 because main already has pt-20 --}}
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Loading Screen -->
    <div id="loading-screen" class="fixed inset-0 bg-gray-100 flex items-center justify-center z-50 hidden">
        <div class="text-center">
            <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-12 w-12 mb-4 animate-spin"></div>
            <p class="text-sm text-gray-500">Loading...</p>
        </div>
    </div>

    <style>
        .loader {
            border-top-color: #3498db;
        }
    </style>

<script>
    const loadingScreen = document.getElementById('loading-screen');

    // Handle first load (not from manual navigation)
    if (!sessionStorage.getItem('manualNavigation')) {
        window.addEventListener('DOMContentLoaded', () => {
            loadingScreen.classList.remove('hidden');
            setTimeout(() => {
                loadingScreen.classList.add('hidden');
            }, 600);
        });
    } else {
        sessionStorage.removeItem('manualNavigation');
    }

    //  Handle manual navigation (link clicks)
    document.querySelectorAll('a[href]').forEach(link => {
        link.addEventListener('click', function (e) {
            const target = e.currentTarget.getAttribute('target');
            const href = e.currentTarget.getAttribute('href');

            if (
                href.startsWith('#') ||
                href.startsWith('javascript:') ||
                target === '_blank'
            ) return;

            e.preventDefault();
            loadingScreen.classList.remove('hidden');

            // Mark that we're navigating manually
            sessionStorage.setItem('manualNavigation', 'true');

            setTimeout(() => {
                window.location.href = href;
            }, 800);
        });
    });

    // Hide loader when coming back via browser back/forward button
    window.addEventListener('pageshow', (event) => {
        if (event.persisted) {
            // Page loaded from bfcache (back/forward)
            loadingScreen.classList.add('hidden');
        }
    });
</script>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>

</html>
