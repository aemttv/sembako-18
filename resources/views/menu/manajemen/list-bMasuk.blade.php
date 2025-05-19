@extends('layout')

@section('content')
    <div class="p-6 space-y-4">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div class="flex-1">
                <h1 class="text-xl font-semibold">Tabel Barang Masuk </h1>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Home > Daftar Produk</p>
            </div>
        </div>


        <!-- Tabs -->
        <div class="flex justify-between items-center gap-2 border rounded-lg p-2 bg-white">
            <!-- Search Input Group -->
            <div class="flex items-center bg-gray-50 border border-gray-200 rounded-xl px-3 w-[360px] shadow-sm mx-auto">
                <i class="fas fa-search text-gray-400 mr-2"></i>
                <input type="text" placeholder="Search or type command..."
                    class="bg-transparent border-none focus:ring-0 focus:outline-none w-full text-sm text-gray-700 placeholder-gray-400" />
            </div>
            <a href="/barang-masuk"
                class="px-4 py-2 text-sm font-medium text-white bg-green-500 rounded-md hover:bg-green-600">Tambah Barang
                Masuk</a>
        </div>


        <!-- Table -->
        <div class="border rounded-lg overflow-x-auto">
            <table class="min-w-full text-lg text-center items-center">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-4 py-2">ID Barang Masuk</th>
                        <th class="px-4 py-2">ID Supplier</th>
                        <th class="px-4 py-2">ID Akun</th>
                        <th class="px-4 py-2">Harga Beli</th>
                        <th class="px-4 py-2">Jumlah Masuk</th>
                        <th class="px-4 py-2">Subtotal</th>
                        <th class="px-4 py-2">Tanggal Masuk</th>
                        <th class="px-4 py-2">Tanggal Kadaluarsa</th>
                        <th class="px-4 py-2">Nota</th>
                        <th class="px-4 py-2">Proses</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @if ($bMasuk->isEmpty())
                        <td>
                        <td class="px-4 py-2 text-center" colspan="9">Data Barang Masuk tidak ditemukan.</td>
                        </td>
                    @endif
                    @foreach ($bMasuk as $data)
                        <tr>
                            <td class="px-4 py-2">{{ $data->idBarangMasuk }}</td>
                            <td class="px-4 py-2">{{ $data->idSupplier }}</td>
                            <td class="px-4 py-2">{{ $data->idAkun }}</td>
                            <td class="px-4 py-2">Rp.{{ number_format($data->hargaBeli, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">{{ $data->quantity }}</td>
                            <td class="px-4 py-2">Rp.{{ number_format($data->total, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($data->tglMasuk)->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($data->expiredDate)->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-4 py-2 text-center">
                                <button onclick="showNotaModal('{{ asset('nota_file/' . $data->nota) }}')"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                    Lihat Nota
                                </button>
                            </td>

                            <td class="px-4 py-2 flex gap-1">
                                {{-- <a href="{{route('detail.produk', ['idBarangMasuk' => $data->idBarangMasuk])}}" class="px-2 py-1 bg-blue-500 text-white rounded text-xs">Detail</a> --}}
                                <a href="#" class="px-2 py-1 bg-yellow-500 text-white rounded text-xs">Edit</a>
                                <a href="#" class="px-2 py-1 bg-red-500 text-white rounded text-xs">Hapus</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <div id="notaModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white p-4 rounded shadow-lg max-w-xl w-full relative">
                <button onclick="closeNotaModal()"
                    class="absolute top-2 right-2 text-gray-500 hover:text-black text-lg">&times;</button>
                <img id="notaImage" src="" alt="Nota Image" class="max-w-full h-auto mx-auto">
            </div>
        </div>


        <!-- Pagination -->
        {{ $bMasuk->links() }}
    </div>

    <script>
        function showNotaModal(imageUrl) {
            document.getElementById('notaImage').src = imageUrl;
            document.getElementById('notaModal').classList.remove('hidden');
        }

        function closeNotaModal() {
            document.getElementById('notaModal').classList.add('hidden');
            document.getElementById('notaImage').src = '';
        }
    </script>
@endsection
