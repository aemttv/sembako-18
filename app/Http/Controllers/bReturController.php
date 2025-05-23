<?php

namespace App\Http\Controllers;

use App\Models\BarangDetail;
use App\Models\bRetur;
use App\Models\bReturDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class bReturController extends Controller
{
    public function viewConfirmBRetur()
    {
        $bRetur = bRetur::with(['detailRetur', 'detailRetur.barang']) // Load nested relationships
                    ->where('statusRetur', 2)
                    ->paginate(10);

        $staffBRetur = bRetur::with(['detailRetur', 'detailRetur.barang']) // Load nested relationships
                    ->where('statusRetur', 2)
                    ->where('penanggungJawab', session('idAkun'))
                    ->paginate(10);

        return view('menu.icare.retur.confirm-bRetur', ['bRetur' => $bRetur, 'staffBRetur' => $staffBRetur]);
    }

    function viewDetailBKeluar($idBarangRetur) {
        $bRetur = bRetur::with(['detailRetur', 'detailRetur.barang']) // Load nested relationships
                    ->where('idBarangRetur', $idBarangRetur)
                    ->first();

        return view('menu.icare.retur.detail-bRetur', ['bRetur' => $bRetur]);
    }

    function viewAjukanBRetur()
    {
        return view('menu.icare.retur.tambah');
    }

    public function ajukanBRetur(Request $request)
    {
        DB::beginTransaction();

        try {
            // Step 1: Create the main bRetur record
            $retur = new bRetur();
            $retur->idBarangRetur = bRetur::generateNewIdReturBarang();
            $retur->tglRetur = $request->retur[1]['tanggal_retur']; // Use the first row to get the date
            $retur->idSupplier = $request->retur[1]['id_supplier']; // Use the first row to get the supplier
            $retur->penanggungJawab = $request->retur[1]['id_akun']; // Or get from session if needed
            $retur->statusRetur = 2; // pending
            $retur->save();
            // dd($retur);

            // Step 2: Loop over each row submitted
            foreach ($request->retur as $data) {
                // Get the item to check available quantity
                $barang = BarangDetail::where('barcode', $data['barcode'])->first();

                if (!$barang) {
                    return redirect()->back()->with('error', 'Data barang tidak ditemukan.');
                }

                // Check stock quantity
                if ($data['kuantitas'] > $barang->quantity) {
                    return redirect()
                        ->back()
                        ->with('error', 'Jumlah barang retur melebihi stok yang tersedia untuk barang ID: ' . $data['id_barang']);
                }

                if ($data['kuantitas'] < $barang->quantity) {
                    // 1. Reduce the original's quantity
                    $barang->quantity -= $data['kuantitas'];
                    $barang->save();

                    // 2. Create a new BarangDetail for the returned quantity, status pending
                    $pendingBarang = $barang->replicate();
                    $pendingBarang->idDetailBarang = BarangDetail::generateNewIdBarangDetail();
                    $pendingBarang->quantity = $data['kuantitas'];
                    $pendingBarang->statusDetailBarang = 2; // pending
                    
                    $pendingBarang->save();
                    
                } else {
                    // If returning all, just update status to pending
                    $barang->statusDetailBarang = 2;
                    $barang->save();
                }

                // Step 3: Save detail row
                $detailRetur = new bReturDetail();
                $detailRetur->idDetailRetur = bReturDetail::generateNewIdDetailRetur();
                $detailRetur->idBarangRetur = $retur->idBarangRetur;
                $detailRetur->barcode = $data['barcode'];
                $detailRetur->jumlah = $data['kuantitas'];
                $detailRetur->kategoriAlasan = $data['kategori_ket'];
                $detailRetur->keterangan = $data['note'];
                $detailRetur->statusReturDetail = 2; // pending
                $detailRetur->save();
            }

            DB::commit();
            return redirect()->route('view.AjukanBRetur')->with('success', 'Barang telah diajukan untuk retur, Silahkan menunggu konfirmasi');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage());
        }
    }

    public function validBRetur($idDetailRetur) {
        // Update the detail status
        $detail = bReturDetail::where('idDetailRetur', $idDetailRetur)->first();
        $detail->statusReturDetail = 1; // approved

        // Find the pending BarangDetail (status 2, matching barcode and quantity)
        $pendingBarang = BarangDetail::where('barcode', $detail->barcode)
            ->where('statusDetailBarang', 2)
            ->where('quantity', $detail->jumlah)
            ->first();

        if (!$pendingBarang) {
            return redirect()->back()->with('error', 'Data barang (pending) tidak ditemukan.');
        }

        // Approve the pendingBarang: remove from stock (delete or set status to approved/removed)
        $pendingBarang->delete();


        $detail->save();

        // Check if all details for this return are validated (status = 1)
        $allDetailsValidated = bReturDetail::where('idBarangRetur', $detail->idBarangRetur)
            ->where('statusReturDetail', '!=', 1)
            ->doesntExist();

        if ($allDetailsValidated) {
            // Update the main return status
            $bRetur = bRetur::find($detail->idBarangRetur);
            $bRetur->statusRetur = 1;
            $bRetur->save();

            return redirect()->route('view.ConfirmBRetur')
                ->with('success', 'Semua barang retur telah divalidasi dan retur telah dikonfirmasi');
        }

        return redirect()->route('detail.bRetur', ['idBarangRetur' => $detail->idBarangRetur])
            ->with('success', 'Informasi Barang Retur berhasil diubah');
    }
    
    function rejectBRetur($idDetailRetur) {
        // Update the detail status
        $detail = bReturDetail::where('idDetailRetur', $idDetailRetur)->first();
        $detail->statusReturDetail = 0;
        $detail->save();

        // Find the pending BarangDetail (status 2, matching barcode and quantity)
        $pendingBarang = BarangDetail::where('barcode', $detail->barcode)
            ->where('statusDetailBarang', 2)
            ->where('quantity', $detail->jumlah)
            ->first();

        // Find the original active BarangDetail (status 1, same barcode)
        $activeBarang = BarangDetail::where('barcode', $detail->barcode)
            ->where('statusDetailBarang', 1)
            ->first();

        if ($pendingBarang && $activeBarang) {
            // Restore the quantity
            $activeBarang->quantity += $pendingBarang->quantity;
            $activeBarang->save();

            // Delete the pendingBarang row
            $pendingBarang->delete();
        } elseif ($pendingBarang && !$activeBarang) {
            // If no activeBarang exists (maybe all was returned before), just set pendingBarang back to active
            $pendingBarang->statusDetailBarang = 1;
            $pendingBarang->save();
        }

        // Check if all details for this return are validated (status = 0)
        $allDetailsValidated = bReturDetail::where('idBarangRetur', $detail->idBarangRetur)
            ->where('statusReturDetail', '!=', 0)
            ->doesntExist();

        if ($allDetailsValidated) {
            // Update the main return status
            $bRetur = bRetur::find($detail->idBarangRetur);
            $bRetur->statusRetur = 0;
            $bRetur->save();

            return redirect()->route('view.ConfirmBRetur')
                ->with('success', 'Semua barang retur telah ditolak');
        }

        return redirect()->route('detail.bRetur', ['idBarangRetur' => $detail->idBarangRetur])
            ->with('success', 'Informasi Barang Retur berhasil diubah');
    }

}
