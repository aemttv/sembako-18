<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function viewBarang()
    {
        $barang = Barang::with('detailBarang')->where('statusBarang', 1)->paginate(10);

        $barang->getCollection()->transform(function ($item) {
            // Dynamic total stock
            $item->totalStok = $item->detailBarang->sum('quantity');

            // Convert merek
            $item->merekBarangText = match ($item->merekBarang) {
                1 => 'Merek 1',
                2 => 'Merek 2',
                3 => 'Merek 3',
                default => 'Merek 4',
            };

            // Convert kategori
            $item->kategoriBarangText = match ($item->kategoriBarang) {
                1 => 'Kategori 1',
                2 => 'Kategori 2',
                3 => 'Kategori 3',
                default => 'Kategori 4',
            };

            // Convert kondisi (only if Barang has this directly — usually it's in Detail though)
            $item->kondisiBarangText = match ($item->kondisiBarang) {
                'Baik' => 'Baik',
                'Mendekati Kadaluarsa' => 'Mendekati Kadaluarsa',
                'Kadaluarsa' => 'Kadaluarsa',
                default => 'Baik',
            };

            return $item;
        });

        return view('menu.produk', ['barang' => $barang]);
    }


    public function search(Request $request)
    {
        $query = $request->get('q');

        $results = Barang::where('namaBarang', 'like', "%$query%")
            ->select('idBarang', 'namaBarang')
            ->get();

        return response()->json($results);
    }

    public function viewDetailProduk($idBarang) {
        $barang = Barang::with('detailBarang')->where('idBarang', $idBarang)->first();

        // Dynamically calculate total stock (sum of all detail quantities)
        $barang->totalStok = $barang->detailBarang->sum('quantity');

        return view('menu.detail-produk', ['barang' => collect([$barang])]); // so @foreach still works
    }


}
