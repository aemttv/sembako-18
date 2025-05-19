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

    /**
     * View all products with their details and stock.
     *
     * This function shows a paginated list of all products, with their names, prices, and total stock.
     * It also includes the latest kondisiBarangText and merekBarangName for each product.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewBarang()
    {
        // Eager load both 'detailBarang' and 'merek' relationships
        $barang = Barang::with(['detailBarang', 'merek'])->paginate(10);

        // Transform the collection to include dynamic values
        $barang->getCollection()->transform(function ($item) {
            // Dynamic total stock from detailBarang
            $item->totalStok = $item->detailBarang->sum('quantity');

            // Determine kondisiBarangText based on string value in detailBarang
            // Priority: Kadaluarsa > Mendekati Kadaluarsa > Baik
            // pluck -> extract semua value dari kondisiBarang dan return sebagai array baru 
            $kondisiList = $item->detailBarang->pluck('kondisiBarang')->all();

            if (in_array('Kadaluarsa', $kondisiList, true)) {
                $item->kondisiBarangText = 'Kadaluarsa';
            } elseif (in_array('Mendekati Kadaluarsa', $kondisiList, true)) {
                $item->kondisiBarangText = 'Mendekati Kadaluarsa';
            } else {
                $item->kondisiBarangText = 'Baik';
            }

            // Access the 'merek' relationship and add a custom attribute
            $item->merekBarangName = $item->merek ? $item->merek->namaMerek : 'Unknown';

            return $item;
        });

        // Return the view with the barang data
        return view('menu.barang.produk', ['barang' => $barang]);
    }

    /**
     * Search for product brands by name.
     *
     * This function takes a query string from the request and searches for
     * product brands ('bMerek') whose name contains the query string.
     * It returns a JSON response with a list of matching brands, including
     * their IDs and names.
     *
     * @param \Illuminate\Http\Request $request The incoming request containing the search query.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the search results.
     */

    public function searchMerek(Request $request)
    {
        $query = $request->get('q');

        $results = bMerek::where('namaMerek', 'like', "%$query%")
            ->select('idMerek', 'namaMerek')
            ->get();

        return response()->json($results);
    }

    /**
     * Search for products by name.
     *
     * This function takes a query string from the request and searches for
     * products ('Barang') whose name contains the query string.
     * It returns a JSON response with a list of matching products, including
     * their IDs and names.
     *
     * @param \Illuminate\Http\Request $request The incoming request containing the search query.
     *
     * @return \Illuminate\Http\JsonResponse A JSON response containing the search results.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        $results = Barang::where('namaBarang', 'like', "%$query%")
            ->select('idBarang', 'namaBarang')
            ->get();

        return response()->json($results);
    }
    /**
     * Search for a barcode in the Barang's detailBarang relation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchBarcode(Request $request)
    {
        $query = $request->get('q');

        $results = Barang::with([
            'detailBarang' => function ($queryBuilder) use ($query) {
                $queryBuilder->where('barcode', 'like', "%$query%"); // load barcode
            },
        ])
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
                'barcode' => $barcode,
            ];
        });

        return response()->json($formatted);
    }

    /**
     * Search for a barang detail by barcode and return its information.
     *
     * This method accepts a request containing a barcode and retrieves the corresponding
     * BarangDetail along with its related Barang information. If the detail is found, it returns
     * a JSON response containing the idBarang, barcode, nama, harga, and stok. If not found, it
     * returns a 404 response.
     *
     * @param \Illuminate\Http\Request $request The request instance containing the barcode to search for.
     * @return \Illuminate\Http\JsonResponse A JSON response with the barang detail or a 404 status if not found.
     */

    public function searchDetail(Request $request)
    {
        $barcode = $request->get('barcode');

        $detail = BarangDetail::with('barang')->where('barcode', $barcode)->first();

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

    /**
     * Show the detail of a specific product with its details and stock.
     *
     * @param int $idBarang The ID of the product to show.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewDetailProduk($idBarang)
    {
        Carbon::setLocale('id');

        $barang = Barang::with([
            'detailBarang' => function ($query) {
                $query->where('statusBarang', 1);
            },
            'merek',
        ])
            ->where('idBarang', $idBarang)
            ->firstOrFail(); // Changed from first() to firstOrFail()

        $barang->merekBarangName = $barang->merek ? $barang->merek->namaMerek : 'Unknown';
        $barang->totalStok = $barang->detailBarang->sum('quantity');

        return view('menu.barang.detail-produk', compact('barang'));
    }

    /**
     * Handle adding a new product brand.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
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

    function viewTambahProduk()
    {
        $merek = bMerek::all();
        $kategori = KategoriBarang::cases();

        return view('menu.barang.tambah', ['kategori' => $kategori, 'merek' => $merek]);
    }

    /**
     * Tambah produk baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function tambahProduk(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validate the request
            $request->validate([
                'barang_image.*' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                'items' => 'required|array',
                'items.*.nama_barang' => 'required',
                'items.*.merek_id' => 'required',
                'items.*.kategori' => 'required',
                'items.*.harga_satuan' => 'required|numeric',
                'items.*.kuantitas_masuk' => 'required|numeric',
            ]);

            // Process images if any
            $uploadedImages = [];
            if ($request->hasFile('barang_image')) {
                foreach ($request->file('barang_image') as $index => $file) {
                    $directory = public_path('produk');
                    if (!file_exists($directory)) {
                        mkdir($directory, 0755, true);
                    }

                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->move($directory, $filename);

                    // Store either:
                    // Option 1: Just the filename (recommended)
                    $uploadedImages[$index] = $filename;

                    // OR Option 2: Full relative path
                    // $uploadedImages[$index] = 'produk/'.$filename;
                }
            }

            // Process each product
            foreach ($request->items as $index => $item) {
                // Create new product
                $barang = new Barang();
                $barang->idBarang = Barang::generateNewIdBarang();
                $barang->namaBarang = $item['nama_barang'];
                $barang->merekBarang = $item['merek_id'];
                $barang->kategoriBarang = $item['kategori'];
                $barang->hargaJual = $item['harga_satuan'];
                $barang->stokBarang = $item['kuantitas_masuk'];

                // Associate image if exists for this index
                if (isset($uploadedImages[$index])) {
                    $barang->gambarProduk = $uploadedImages[$index];
                }

                // dd($barang->gambarProduk);

                $barang->save();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Gagal menambahkan produk: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function softDeleteBarangDetail(Request $request, $idBarang, $barcode)
    {
        $detail = BarangDetail::where('idBarang', $idBarang)->where('barcode', $barcode)->where('statusBarang', 1)->first();

        if (!$detail) {
            return redirect()->back()->with('error', 'Barang detail tidak ditemukan.');
        }

        // Your soft-delete logic
        $detail->statusBarang = 0;
        $detail->save();

        return redirect()->back()->with('success', 'Barang detail berhasil dihapus.');
    }
}
