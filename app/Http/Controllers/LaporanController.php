<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\bKeluar;
use App\Models\bMasuk;
use App\Models\bRetur;
use App\Models\bRusak;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    function viewbMasuk()
    {
        if(!isOwner() ||  !isUserLoggedIn()){

            abort(403, 'Unauthorized action.');
        }

        $bMasuk = bMasuk::with('detailMasuk.barangDetail.barang')->OrderBy('tglMasuk', 'desc')->paginate(5);


        return view('menu.laporan.bMasuk', ['bMasuk' => $bMasuk]);
    }

    function searchBMasuk(Request $request)
    {

        if(!isOwner() ||  !isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

        $query = bMasuk::with('detailMasuk.barangDetail.barang');

        // Filter by date range if provided
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tglMasuk', [$request->tanggal_awal, $request->tanggal_akhir]);
        } elseif ($request->filled('tanggal_awal')) {
            $query->where('tglMasuk', '>=', $request->tanggal_awal);
        } elseif ($request->filled('tanggal_akhir')) {
            $query->where('tglMasuk', '<=', $request->tanggal_akhir);
        }

        // Search by keyword (e.g., namaBarang or idBarang)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->with([
                'detailMasuk' => function ($q) use ($search) {
                    $q->whereHas('barangDetail.barang', function ($q2) use ($search) {
                        $q2->where('namaBarang', 'like', "%$search%")->orWhere('idBarang', 'like', "%$search%");
                    });
                },
            ]);
        }

        $bMasuk = $query->paginate(6);

        return view('menu.laporan.bMasuk', compact('bMasuk'));
    }

    function viewbKeluar()
    {
        if(!isOwner() ||  !isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

        $bKeluar = bKeluar::with('detailKeluar.barangDetailKeluar.barang')->OrderBy('tglKeluar', 'desc')->paginate(5);

        return view('menu.laporan.bKeluar', ['bKeluar' => $bKeluar]);
    }

    function searchBKeluar(Request $request)
    {
        if(!isOwner()||  !isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

        $query = bKeluar::with('detailKeluar.barangDetailKeluar.barang');

        // Filter by date range if provided
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tglKeluar', [$request->tanggal_awal, $request->tanggal_akhir]);
        } elseif ($request->filled('tanggal_awal')) {
            $query->where('tglKeluar', '>=', $request->tanggal_awal);
        } elseif ($request->filled('tanggal_akhir')) {
            $query->where('tglKeluar', '<=', $request->tanggal_akhir);
        }

        // Search by keyword (e.g., namaBarang or idBarang)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->with([
                'detailKeluar' => function ($q) use ($search) {
                    $q->whereHas('barangDetailKeluar.barang', function ($q2) use ($search) {
                        $q2->where('namaBarang', 'like', "%$search%")->orWhere('idBarang', 'like', "%$search%");
                    });
                },
            ]);
        }

        $bKeluar = $query->paginate(6);

        return view('menu.laporan.bKeluar', compact('bKeluar'));
    }

    function viewStokBarang()
    {
        if(!isOwner() || !isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

        $barang = Barang::with([
            'detailBarang' => function ($query) {
                $query->where('statusDetailBarang', 1);
            },
            'merek',
        ])->paginate(10);

        // Transform and set the collection back to the paginator
        $barang->setCollection(
            $barang->getCollection()->transform(function ($item) {
                // Dynamic total stock from detailBarang
                $item->totalStok = $item->detailBarang->sum('quantity');

                // Access the 'merek' relationship and add a custom attribute
                $item->merekBarangName = $item->merek ? $item->merek->namaMerek : 'Unknown';

                return $item;
            }),
        );

        return view('menu.laporan.stok', ['barang' => $barang]);
    }

    public function searchStokBarang(Request $request)
    {
        if(!isOwner() ||  !isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

        // Start with the query builder, not paginate yet!
        $query = Barang::with([
            'detailBarang' => function ($query) {
                $query->where('statusDetailBarang', 1);
            },
            'merek',
        ]);

        // Filter by date range if provided
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereHas('detailBarang', function ($q) use ($request) {
                $q->whereBetween('tglMasuk', [$request->tanggal_awal, $request->tanggal_akhir]);
            });
        } elseif ($request->filled('tanggal_awal')) {
            $query->whereHas('detailBarang', function ($q) use ($request) {
                $q->where('tglMasuk', '>=', $request->tanggal_awal);
            });
        } elseif ($request->filled('tanggal_akhir')) {
            $query->whereHas('detailBarang', function ($q) use ($request) {
                $q->where('tglMasuk', '<=', $request->tanggal_akhir);
            });
        }

        // Search by keyword (e.g., namaBarang or idBarang)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('namaBarang', 'like', "%$search%")->orWhere('idBarang', 'like', "%$search%");
            });
        }

        // Now paginate
        $barang = $query->paginate(6);

        // Transform the paginated collection
        $barang->setCollection(
            $barang->getCollection()->transform(function ($item) {
                // Dynamic total stock from detailBarang
                $item->totalStok = $item->detailBarang->sum('quantity');

                // Determine kondisiBarangText based on string value in detailBarang
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

                $tglMasuk = $item->detailBarang->max('tglMasuk');

                return $item;
            }),
        );

        return view('menu.laporan.stok', compact('barang'));
    }

    function viewbRetur() {

        if(!isOwner() ||  !isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

        $bRetur = bRetur::with('detailRetur.detailBarangRetur.barang')
        ->orderBy('tglRetur', 'desc')
        ->orderBy('idBarangRetur', 'desc')
        ->paginate(5);

        return view('menu.laporan.bRetur', ['bRetur' => $bRetur]);
    }

    function searchBRetur(Request $request) {

        if(!isOwner() ||  !isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

        $query = bRetur::with('detailRetur.detailBarangRetur.barang', 'supplier');

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tglRetur', [$request->tanggal_awal, $request->tanggal_akhir]);
        } elseif ($request->filled('tanggal_awal')) {
            $query->where('tglRetur', '>=', $request->tanggal_awal);
        } elseif ($request->filled('tanggal_akhir')) {
            $query->where('tglRetur', '<=', $request->tanggal_akhir);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->with([
                'detailRetur' => function ($q) use ($search) {
                    $q->whereHas('detailBarangRetur.barang', function ($q2) use ($search) {
                        $q2->where('namaBarang', 'like', "%$search%")->orWhere('barcode', 'like', "%$search%");
                    });
                },
            ]);
        }

        $bRetur = $query->OrderBy('tglRetur', 'desc')->paginate(6);


        return view('menu.laporan.bRetur', ['bRetur' => $bRetur]);
    }

    function viewbRusak() {

        if(!isOwner() ||  !isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

        $bRusak = bRusak::with('detailRusak.detailBarangRusak.barang')
        ->OrderBy('tglRusak', 'desc')
        ->OrderBy('idBarangRusak', 'desc')
        ->paginate(6);

        return view('menu.laporan.bRusak', ['bRusak' => $bRusak]);
    }

    function searchBRusak(Request $request) {

        if(!isOwner() ||  !isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

        $query = bRusak::with('detailRusak.detailBarangRusak.barang');

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tglRusak', [$request->tanggal_awal, $request->tanggal_akhir]);
        } elseif ($request->filled('tanggal_awal')) {
            $query->where('tglRusak', '>=', $request->tanggal_awal);
        } elseif ($request->filled('tanggal_akhir')) {
            $query->where('tglRusak', '<=', $request->tanggal_akhir);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->with([
                'detailRusak' => function ($q) use ($search) {
                    $q->whereHas('detailBarangRusak.barang', function ($q2) use ($search) {
                        $q2->where('namaBarang', 'like', "%$search%")->orWhere('barcode', 'like', "%$search%");
                    });
                },
            ]);
        }

        $bRusak = $query->OrderBy('tglRusak', 'desc')->paginate(6);

        return view('menu.laporan.bRusak', ['bRusak' => $bRusak]);
    }
}
