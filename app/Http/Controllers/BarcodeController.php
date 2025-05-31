<?php

namespace App\Http\Controllers;

use App\enum\KategoriBarang;
use App\enum\satuan;
use App\Models\Barang;
use App\Models\BarangDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    function viewDetailProduk($barcode) {

        Carbon::setLocale('id');
        
        $barang = Barang::with(['detailBarang', 'merek'])
            ->whereHas('detailBarang', function($query) use ($barcode) {
                $query->where('barcode', $barcode);
            })
            ->firstOrFail();

        // Correct enum comparison for satuan
        if (
            ($barang->satuan instanceof \App\enum\satuan && $barang->satuan->value == 2) ||
            $barang->satuan == 2 // fallback if not casted
        ) {
            $barang->totalStok = $barang->detailBarang->count();
        } else {
            $barang->totalStok = $barang->detailBarang->sum('quantity');
        }
        $scannedDetail = $barang->detailBarang->firstWhere('barcode', $barcode);

        $barang->merekBarangName = $barang->merek ? $barang->merek->namaMerek : 'Unknown';

        return view('barcode-scan', [
            'barang' => $barang,
            'scannedDetail' => $scannedDetail
        ]);
    }
}
