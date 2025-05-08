<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    function viewBarang()
    {
        $barang = Barang::where('statusBarang', 1)->paginate(10);

        $barang->getCollection()->transform(function ($item) {
            // Check the value of kondisiBarang and assign the appropriate string
            if ($item->merekBarang == 1) {
                $item->merekBarangText = 'Merek 1';
            } elseif ($item->merekBarang == 2) {
                $item->merekBarangText = 'Merek 2';
            } elseif ($item->merekBarang == 3) {
                $item->merekBarangText = 'Merek 3';
            } else {
                $item->merekBarangText = 'Merek 4';
            }
            return $item;
        });

        $barang->getCollection()->transform(function ($item) {
            // Check the value of kondisiBarang and assign the appropriate string
            if ($item->kategoriBarang == 1) {
                $item->kategoriBarangText = 'Kategori 1';
            } elseif ($item->kategoriBarang == 2) {
                $item->kategoriBarangText = 'Kategori 2';
            } elseif ($item->kategoriBarang == 3) {
                $item->kategoriBarangText = 'Kategori 3';
            } else {
                $item->kategoriBarangText = 'Kategori 4';
            }
            return $item;
        });

        $barang->getCollection()->transform(function ($item) {
           // Check the value of kondisiBarang and assign the appropriate string
            if ($item->kondisiBarang == 1) {
                $item->kondisiBarangText = 'Baik';
            } elseif ($item->kondisiBarang == 2) {
                $item->kondisiBarangText = 'Mendekati Kadaluarsa';
            } elseif ($item->kondisiBarang == 3) {
                $item->kondisiBarangText = 'Kadaluarsa';
            } else {
                $item->kondisiBarangText = 'Rusak';
            }
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

}
