<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangDetail;
use App\Models\bKeluar;
use App\Models\bKeluarDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class bKeluarController extends Controller
{
    function viewBKeluar() {
        $bKeluar = bKeluar::with('detailKeluar')->get();
        return view('menu.manajemen.list-bKeluar', ['bKeluar' => $bKeluar]);
    }

    function viewDetailBKeluar($idBarangKeluar) {
        Carbon::setLocale('id');

        $bKeluar = bKeluar::with('detailKeluar')
            ->where('idBarangKeluar', $idBarangKeluar)
            ->firstOrFail(); // Changed from first() to firstOrFail()


        return view('menu.manajemen.detail-bKeluar', compact('bKeluar'));
    }

    function viewBuatBKeluar() {
        return view('menu.manajemen.bKeluar');
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

                    if ($barang->quantity <= 0) {
                        $barang->statusDetailBarang = 0;
                    }
                    $barang->save();
                } else {
                    return redirect()->back()->with('error', 'Data barang tidak ditemukan.');
                }
            }

        DB::commit();
        return redirect()->back()->with('success', 'Data barang keluar berhasil disimpan.');
    }
}
