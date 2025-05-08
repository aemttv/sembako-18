<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\bMasuk;
use App\Models\bMasukDetail;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class bMasukController extends Controller
{
    function viewbMasuk() {

        $suppliers = Supplier::pluck('nama');

        return view('menu.manajemen.bMasuk', compact('suppliers'));
    }

    public function tambahBMasuk(Request $request) {
        // Start of - prevent partial from being saved when something goes wrong
        DB::beginTransaction();
    
        try {
            // Create main bMasuk entry
            $barangMasuk = new bMasuk();

            foreach ($request->barang_masuk as $jsonItem) {
                $item = json_decode($jsonItem, true);
            
                $barangMasuk->idBarangMasuk = bMasuk::generateNewIdBarangMasuk();
                $barangMasuk->idSupplier = $item['supplier_id'];
                $barangMasuk->idAkun = 'A001';
                $barangMasuk->tglMasuk = now();
            }

            $barangMasuk->save();

            foreach ($request->barang_masuk as $jsonItem) {
                $item = json_decode($jsonItem, true);
            
                // Create detail
                $detail = new bMasukDetail();
                $detail->idDetailBM = bMasukDetail::generateNewIdDetailBM();
                $detail->idBarangMasuk = $barangMasuk->idBarangMasuk;
                $detail->idBarang = $item['barang_id'];
                $detail->jumlahMasuk = $item['kuantitas_masuk'];
                $detail->hargaBeli = $item['harga_satuan'];
                $detail->subtotal = $item['harga_satuan'] * $item['kuantitas_masuk'];
                $detail->tglKadaluarsa = $item['tanggal_kadaluwarsa'];
                $detail->save();
                // @dd($detail);
            
                // Update stock
                $barang = Barang::find($item['barang_id']);
                if ($barang) {
                    $barang->stokBarangCurrent += $item['kuantitas_masuk'];
                    $barang->save();
                }
            }
            
    
            // Commit the transaction
            DB::commit();
            // return response()->json(['success' => 'Barang Masuk berhasil disimpan'], 200);
            return redirect()->route('barang-masuk')->with('success', 'Barang Masuk berhasil disimpan');
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollback();
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
