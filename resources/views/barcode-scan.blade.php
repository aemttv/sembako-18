
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Barang - Toko Sembako 18</title>
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
                        hoverColor: "#f77b00"
                    }
                }
            }
        }
    </script>


</head>
<body>
    
    <div class="max-w-2xl mx-auto p-6 bg-white shadow rounded-lg mt-10">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Detail Produk</h2>
    
        <div class="border border-gray-200 rounded-md p-4 space-y-4">
            <div class="flex justify-between">
                <span class="font-medium text-gray-600">Barcode:</span>
                <span class="text-gray-800">{{ $scannedDetail->barcode }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-gray-600">Nama Produk:</span>
                <span class="text-gray-800">{{ $barang->namaBarang }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-gray-600">Merek:</span>
                <span class="text-gray-800">{{ $barang->merekBarangName ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-gray-600">Kategori:</span>
                <span class="text-gray-800">{{ $barang->kategoriBarang->namaKategori() ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-gray-600">Harga:</span>
                <span class="text-gray-800">Rp {{ number_format($barang->hargaJual, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-gray-600">Stok:</span>
                <span class="text-gray-800">{{ $barang->totalStok }}</span>
            </div>
        </div>
    
        <div class="mt-6 text-right">
            <a href="{{ url()->previous() }}"
               class="inline-block px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-800 transition">
                Kembali
            </a>
        </div>
    </div>
</body>
</html>

