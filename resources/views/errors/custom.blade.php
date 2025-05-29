
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terjadi Kesalahan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex flex-col items-center justify-center min-h-screen">
        <div class="bg-white shadow-md rounded px-8 py-6 max-w-lg w-full mt-12">
            <h1 class="text-2xl font-bold text-red-600 mb-4">Terjadi Kesalahan</h1>
            <p class="mb-2 text-gray-700">
                {{ $message ?? 'Maaf, terjadi kesalahan saat memproses permintaan Anda.' }}
            </p>
            @if(config('app.debug') && !empty($error))
                <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded">
                    <strong>Detail Error:</strong>
                    <pre class="text-xs text-red-700">{{ $error }}</pre>
                </div>
            @endif
            <div class="mt-6">
                <a href="{{ url()->previous() }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</body>
</html>