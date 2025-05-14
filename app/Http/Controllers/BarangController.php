<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangDetail;
use App\Models\bMerek;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function viewBarang()
    {
        // Eager load both 'detailBarang' and 'merekBarang' relationships
        $barang = Barang::with(['detailBarang', 'merek'])
            ->where('statusBarang', 1)
            ->paginate(10);

        // Transform the collection to include dynamic values
        $barang->getCollection()->transform(function ($item) {
            // Dynamic total stock from detailBarang
            $item->totalStok = $item->detailBarang->sum('quantity');

            // Convert kondisi (only if Barang has this directly)
            $item->kondisiBarangText = match ($item->kondisiBarang) {
                '1' => 'Baik',
                '2' => 'Mendekati Kadaluarsa',
                '3' => 'Kadaluarsa',
                default => 'Baik',
            };

            // Access the 'merekBarang' relationship and add a custom attribute
            $item->merekBarangName = $item->merek ? $item->merek->namaMerek : 'Unknown';

            return $item;
        });

        // Return the view with the barang data
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
    public function searchBarcode(Request $request)
    {
        $query = $request->get('q');

        $results = Barang::with(['detailBarang' => function ($queryBuilder) use ($query) {
            $queryBuilder->where('barcode', 'like', "%$query%"); // load barcode 
        }])
        ->whereHas('detailBarang', function ($queryBuilder) use ($query) {
            $queryBuilder->where('barcode', 'like', "%$query%"); // filterkan parent barang yang barcodenya related sama detailbarang 
        })
        ->select('idBarang') // barcode is in the relation
        ->get();

        // Flatten result for easier frontend use (just the latest barcode)
        $formatted = $results->map(function ($item) {
            $barcode = $item->detailBarang->first()->barcode ?? '';
            return [
                'idBarang' => $item->idBarang,
                'barcode' => $barcode
            ];
        });

        return response()->json($formatted);
    }

    public function searchDetail(Request $request)
    {
        $barcode = $request->get('barcode');

        $detail = BarangDetail::with('barang')
            ->where('barcode', $barcode)
            ->first();

        if ($detail) {
            return response()->json([
                'barcode' => $detail->barcode,
                'nama' => $detail->barang->namaBarang ?? '',
                'harga' => $detail->barang->hargaJual ?? 0,
                'stok' => $detail->quantity ?? 0,
            ]);
        } else {
            return response()->json(null, 404);
        }
    }



    public function viewDetailProduk($idBarang)
    {
        Carbon::setLocale('id');
        $barang = Barang::with('detailBarang')->where('idBarang', $idBarang)->first();

        // Dynamically calculate total stock (sum of all detail quantities)
        $barang->totalStok = $barang->detailBarang->sum('quantity');

        return view('menu.detail-produk', ['barang' => collect([$barang])]); // so @foreach still works
    }

    function tambahMerek(Request $request)
    {
        // Validate the input
        $request->validate([
            'merekBaru' => 'required|string|max:255',
        ]);

        // Create a new instance of the bMerek model
        $merekBaru = new bMerek();

        // Set the namaMerek field
        $merekBaru->namaMerek = $request->input('merekBaru');

        // Save to database
        $merekBaru->save();

        // Optionally return response or redirect
        return redirect()->back()->with('success', 'Merek berhasil ditambahkan!');
    }

    function viewBKeluar() {

        $stokTersedia = Barang::with(['detailBarang', 'merek'])
            ->where('statusBarang', 1)
            ->paginate(10);

            $stokTersedia->getCollection()->transform(function ($item) {
            // Dynamic total stock from detailBarang
            $item->totalStok = $item->detailBarang->sum('quantity');

            // Convert kondisi (only if Barang has this directly)
            $item->kondisiBarangText = match ($item->kondisiBarang) {
                '1' => 'Baik',
                '2' => 'Mendekati Kadaluarsa',
                '3' => 'Kadaluarsa',
                default => 'Baik',
            };

            // Access the 'merekBarang' relationship and add a custom attribute
            $item->merekBarangName = $item->merek ? $item->merek->namaMerek : 'Unknown';

            return $item;
        });

        // $barcode = Barang::where('statusBarang', 1)->pluck('barcode')->toArray();

        return view('menu.manajemen.bKeluar', ['stokTersedia' => $stokTersedia]);
    }
}
