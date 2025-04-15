<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/style.css">
    <title>Toko Sembako 18</title>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

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
                    hoverColor: "#f77b00"
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100">

    <!-- Header -->
    <Header class="flex min-h-screen">
        <x-navbar></x-navbar>
    </Header>

    {{-- <!-- Main Content -->
    <main class="flex-grow">
        {{ $slot }}
    </main> --}}

    <!-- Footer -->
    {{-- <x-footer></x-footer> --}}

    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>
</html>