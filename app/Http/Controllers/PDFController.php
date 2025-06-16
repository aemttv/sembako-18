<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Barang;
use App\Models\bKeluar;
use App\Models\bMasuk;
use App\Models\bRetur;
use App\Models\bRusak;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    function streamPDFbMasuk(Request $request)
    {

        if(!isOwner()){
            abort(403, 'Unauthorized action.');
        }

        $query = bMasuk::with('detailMasuk.barangDetail.barang');

        $tglMasuk = $request->input('tanggal_awal');
        $tglAkhir = $request->input('tanggal_akhir');

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

        $bMasuk = $query->get();

        // Calculate grand total from all subtotal fields in detailMasuk
        $grandTotal = 0;
        foreach ($bMasuk as $data) {
            foreach ($data->detailMasuk as $detail) {
                $grandTotal += $detail->subtotal;
            }
        }

        // Collect unique supplier IDs and akun IDs from $bRetur
        $idSuppliers = $bMasuk->pluck('idSupplier')->unique()->filter();
        $idAkuns = $bMasuk->pluck('idAkun')->unique()->filter();

        // Fetch names from DB (Supplier and Akun models)
        $suppliers = Supplier::whereIn('idSupplier', $idSuppliers)->pluck('nama', 'idSupplier');
        $akuns = Akun::whereIn('idAkun', $idAkuns)->pluck('nama', 'idAkun');

        // Prepare separate arrays
        $supplierList = [];
        foreach ($idSuppliers as $idSupplier) {
            $supplierList[] = [
                'idSupplier' => $idSupplier,
                'namaSupplier' => $suppliers[$idSupplier] ?? '-',
            ];
        }

        $akunList = [];
        foreach ($idAkuns as $idAkun) {
            $akunList[] = [
                'idAkun' => $idAkun,
                'namaAkun' => $akuns[$idAkun] ?? '-',
            ];
        }

        $pdf = Pdf::loadView('menu.laporan.pdf.bMasuk', compact('bMasuk', 'tglMasuk', 'tglAkhir', 'grandTotal', 'supplierList', 'akunList'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('laporan-bmasuk.pdf');
    }

    function streamPDFbKeluar(Request $request)
    {

        if(!isOwner()){
            abort(403, 'Unauthorized action.');
        }

        $query = bKeluar::with('detailKeluar.barangDetailKeluar.barang');

        $tglMasuk = $request->input('tanggal_awal');
        $tglAkhir = $request->input('tanggal_akhir');

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

        $bKeluar = $query->get();

        // Calculate grand total from all subtotal fields in detailMasuk
        $grandTotal = 0;
        foreach ($bKeluar as $data) {
            foreach ($data->detailKeluar as $detail) {
                $grandTotal += $detail->subtotal;
            }
        }

        // Collect unique IDs
        $idSuppliers = $bKeluar->pluck('idSupplier')->unique();
        $idAkuns = $bKeluar->pluck('idAkun')->unique();

        // Fetch names from DB (Supplier and Akun models)
        $akuns = Akun::whereIn('idAkun', $idAkuns)->pluck('nama', 'idAkun');

        // Prepare the legend list
        $supplierAkunList = [];
        foreach ($bKeluar as $data) {
            $supplierAkunList[] = [
                'idAkun' => $data->idAkun,
                'namaAkun' => $akuns[$data->idAkun] ?? '-',
            ];
        }
        //  make unique by idSupplier+idAkun
        $supplierAkunList = collect($supplierAkunList)
            ->unique(function ($item) {
                return $item['idAkun'];
            })
            ->values();

        $pdf = Pdf::loadView('menu.laporan.pdf.bKeluar', compact('bKeluar', 'tglMasuk', 'tglAkhir', 'grandTotal', 'supplierAkunList'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('laporan-bkeluar.pdf');
    }

    function streamPDFStokBarang(Request $request)
    {

        if(!isOwner()){
            abort(403, 'Unauthorized action.');
        }

        $query = Barang::with([
            'detailBarang' => function ($query) {
                $query->where('statusDetailBarang', 1);
            },
            'merek',
        ]);

        $tglMasuk = $request->input('tanggal_awal');
        $tglAkhir = $request->input('tanggal_akhir');

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

        $barang = $query->paginate(10);

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

                return $item;
            }),
        );

        // Calculate grand total from all subtotal fields in detailMasuk
        $grandTotal = 0;
        foreach ($barang as $data) {
            foreach ($data->detailBarang as $detail) {
                $grandTotal += $detail->quantity * $data->hargaJual;
            }
        }

        $pdf = Pdf::loadView('menu.laporan.pdf.stok', compact('barang', 'grandTotal', 'tglMasuk', 'tglAkhir'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('laporan-stok-barang.pdf');
    }

    function streamPDFbRetur(Request $request)
    {

        if(!isOwner()){
            abort(403, 'Unauthorized action.');
        }

        $query = bRetur::with('detailRetur.detailBarangRetur.barang', 'supplier')->where('statusRetur', 1)->orWhere('statusRetur', 0);

        $tglMasuk = $request->input('tanggal_awal');
        $tglAkhir = $request->input('tanggal_akhir');

        // Filter by date range if provided
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tglRetur', [$request->tanggal_awal, $request->tanggal_akhir]);
        } elseif ($request->filled('tanggal_awal')) {
            $query->where('tglRetur', '>=', $request->tanggal_awal);
        } elseif ($request->filled('tanggal_akhir')) {
            $query->where('tglRetur', '<=', $request->tanggal_akhir);
        }

        // Search by keyword (e.g., namaBarang or idBarang)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('detailRetur.detailBarangRetur.barang', function ($q) use ($search) {
                $q->where('namaBarang', 'like', "%$search%")->orWhere('barcode', 'like', "%$search%");
            });
        }

        $bRetur = $query->get();

        // Collect unique supplier IDs and akun IDs from $bRetur
        $idSuppliers = $bRetur->pluck('idSupplier')->unique()->filter();
        $idAkuns = $bRetur->pluck('penanggungJawab')->unique()->filter();

        // Fetch names from DB (Supplier and Akun models)
        $suppliers = Supplier::whereIn('idSupplier', $idSuppliers)->pluck('nama', 'idSupplier');
        $akuns = Akun::whereIn('idAkun', $idAkuns)->pluck('nama', 'idAkun');

        // Prepare separate arrays
        $supplierList = [];
        foreach ($idSuppliers as $idSupplier) {
            $supplierList[] = [
                'idSupplier' => $idSupplier,
                'namaSupplier' => $suppliers[$idSupplier] ?? '-',
            ];
        }

        $akunList = [];
        foreach ($idAkuns as $idAkun) {
            $akunList[] = [
                'idAkun' => $idAkun,
                'namaAkun' => $akuns[$idAkun] ?? '-',
            ];
        }

        // dd($supplierList, $akunList);

        $pdf = Pdf::loadView('menu.laporan.pdf.bRetur', compact('bRetur', 'tglMasuk', 'tglAkhir', 'supplierList', 'akunList'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('laporan-bRetur.pdf');
    }

    function streamPDFbRusak(Request $request)
    {

        if(!isOwner()){
            abort(403, 'Unauthorized action.');
        }

        $query = bRusak::with('detailRusak.detailBarangRusak.barang')->where('statusRusak', 1)->orWhere('statusRusak', 0);

        $tglMasuk = $request->input('tanggal_awal');
        $tglAkhir = $request->input('tanggal_akhir');

        // Filter by date range if provided
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tglRusak', [$request->tanggal_awal, $request->tanggal_akhir]);
        } elseif ($request->filled('tanggal_awal')) {
            $query->where('tglRusak', '>=', $request->tanggal_awal);
        } elseif ($request->filled('tanggal_akhir')) {
            $query->where('tglRusak', '<=', $request->tanggal_akhir);
        }

        // Search by keyword (e.g., namaBarang or idBarang)
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

        $bRusak = $query->orderBy('tglRusak', 'desc')->get();

        $idAkuns = $bRusak->pluck('penanggungJawab')->unique();

        // Fetch names from DB (Akun models)
        $akuns = Akun::whereIn('idAkun', $idAkuns)->pluck('nama', 'idAkun');

        // Prepare the legend list
        $AkunList = [];
        foreach ($bRusak as $data) {
            $AkunList[] = [
                'idAkun' => $data->penanggungJawab,
                'namaAkun' => $akuns[$data->penanggungJawab] ?? '-',
            ];
        }
        $AkunList = collect($AkunList)
            ->unique(function ($item) {
                return $item['idAkun'];
            })
            ->values();

        // Prepare BarcodeList from related Barang models
        $BarcodeList = [];
        foreach ($bRusak as $data) {
            if ($data->detailRusak) {
                foreach ($data->detailRusak as $detail) {
                    if ($detail->detailBarangRusak && $detail->detailBarangRusak->barang) {
                        $barang = $detail->detailBarangRusak->barang;
                        $detilBarang = $detail->detailBarangRusak;
                        // dump($barang->toArray());
                        $BarcodeList[] = [
                            'barcode' => $detilBarang->barcode ?? '-',
                            'namaBarang' => $barang->namaBarang ?? '-',
                        ];
                    }
                }
            }
        }

        // Make unique by barcode
        $BarcodeList = collect($BarcodeList)
            ->unique(function ($item) {
                return $item['barcode'];
            })
            ->values();

        $pdf = Pdf::loadView('menu.laporan.pdf.bRusak', compact('bRusak', 'tglMasuk', 'tglAkhir', 'AkunList', 'BarcodeList'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('laporan-bRusak.pdf');
    }
}
