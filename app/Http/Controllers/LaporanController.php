<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\bKeluar;
use App\Models\bKeluarDetail;
use App\Models\bMasuk;
use App\Models\bMasukDetail;
use App\Models\bRetur;
use App\Models\bReturDetail;
use App\Models\bRusak;
use App\Models\bRusakDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    function viewbMasuk()
    {
        Carbon::setLocale('id');

        if(!isOwner() ||  !isUserLoggedIn()){

            abort(403, 'Unauthorized action.');
        }

        // $bMasuk = bMasuk::with('detailMasuk.barangDetail.barang')->OrderBy('tglMasuk', 'desc')->paginate(5);
        $details = bMasukDetail::with(['barangMasuk', 'barangDetail.barang'])
            ->join('barang_masuk as bm', 'detail_barang_masuk.idBarangMasuk', '=', 'bm.idBarangMasuk')
            ->orderBy('bm.tglMasuk', 'desc')
            ->select('detail_barang_masuk.*')
            ->paginate(10);

        return view('menu.laporan.bMasuk', ['details' => $details]);
    }

    function searchBMasuk(Request $request)
    {
        Carbon::setLocale('id');
        
        if(!isOwner() ||  !isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

        $query = bMasukDetail::with(['barangMasuk', 'barangDetail.barang'])
            ->join('barang_masuk as bm', 'detail_barang_masuk.idBarangMasuk', '=', 'bm.idBarangMasuk')
            ->select('detail_barang_masuk.*');

        // Filter by date range if provided
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('bm.tglMasuk', [$request->tanggal_awal, $request->tanggal_akhir]);
        } elseif ($request->filled('tanggal_awal')) {
            $query->where('bm.tglMasuk', '>=', $request->tanggal_awal);
        } elseif ($request->filled('tanggal_akhir')) {
            $query->where('bm.tglMasuk', '<=', $request->tanggal_akhir);
        }

        // Search by keyword (e.g., namaBarang or idBarang)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('barangDetail.barang', function ($q) use ($search) {
                $q->where('namaBarang', 'like', "%$search%")
                ->orWhere('idBarang', 'like', "%$search%");
            });
        }

        $details = $query->orderBy('bm.tglMasuk', 'desc')->paginate(10);

        return view('menu.laporan.bMasuk', ['details' => $details]);
    }

    function viewbKeluar()
    {
        Carbon::setLocale('id');

        if(!isOwner() ||  !isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

        $details = bKeluarDetail::with(['barangKeluar', 'barangDetailKeluar.barang'])
            ->join('barang_keluar as bk', 'detail_barang_keluar.idBarangKeluar', '=', 'bk.idBarangKeluar')
            ->orderBy('bk.tglKeluar', 'desc')
            ->select('detail_barang_keluar.*')
            ->paginate(10);

        return view('menu.laporan.bKeluar', ['details' => $details]);
    }

    function searchBKeluar(Request $request)
    {
        Carbon::setLocale('id');

        if(!isOwner()||  !isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

        $query = bKeluarDetail::with(['barangKeluar', 'barangDetailKeluar.barang'])
            ->join('barang_keluar as bk', 'detail_barang_keluar.idBarangKeluar', '=', 'bk.idBarangKeluar')
            ->select('detail_barang_keluar.*');

        // Filter by date range if provided
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('bk.tglKeluar', [$request->tanggal_awal, $request->tanggal_akhir]);
        } elseif ($request->filled('tanggal_awal')) {
            $query->where('bk.tglKeluar', '>=', $request->tanggal_awal);
        } elseif ($request->filled('tanggal_akhir')) {
            $query->where('bk.tglKeluar', '<=', $request->tanggal_akhir);
        }

        // Search by keyword (e.g., namaBarang or idBarang)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('barangDetailKeluar.barang', function ($q) use ($search) {
                $q->where('namaBarang', 'like', "%$search%")
                ->orWhere('idBarang', 'like', "%$search%");
            });
        }

        $details = $query->orderBy('bk.tglKeluar', 'desc')->paginate(10);

        return view('menu.laporan.bKeluar', ['details' => $details]);
    }

    function viewStokBarang()
    {
        Carbon::setLocale('id');

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
        Carbon::setLocale('id');
        
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

    function viewbRetur()
    {
        Carbon::setLocale('id');

        if(!isOwner() ||  !isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

        $details = bReturDetail::with(['returBarang', 'barang'])
            ->join('retur_barang as rb', 'detail_retur_barang.idBarangRetur', '=', 'rb.idBarangRetur')
            ->orderBy('rb.tglRetur', 'desc')
            ->select('detail_retur_barang.*')
            ->paginate(10);

        return view('menu.laporan.bRetur', ['details' => $details]);
    }

    function searchBRetur(Request $request)
    {
        Carbon::setLocale('id');

        if(!isOwner() ||  !isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

        $query = bReturDetail::with(['returBarang', 'barang'])
            ->join('retur_barang as rb', 'detail_retur_barang.idBarangRetur', '=', 'rb.idBarangRetur')
            ->select('detail_retur_barang.*');

        // Filter by date range if provided
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('rb.tglRetur', [$request->tanggal_awal, $request->tanggal_akhir]);
        } elseif ($request->filled('tanggal_awal')) {
            $query->where('rb.tglRetur', '>=', $request->tanggal_awal);
        } elseif ($request->filled('tanggal_akhir')) {
            $query->where('rb.tglRetur', '<=', $request->tanggal_akhir);
        }

        // Search by keyword (e.g., barcode or namaBarang)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('barang', function ($q) use ($search) {
                $q->where('namaBarang', 'like', "%$search%")
                ->orWhere('barcode', 'like', "%$search%");
            });
        }

        $details = $query->orderBy('rb.tglRetur', 'desc')->paginate(10);

        return view('menu.laporan.bRetur', ['details' => $details]);
    }
    function viewbRusak()
    {
        Carbon::setLocale('id');
        if(!isOwner() ||  !isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

        $details = bRusakDetail::with(['rusakBarang', 'barang'])
            ->join('barang_rusak as br', 'detail_barang_rusak.idBarangRusak', '=', 'br.idBarangRusak')
            ->orderBy('br.tglRusak', 'desc')
            ->select('detail_barang_rusak.*')
            ->paginate(10);

        return view('menu.laporan.bRusak', ['details' => $details]);
    }

    function searchBRusak(Request $request)
    {
        Carbon::setLocale('id');
        if(!isOwner() ||  !isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

        $query = bRusakDetail::with(['rusakBarang', 'barang'])
            ->join('barang_rusak as br', 'detail_barang_rusak.idBarangRusak', '=', 'br.idBarangRusak')
            ->select('detail_barang_rusak.*');

        // Filter by date range if provided
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('br.tglRusak', [$request->tanggal_awal, $request->tanggal_akhir]);
        } elseif ($request->filled('tanggal_awal')) {
            $query->where('br.tglRusak', '>=', $request->tanggal_awal);
        } elseif ($request->filled('tanggal_akhir')) {
            $query->where('br.tglRusak', '<=', $request->tanggal_akhir);
        }

        // Search by keyword (e.g., namaBarang or barcode)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('barang', function ($q) use ($search) {
                $q->where('namaBarang', 'like', "%$search%")
                ->orWhere('barcode', 'like', "%$search%");
            });
        }

        $details = $query->orderBy('br.tglRusak', 'desc')->paginate(10);

        return view('menu.laporan.bRusak', ['details' => $details]);
    }
}
