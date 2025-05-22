<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangDetail;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    function viewDetailProduk($barcode) {
    $barang = Barang::with(['detailBarang', 'merek'])
        ->whereHas('detailBarang', function($query) use ($barcode) {
            $query->where('barcode', $barcode);
        })
        ->firstOrFail();

    $scannedDetail = $barang->detailBarang->firstWhere('barcode', $barcode);
    
    $barang->totalStok = $barang->detailBarang->sum('quantity');
    $barang->merekBarangName = $barang->merek ? $barang->merek->namaMerek : 'Unknown';

    return view('barcode-scan', [
        'barang' => $barang,
        'scannedDetail' => $scannedDetail
    ]);
}
}
