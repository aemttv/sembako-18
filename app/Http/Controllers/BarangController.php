<?php

namespace App\Http\Controllers;

use App\enum\KategoriBarang;
use App\Models\Barang;
use App\Models\BarangDetail;
use App\Models\bMerek;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    public function viewBarang()
    {
        // Eager load both 'detailBarang' and 'merekBarang' relationships
        $barang = Barang::with(['detailBarang', 'merek'])
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
        return view('menu.barang.produk', ['barang' => $barang]);
    }

    public function searchMerek(Request $request)
    {
        $query = $request->get('q');

        $results = bMerek::where('namaMerek', 'like', "%$query%")
            ->select('idMerek', 'namaMerek')
            ->get();

        return response()->json($results);
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
                'idBarang' => $detail->idBarang,
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
        $barang = Barang::with(['detailBarang' => function ($query) {
            $query->where('statusBarang', 1);
        }])
        ->where('idBarang', $idBarang)
        ->first();

        // Dynamically calculate total stock (sum of all detail quantities)
        $barang->totalStok = $barang->detailBarang->sum('quantity');

        return view('menu.barang.detail-produk', ['barang' => collect([$barang])]); // so @foreach still works
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

    function viewTambahProduk() {
        $merek = bMerek::all();
        $kategori = KategoriBarang::cases();

        return view('menu.barang.tambah', ['kategori' => $kategori, 'merek' => $merek]);
    }

    function tambahProduk(Request $request) {

        if (!is_array($request->barang_input)) {
            return back()->with('error', 'Tidak ada data barang yang dikirim.');
        }

        DB::beginTransaction();

        foreach ($request->barang_input as $jsonItem) {
            $item = json_decode($jsonItem, true);

            $barang = new Barang();
            $barang->idBarang = Barang::generateNewIdBarang();
            $barang->namaBarang = $item['nama_barang'];
            $barang->merekBarang = $item['nama_merek'];
            $barang->kategoriBarang = $item['kategori'];
            $barang->hargaJual = $item['harga_satuan'];
            $barang->stokBarang = $item['kuantitas_masuk'];

            dd($barang);

            $barang->save();
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
    }
}
