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
        if(!isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

        Carbon::setLocale('id');
        $bMasuk = bMasuk::with('detailMasuk')->latest()->paginate(10);
        $bMasukStaff = bMasuk::with('detailMasuk', 'akun')->where('idAkun', session('idAkun'))->latest()->paginate(10);

        $bMasuk->getCollection()->transform(function ($item) {
            $item->quantity = $item->detailMasuk->sum('jumlahMasuk');
            $item->hargaBeli = $item->detailMasuk->sum('hargaBeli');
            $item->total = $item->detailMasuk->sum('subtotal');
            $item->expiredDate = $item->detailMasuk->max('tglKadaluarsa');
            $item->barcode = $item->detailMasuk->max('barcode');

            return $item;
        });
        $bMasukStaff->getCollection()->transform(function ($item) {
            $item->quantity = $item->detailMasuk->sum('jumlahMasuk');
            $item->hargaBeli = $item->detailMasuk->sum('hargaBeli');
            $item->total = $item->detailMasuk->sum('subtotal');
            $item->expiredDate = $item->detailMasuk->max('tglKadaluarsa');
            $item->barcode = $item->detailMasuk->max('barcode');

            return $item;
        });

        return view('menu.manajemen.list-bMasuk', ['bMasuk' => $bMasuk, 'bMasukStaff' => $bMasukStaff]);
    }
    function viewTambahBMasuk()
    {
        if(!isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

        $suppliers = Supplier::pluck('nama');

        return view('menu.manajemen.bMasuk', compact('suppliers'));
    }

    public function searchList(Request $request)
    {
        \Carbon\Carbon::setLocale('id');
        $search = $request->input('q');

        $bMasukQuery = \App\Models\bMasuk::with('detailMasuk');

        // Example: search by idBarangMasuk or idSupplier (adjust fields as needed)
        if ($search) {
            $bMasukQuery->where(function ($query) use ($search) {
                $query->where('idBarangMasuk', 'like', '%' . $search . '%')
                      ->orWhere('idSupplier', 'like', '%' . $search . '%');
            });
        }

        $bMasuk = $bMasukQuery->paginate(10)->appends(['q' => $search]);

        $bMasuk->getCollection()->transform(function ($item) {
            $item->quantity = $item->detailMasuk->sum('jumlahMasuk');
            $item->hargaBeli = $item->detailMasuk->sum('hargaBeli');
            $item->total = $item->detailMasuk->sum('subtotal');
            $item->expiredDate = $item->detailMasuk->max('tglKadaluarsa');
            $item->barcode = $item->detailMasuk->max('barcode');
            return $item;
        });

        return view('menu.manajemen.list-bMasuk', [
            'bMasuk' => $bMasuk,
            'search' => $search,
        ]);
    }

    function viewDetailBMasuk($idBarangMasuk)
    {
        Carbon::setLocale('id');

        $bMasuk = bMasuk::with('detailMasuk.barangDetail.barang')->where('idBarangMasuk', $idBarangMasuk)->firstOrFail(); // Changed from first() to firstOrFail()

        return view('menu.manajemen.detail-bMasuk', compact('bMasuk'));
    }

    public function tambahBMasuk(Request $request)
    {
        try {
            $request->validate(
                [
                    'nota_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048', 'dimensions:min_width=400,min_height=400,max_width=1200,max_height=1200'],
                ],
                [
                    'nota_file.dimensions' => 'Resolusi gambar harus minimal 400x400px dan maksimal 1200x1200px.',
                ],
            );

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
                return redirect()
                ->back()
                ->with('error', 'Data barang masuk masih kosong. Silakan tambahkan barang terlebih dahulu.');
            }

            foreach ($request->items as $item) {

                $barang = Barang::find($item['barang_id']);
                if (!$barang) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Barang tidak ditemukan.')->withInput();
                }

                // Logic subtotal: jika satuan == 2 (kg), jumlah masuk dianggap 1
                // if ($barang->satuan->value == 2) { // 2 = kg
                //     $jumlahMasuk = $item['kuantitas_masuk'];
                //     $subtotal = $item['harga_satuan'] * 1;
                // } else {
                //     $jumlahMasuk = $item['kuantitas_masuk'];
                //     $subtotal = $item['harga_satuan'];
                // }

                // Simpan detail barang masuk
                $detail = new bMasukDetail();
                $detail->idDetailBM = bMasukDetail::generateNewIdDetailBM();
                $detail->idBarangMasuk = $barangMasuk->idBarangMasuk;
                $detail->idBarang = $item['barang_id'];
                $detail->jumlahMasuk = $item['kuantitas_masuk'];
                $detail->hargaBeli = $item['harga_satuan'];
                $detail->subtotal = $detail->hargaBeli;
                $detail->tglKadaluarsa = $item['tanggal_kadaluwarsa'];
                $detail->save();

                // Simpan ke stok detail
                $newDetail = new BarangDetail();
                $newDetail->idDetailBarang = BarangDetail::generateNewIdBarangDetail();
                $newDetail->idBarang = $item['barang_id'];
                $newDetail->idSupplier = $request->supplier_id; // Bisa dari form
                $newDetail->kondisiBarang = 'Baik';
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
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan barang masuk. Silakan coba lagi.');
        }
    }
}
