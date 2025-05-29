<?php

namespace App\Http\Controllers;

use App\enum\Alasan;
use App\enum\KategoriBarang;
use App\Models\Akun;
use App\Models\Barang;
use App\Models\BarangDetail;
use App\Models\bKeluar;
use App\Models\bKeluarDetail;
use App\Models\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class bKeluarController extends Controller
{
    function viewBKeluar()
    {
        Carbon::setLocale('id');
        $bKeluar = bKeluar::with('detailKeluar')->paginate(10);
        return view('menu.manajemen.list-bKeluar', ['bKeluar' => $bKeluar]);
    }

    public function viewDetailBKeluar($idBarangKeluar)
    {
        Carbon::setLocale('id');

        $bKeluar = bKeluar::with(['detailKeluar.barangDetailKeluar.barang'])
            ->where('idBarangKeluar', $idBarangKeluar)
            ->firstOrFail();

        $kategoriAlasan = Alasan::cases();

        return view('menu.manajemen.detail-bKeluar', compact('bKeluar', 'kategoriAlasan'));
    }

    function viewBuatBKeluar()
    {
        return view('menu.manajemen.bKeluar');
    }

    public function searchList(Request $request)
    {
        \Carbon\Carbon::setLocale('id');
        $search = $request->input('q');

        $bKeluarQuery = bKeluar::with('detailKeluar');

        if ($search) {
            $bKeluarQuery->where(function ($query) use ($search) {
                $query
                    ->where('invoice', 'like', '%' . $search . '%')
                    ->orWhere('idAkun', 'like', '%' . $search . '%')
                    ->orWhere('idBarangKeluar', 'like', '%' . $search . '%');
            });
        }

        $bKeluar = $bKeluarQuery->paginate(10)->appends(['q' => $search]);

        return view('menu.manajemen.list-bKeluar', [
            'bKeluar' => $bKeluar,
            'search' => $search,
        ]);
    }
    public function buatBKeluar(Request $request)
    {
        try {
            DB::beginTransaction();

            $barangKeluar = new bKeluar();
            $barangKeluar->idBarangKeluar = bKeluar::generateNewIdBarangKeluar();
            $barangKeluar->invoice = bKeluar::generateNewInvoiceNumber();
            $barangKeluar->idAkun = session('user_data')->idAkun;
            $barangKeluar->tglKeluar = $request->tanggal_keluar;
            $barangKeluar->save();

            foreach ($request->barang_keluar as $jsonItem) {
                $item = json_decode($jsonItem, true);

                // Create detail entry for barang keluar
                $detail = new bKeluarDetail();
                $detail->idDetailBK = bKeluarDetail::generateNewIdDetailBK();
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
                    if ($barang->quantity <= 0) {
                        $barang->statusDetailBarang = 0;
                    }
                    $barang->save();

                    // Collect item names and IDs for notification
                    $itemNames[] = $barang->namaBarang ?? 'Barang Tidak Diketahui';
                    $itemIds[] = $barang->idBarang ?? null;
                } else {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Data barang tidak ditemukan.');
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Data barang keluar berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving barang keluar: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data barang keluar. Silakan coba lagi.');
        }
    }
}
