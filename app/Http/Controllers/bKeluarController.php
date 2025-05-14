<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangDetail;
use App\Models\bKeluar;
use App\Models\bKeluarDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class bKeluarController extends Controller
{
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

    public function buatBKeluar(Request $request) {
        
        DB::beginTransaction();

            $barangKeluar = new bKeluar();
            $barangKeluar->idBarangKeluar = bKeluar::generateNewIdBarangKeluar();
            $barangKeluar->invoice = bKeluar::generateNewInvoiceNumber();
            $barangKeluar->idAkun = 'A001';
            $barangKeluar->tglKeluar = now();
            $barangKeluar->save();

            foreach ($request->barang_keluar as $jsonItem) {
                $item = json_decode($jsonItem, true);

                // Create detail entry for barang keluar
                $detail = new bKeluarDetail();
                $detail->idDetailBK = bKeluarDetail::generateNewIdDetailBK();
                // $detail->barcode = $item['barcode'];
                $detail->idBarangKeluar = $barangKeluar->idBarangKeluar;
                $detail->idBarang = $item['barang_id'];
                $detail->jumlahKeluar = $item['kuantitas_keluar'];
                $detail->subtotal = $item['subtotal'];
                $detail->kategoriAlasan = $item['kategori_alasan'];
                $detail->keterangan = $item['keterangan'];
                $detail->save();
                
                $barang = BarangDetail::where('barcode', $item['barcode'])->first();

                if ($barang) {
                    $barang->quantity -= $detail->jumlahKeluar;
                    // dd($barang);
                    $barang->save();
                } else {
                    return redirect()->back()->with('error', 'Data barang tidak ditemukan.');
                }
            }

        DB::commit();
        return redirect()->back()->with('success', 'Data barang keluar berhasil disimpan.');
    }
}
