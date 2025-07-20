<?php

namespace App\Http\Controllers;

use App\enum\KategoriBarang;
use App\enum\KondisiBarang;
use App\enum\satuan;
use App\Models\Akun;
use App\Models\Barang;
use App\Models\BarangDetail;
use App\Models\bMerek;
use App\Models\Notifications;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

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

        if(!isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

        // Eager load both 'detailBarang' and 'merek' relationships
        $barang = Barang::with([
            'detailBarang' => function ($query) {
                $query->where('statusDetailBarang', 1);
            },
            'merek',
        ])->paginate(10);

        // Transform the collection to include dynamic values
        $barang->getCollection()->transform(function ($item) {
            // Dynamic total stock from detailBarang
            // If satuan is 2 (Kg), count the number of detailBarang rows
            // Otherwise, sum the quantity as usual
            if ($item->satuan->value === 2) {
                $item->totalStok = $item->detailBarang->count();
            } else {
                $item->totalStok = $item->detailBarang->sum('quantity');
            }

            // Determine kondisiBarangText based on string value in detailBarang
            // Priority: Kadaluarsa > Mendekati Kadaluarsa > Baik
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
        if(!isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

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
        if(!isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

        $query = $request->get('q');

        // Eager load 'merek' relationship
        $results = Barang::with('merek')
        ->where(function ($q) use ($query) {
            $q->where('namaBarang', 'like', "%$query%")
            ->orWhereHas('merek', function ($q2) use ($query) {
                $q2->where('namaMerek', 'like', "%$query%");
            });
        })
        ->select('idBarang', 'namaBarang', 'satuan', 'merekBarang', 'kategoriBarang')
        ->get();

        // Transform the results to include merek name
        $formatted = $results->map(function ($item) {
            return [
                'idBarang' => $item->idBarang,
                'namaBarang' => $item->namaBarang,
                'satuan' => $item->satuan,
                'merekBarang' => $item->merekBarang,
                'merekNama' => $item->merek ? $item->merek->namaMerek : 'Unknown',
                'kategoriBarang' => $item->kategoriBarang,  
            ];
        });

        return response()->json($formatted);
    }

public function searchList(Request $request)
{
    if(!isUserLoggedIn()){
        abort(403, 'Unauthorized action.');
    }

    $search = $request->input('q');
    $kategori = $request->input('kategoriBarang');
    $kondisi = $request->input('kondisiBarang'); // from select option

    $barangQuery = Barang::with([
        'detailBarang' => function ($query) {
            $query->where('statusDetailBarang', 1);
        },
        'merek',
    ]);
    // Filter by kondisiBarang if selected
    if ($kondisi) {
        $dbValue = KondisiBarang::from($kondisi)->namaKondisi();

        $barangQuery->whereHas('detailBarang', function ($q) use ($dbValue) {
            $q->where('kondisiBarang', $dbValue)
            ->where('statusDetailBarang', 1);
        });

        if ($dbValue === 'Mendekati Kadaluarsa') {
            $barangQuery->whereDoesntHave('detailBarang', function ($q) {
                $q->where('kondisiBarang', 'Kadaluarsa')
                ->where('statusDetailBarang', 1);
            });
        } elseif ($dbValue === 'Baik') {
            $barangQuery->whereDoesntHave('detailBarang', function ($q) {
                $q->whereIn('kondisiBarang', ['Kadaluarsa', 'Mendekati Kadaluarsa'])
                ->where('statusDetailBarang', 1);
            });
        }
    }

    if ($kategori) {
        $barangQuery->where('kategoriBarang', $kategori);
    }


    // Search by namaBarang or idBarang
    $barangQuery->when($search, function ($query, $search) {
        $query->where(function ($q) use ($search) {
            $q->where('namaBarang', 'like', '%' . $search . '%')
            ->orWhere('idBarang', 'like', '%' . $search . '%');
        });
    });

    $barang = $barangQuery->paginate(10)->appends($request->only(['q', 'kondisiBarang']));

    // Transform the collection to include dynamic values
    $barang->getCollection()->transform(function ($item) {
        $item->totalStok = $item->detailBarang->sum('quantity');
        $kondisiList = $item->detailBarang->pluck('kondisiBarang')->all();

        if (in_array('Kadaluarsa', $kondisiList, true)) {
            $item->kondisiBarangText = 'Kadaluarsa';
        } elseif (in_array('Mendekati Kadaluarsa', $kondisiList, true)) {
            $item->kondisiBarangText = 'Mendekati Kadaluarsa';
        } else {
            $item->kondisiBarangText = 'Baik';
        }

        $item->merekBarangName = $item->merek ? $item->merek->namaMerek : 'Unknown';

        return $item;
    });

    return view('menu.barang.produk', [
        'barang' => $barang,
        'search' => $search,
        'kondisiBarang' => $kondisi,
    ]);
}

    /**
     * Search for a barcode in the Barang's detailBarang relation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchBarcode(Request $request)
{
    if (!isUserLoggedIn()) {
        abort(403, 'Unauthorized action.');
    }

    $query = $request->get('q');

    // Start from detailBarang instead of Barang
    $results = BarangDetail::with(['supplier', 'barang.merek'])
        ->where('statusDetailBarang', 1)
        ->where(function ($qBuilder) use ($query) {
            $qBuilder->where('barcode', 'like', "%$query%")
                ->orWhereHas('barang', function ($barangQuery) use ($query) {
                    $barangQuery->where('namaBarang', 'like', "%$query%")
                        ->orWhereHas('merek', function ($merekQuery) use ($query) {
                            $merekQuery->where('namaMerek', 'like', "%$query%");
                        });
                });
        })->orderby('tglKadaluarsa', 'asc')
        ->get();


    $formatted = $results->map(function ($detail) {
        $barang = $detail->barang;

        return [
            'idBarang' => $barang->idBarang ?? null,
            'namaBarang' => $detail->namaBarang ?? $barang->namaBarang ?? 'Unknown',
            'barcode' => $detail->barcode,
            'quantity' => $detail->quantity,
            'stok' => $detail->quantity ?? 0,
            'satuan' => $barang->satuan ?? '-',
            'merekNama' => $barang->merek->namaMerek ?? '-',
            'tglKadaluarsa' => $detail->tglKadaluarsa ?? '-',
            'idSupplier' => $detail->supplier?->idSupplier,
            'namaSupplier' => $detail->supplier?->nama ?? 'Unknown',
            'hargaJual' => $barang->hargaJual ?? '0',
            'kategoriBarang' => $barang->kategoriBarang ?? 'Unknown',
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
    if (!isUserLoggedIn()) {
        abort(403, 'Unauthorized action.');
    }

    $search = $request->get('barcode'); // still using 'barcode' as query param for backward compatibility

    $detail = BarangDetail::with(['barang', 'supplier'])
        ->where('statusDetailBarang', 1)
        ->where(function ($query) use ($search) {
            $query->where('barcode', $search)
                  ->orWhereHas('barang', function ($subQuery) use ($search) {
                      $subQuery->where('namaBarang', 'like', "%$search%");
                  });
        })
        ->first();

    if ($detail) {
        return response()->json([
            'idBarang' => $detail->idBarang,
            'idSupplier' => $detail->idSupplier,
            'namaSupplier' => $detail->supplier->nama ?? '',
            'barcode' => $detail->barcode,
            'nama' => $detail->barang->namaBarang ?? '',
            'harga' => $detail->barang->hargaJual ?? 0,
            'stok' => $detail->quantity ?? 0,
            'satuan' => $detail->barang->satuan ?? '',
            'kadaluarsa' => $detail->tglKadaluarsa,
        ]);
    } else {
        return response()->json(null, 404);
    }
}


    /**
     * Display the details of a specific product.
     *
     * This method retrieves the product ('Barang') with the given ID, including its active details
     * and associated brand ('merek'). It also calculates the total stock of the product and retrieves
     * any inactive details. The product details, along with all available brands and categories,
     * are then passed to the 'detail-produk' view.
     *
     * @param int $idBarang The ID of the product to display.
     *
     * @return \Illuminate\View\View The view displaying the product's details.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If no product with the given ID is found.
     */

    public function viewDetailProduk($idBarang)
    {
        if(!isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

        Carbon::setLocale('id');

        // Get barang with active details
        $barang = Barang::with([
            'detailBarang' => function ($query) {
                $query->where('statusDetailBarang', 1);
                $query->orderby('tglKadaluarsa', 'asc');

            },
            'merek',
        ])
            ->where('idBarang', $idBarang)
            ->firstOrFail();

        $mereks = bMerek::all();
        $kategori = KategoriBarang::cases();
        $satuan = satuan::cases();



        $barang->merekBarangName = $barang->merek ? $barang->merek->namaMerek : 'Unknown';

        // Update: totalStok logic based on satuan
        if ($barang->satuan->value === 2) {
            // satuan is kg
            $barang->totalStok = $barang->detailBarang->count();
        } else {
            $barang->totalStok = $barang->detailBarang->sum('quantity');
        }


        $inactiveDetail = BarangDetail::where('idBarang', $idBarang)
            ->where('statusDetailBarang', 0)
            ->where('quantity', '>', 0)
            ->get();

        return view('menu.barang.detail-produk', compact('barang', 'mereks', 'kategori', 'satuan' , 'inactiveDetail'));
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
        if (!isUserLoggedIn()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Validate the input
            $request->validate([
                'merekBaru' => 'required|string|max:255',
            ]);

            $inputValue = ucwords(trim($request->input('merekBaru')));
            if (bMerek::where('namaMerek', $inputValue)->exists()) {
                return redirect()->back()->with('error', 'Merek sudah ada!');
            }

            // Create a new instance of the bMerek model
            $merekBaru = new bMerek();

            // Set the namaMerek field
            $merekBaru->namaMerek = $request->input('merekBaru');

            // Save to database
            $merekBaru->save();

            // Optionally return response or redirect
            return redirect()->back()->with('success', 'Merek berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Let Laravel handle validation exceptions
            throw $e;
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error adding merek: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => session('user_data')->id ?? null,
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan merek. Silakan coba lagi.');
        }
    }

    function viewTambahProduk()
    {
        if(!isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

        $merek = bMerek::all();
        $kategori = KategoriBarang::cases();
        $satuan = satuan::cases();

        return view('menu.barang.tambah', ['kategori' => $kategori, 'merek' => $merek, 'satuan' => $satuan]);
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

            if(!isUserLoggedIn()){
                abort(403, 'Unauthorized action.');
            }


            // Validate the request
            $request->validate(
                [
                    'barang_image.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048', 'dimensions:min_width=400,min_height=400,max_width=1200,max_height=1200'],
                    'items' => 'required|array',
                    'items.*.nama_barang' => 'required',
                    'items.*.merek_id' => 'required',
                    'items.*.kategori' => 'required',
                    'items.*.harga_satuan' => 'required|numeric',
                    'items.*.kuantitas_masuk' => 'required|numeric',
                ],
                [
                    'barang_image.*.dimensions' => 'Resolusi gambar harus minimal 400x400px dan maksimal 1200x1200px.',
                ],
            );

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
                // Duplicate check: by name, merek, and kategori
                $exists = Barang::where('namaBarang', $item['nama_barang'])
                    ->where('merekBarang', $item['merek_id'])
                    ->where('kategoriBarang', $item['kategori'])
                    ->exists();

                if ($exists) {
                    DB::rollBack();
                    return redirect()
                        ->back()
                        ->with('error', 'Produk dengan nama, merek, dan kategori yang sama sudah ada: ' . $item['nama_barang'])
                        ->withInput();
                }

                // Create new product
                $barang = new Barang();
                $barang->idBarang = Barang::generateNewIdBarang();
                $barang->namaBarang = $item['nama_barang'];
                $barang->merekBarang = $item['merek_id'];
                $barang->kategoriBarang = $item['kategori'];
                $barang->hargaJual = $item['harga_satuan'];
                $barang->stokBarang = $item['kuantitas_masuk'];
                $barang->satuan = $item['satuan_barang'];

                // Associate image if exists for this index
                if (isset($uploadedImages[$index])) {
                    $barang->gambarProduk = $uploadedImages[$index];
                }

                // dd($barang->gambarProduk);

                $barang->save();
            }

            DB::commit();

            $owner = Akun::where('peran', 1)->get();

            foreach ($owner as $o) {
                Notifications::create([
                    'idAkun' => $o->idAkun,
                    'title' => 'Produk Baru Ditambahkan',
                    'message' => 'Produk baru telah ditambahkan.',
                    'data' => json_encode([
                        'nama_barang' => $barang->namaBarang,
                        'id_barang' => $barang->idBarang,
                        'added_by' => session('user_data')->nama,
                    ]),
                ]);
            }

            return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan barang. Silakan coba lagi.')
                ->withInput();
        }
    }

    function updateBarangDetail(Request $request, $idBarang)
    {
        if(!isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

        try {
            // Validate input
            $validated = $request->validate([
                'nama_barang' => 'required|string|max:255',
                'idMerek' => 'required|exists:merek_barang,idMerek',
                'kategori' => 'required',
                'harga_satuan' => 'required|string', // Will be sanitized
                'satuan' => 'required|integer',
                'status_produk' => 'required|in:0,1',
                'gambarProduk' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048', 'dimensions:min_width=200,min_height=200,max_width=1200,max_height=1920'],
                [
                    'gambarProduk.dimensions' => 'Resolusi gambar harus minimal 400x400px dan maksimal 1200x1200px.',
                ],
            ]);

            // Find the product
            $barang = Barang::findOrFail($idBarang);

            // Handle image upload if present
            if ($request->hasFile('gambarProduk')) {
                // Delete the old image if it exists
                if ($barang->gambarProduk) {
                    $oldImagePath = public_path('produk/' . $barang->gambarProduk);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Store the new image
                $file = $request->file('gambarProduk');
                $imageName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('produk'), $imageName);
                $barang->gambarProduk = $imageName;
            }

            // Sanitize harga_satuan (remove "Rp.", dots, spaces, etc.)
            $hargaJual = preg_replace('/[^\d]/', '', $request->input('harga_satuan'));

            // Update fields
            $barang->namaBarang = $validated['nama_barang'];
            $barang->merekBarang = $validated['idMerek'];
            $barang->kategoriBarang = $validated['kategori']; // If enum, cast in model or controller
            $barang->hargaJual = $hargaJual;
            $barang->satuan = $validated['satuan'];
            $barang->statusBarang = $validated['status_produk'];

            $barang->save();

            $owner = Akun::where('peran', 1)->get();

            foreach ($owner as $o) {
                Notifications::create([
                    'idAkun' => $o->idAkun,
                    'title' => 'Produk Diperbarui',
                    'message' => 'Produk telah Diperbarui.',
                    'data' => json_encode([
                        'nama_barang' => $barang->namaBarang,
                        'id_barang' => $barang->idBarang,
                        'added_by' => session('user_data')->nama,
                    ]),
                ]);
            }

            return redirect()->route('detail.produk', $barang->idBarang)->with('success', 'Data produk berhasil diperbarui.');
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error updating product: ' . $e->getMessage());

            // Redirect back with error message and old input
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function softDeleteBarangDetail($idBarang, $barcode)
    {
        if(!isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

        try {
            $detail = BarangDetail::where('idBarang', $idBarang)->where('barcode', $barcode)->first();

            if (!$detail) {
                return redirect()->back()->with('error', 'Barang detail tidak ditemukan.');
            }

            $detail->statusDetailBarang = 0;
            $detail->save();

            // Fetch the related Barang
            $barang = $detail->barang ?? \App\Models\Barang::where('idBarang', $idBarang)->first();

            $owner = Akun::where('peran', 1)->get();

            foreach ($owner as $o) {
                Notifications::create([
                    'idAkun' => $o->idAkun,
                    'title' => 'Barang Detail Dihapus',
                    'message' => 'Salah satu detail barang telah dihapus.',
                    'data' => json_encode([
                        'barcode' => $detail->barcode,
                        'nama_barang' => $barang ? $barang->namaBarang : '-',
                        'id_barang' => $barang ? $barang->idBarang : $idBarang,
                        'deleted_by' => session('user_data')->nama ?? 'Unknown',
                    ]),
                ]);
            }

            return redirect()->back()->with('success', 'Barang detail berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting BarangDetail: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus barang detail. Silakan coba lagi.');
        }
    }
    public function softUpdateBarangDetail($idBarang, $barcode)
    {
        if(!isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

        try {
            $detail = BarangDetail::where('idBarang', $idBarang)->where('barcode', $barcode)->first();

            if (!$detail) {
                return redirect()->back()->with('error', 'Barang detail tidak ditemukan.');
            }

            $detail->statusDetailBarang = 1;
            $detail->save();

            // Fetch the related Barang
            $barang = $detail->barang ?? \App\Models\Barang::where('idBarang', $idBarang)->first();

            // Notify all owners (peran = 1)
            $owners = \App\Models\Akun::where('peran', 1)->get();

            foreach ($owners as $owner) {
                Notifications::create([
                    'idAkun' => $owner->idAkun,
                    'title' => 'Barang Detail Dikembalikan',
                    'message' => 'Salah satu detail barang telah dikembalikan (restore).',
                    'data' => json_encode([
                        'nama_barang' => $barang ? $barang->namaBarang : '-',
                        'id_barang' => $barang ? $barang->idBarang : $idBarang,
                        'restored_by' => session('user_data')->nama ?? 'Unknown',
                    ]),
                ]);
            }

            return redirect()->back()->with('success', 'Barang detail berhasil dikembalikan.');
        } catch (\Exception $e) {
            Log::error('Error restoring BarangDetail: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengembalikan barang detail. Silakan coba lagi.');
        }
    }

    public function getKadaluarsaByBarcode(Request $request): JsonResponse
    {
        $barcode = $request->query('barcode');
        if (!$barcode) {
            return response()->json(['error' => 'Barcode is required'], 400);
        }

        // Find the latest kadaluarsa for this barcode (adjust logic as needed)
        $barangDetail = BarangDetail::where('barcode', $barcode)
            ->orderByDesc('tglKadaluarsa')
            ->first();

        if (!$barangDetail || !$barangDetail->tglKadaluarsa) {
            return response()->json(['kadaluarsa' => null]);
        }

        return response()->json([
            'kadaluarsa' => $barangDetail->tglKadaluarsa->format('Y-m-d')
        ]);
    }
}
