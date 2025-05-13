<?php

namespace App\Http\Controllers;

use App\Models\Barang;
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

    public function viewDetailProduk($idBarang) {
        Carbon::setLocale('id');
        $barang = Barang::with('detailBarang')->where('idBarang', $idBarang)->first();

        // Dynamically calculate total stock (sum of all detail quantities)
        $barang->totalStok = $barang->detailBarang->sum('quantity');

        return view('menu.detail-produk', ['barang' => collect([$barang])]); // so @foreach still works
    }

    function tambahMerek(Request $request) {
        
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


}
