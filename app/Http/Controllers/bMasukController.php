<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangDetail;
use App\Models\bMasuk;
use App\Models\bMasukDetail;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class bMasukController extends Controller
{
    function viewBMasuk()
    {
        Carbon::setLocale('id');
        $bMasuk = bMasuk::with('detailMasuk')->paginate(10);

        $bMasuk->getCollection()->transform(function ($item) {
            $item->quantity = $item->detailMasuk->sum('jumlahMasuk');
            $item->hargaBeli = $item->detailMasuk->sum('hargaBeli');
            $item->total = $item->detailMasuk->sum('subtotal');
            $item->expiredDate = $item->detailMasuk->max('tglKadaluarsa');
            $item->barcode = $item->detailMasuk->max('barcode');

            return $item;
        });

        return view('menu.manajemen.list-bMasuk', ['bMasuk' => $bMasuk]);
    }
    function viewTambahBMasuk()
    {
        $suppliers = Supplier::pluck('nama');

        return view('menu.manajemen.bMasuk', compact('suppliers'));
    }

    function viewDetailBMasuk($idBarangMasuk) {
        Carbon::setLocale('id');

        $bMasuk = bMasuk::with('detailMasuk')
            ->where('idBarangMasuk', $idBarangMasuk)
            ->firstOrFail(); // Changed from first() to firstOrFail()


        return view('menu.manajemen.detail-bMasuk', compact('bMasuk'));
    }

    public function tambahBMasuk(Request $request)
    {
        try {
            DB::beginTransaction();

            // Simpan nota_file ke folder publik
            $notaPath = null;
            if ($request->hasFile('nota_file')) {
                // Buat nama file unik
                $notaName = time() . '_' . $request->file('nota_file')->getClientOriginalName();
                // Pindahkan ke folder public/assets/nota_file
                $request->file('nota_file')->move(public_path('nota_file'), $notaName);
                // Simpan hanya nama filenya, bukan path penuh
                $notaPath = $notaName;
            }

            // Buat entry utama barang masuk
            $barangMasuk = new bMasuk();
            $barangMasuk->idBarangMasuk = bMasuk::generateNewIdBarangMasuk();
            $barangMasuk->idSupplier = $request->supplier_id; // Bisa dari form
            $barangMasuk->idAkun = session('user_data')->idAkun; // Ambil dari session
            $barangMasuk->tglMasuk = $request->tglMasuk; // Bisa dari form
            $barangMasuk->nota = $notaPath; // Simpan nama file
            $barangMasuk->save();

            // Validasi dan simpan detail barang
            if (!$request->has('items') || !is_array($request->items)) {
                return response()->json(['error' => 'Data barang belum dimasukkan.'], 400);
            }

            foreach ($request->items as $item) {
                // Simpan detail barang masuk
                $detail = new bMasukDetail();
                $detail->idDetailBM = bMasukDetail::generateNewIdDetailBM();
                $detail->idBarangMasuk = $barangMasuk->idBarangMasuk;
                $detail->idBarang = $item['barang_id'];
                $detail->jumlahMasuk = $item['kuantitas_masuk'];
                $detail->hargaBeli = $item['harga_satuan'];
                $detail->subtotal = $item['harga_satuan'] * $item['kuantitas_masuk'];
                $detail->tglKadaluarsa = $item['tanggal_kadaluwarsa'];
                $detail->save();

                // Simpan ke stok detail
                $newDetail = new BarangDetail();
                $newDetail->idDetailBarang = BarangDetail::generateNewIdBarangDetail();
                $newDetail->idBarang = $item['barang_id'];
                $newDetail->idSupplier = $request->supplier_id; // Bisa dari form
                $newDetail->kondisiBarang = 'Baik';
                $newDetail->satuanBarang = 'PCS';
                $newDetail->quantity = $item['kuantitas_masuk'];
                $newDetail->hargaBeli = $item['harga_satuan'];
                $newDetail->tglMasuk = $item['tanggal_masuk'];
                $newDetail->tglKadaluarsa = $item['tanggal_kadaluwarsa'];
                $newDetail->barcode = BarangDetail::generateBarcode();
                $newDetail->save();
            }

            DB::commit();
            return redirect()->route('barang-masuk')->with('success', 'Barang Masuk berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
