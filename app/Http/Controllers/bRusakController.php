<?php

namespace App\Http\Controllers;

use App\Models\BarangDetail;
use App\Models\bRusak;
use App\Models\bRusakDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class bRusakController extends Controller
{
    public function viewConfirmBRusak()
    {
        $bRusak = bRusak::with(['detailRusak', 'detailRusak.barang']) // Load nested relationships
                    ->where('statusRusak', 2)
                    ->paginate(10);

        $staffBRusak = bRusak::with(['detailRusak', 'detailRusak.barang']) // Load nested relationships
                    ->where('statusRusak', 2)
                    ->where('penanggungJawab', session('idAkun'))
                    ->paginate(10);

        // dd($bRusak->items(),$staffBRusak->items());

        return view('menu.icare.rusak.confirm-bRusak', ['bRusak' => $bRusak, 'staffBRusak' => $staffBRusak]);
    }

    public function viewAjukanBRusak() 
    {
        return view('menu.icare.rusak.tambah');
    }

    function viewDetailBKeluar($idBarangRusak) {
        $bRusak = bRusak::with(['detailRusak', 'detailRusak.barang']) // Load nested relationships
                    ->where('idBarangRusak', $idBarangRusak)
                    ->first();

        return view('menu.icare.rusak.detail-bRusak', ['bRusak' => $bRusak]);
    }

    public function ajukanBRusak(Request $request) 
    {
        DB::beginTransaction();

        try {
            // Step 1: Create the main bRetur record
            $rusak = new bRusak();
            $rusak->idBarangRusak = bRusak::generateNewIdBarangRusak();
            $rusak->tglRusak = $request->rusak[1]['tanggal_rusak']; // Use the first row to get the date
            $rusak->penanggungJawab = $request->rusak[1]['id_akun']; // Or get from session if needed
            $rusak->statusRusak = 2; // pending
            $rusak->save();
            // dump($request->all());
            // dump($rusak);

            // Step 2: Loop over each row submitted
            foreach ($request->rusak as $data) {
                // Get the item to check available quantity
                $barang = BarangDetail::where('barcode', $data['barcode'])->first();
                // dump($barang);
                if (!$barang) {
                    return redirect()->back()->with('error', 'Data barang tidak ditemukan.');
                }

                // Check stock quantity
                if ($data['kuantitas'] > $barang->quantity) {
                    return redirect()
                        ->back()
                        ->with('error', 'Jumlah barang rusak melebihi stok yang tersedia untuk barang ID: ' . $data['id_barang']);
                }

                $barang->statusDetailBarang = 2;
                $barang->save();

                // Step 3: Create the detail record
                $detail = new bRusakDetail();
                $detail->idDetailBR = bRusakDetail::generateNewIdDetailBR();
                $detail->idBarangRusak = $rusak->idBarangRusak;
                $detail->idBarang = $data['id_barang'];
                $detail->barcode = $data['barcode'];
                $detail->jumlah = $data['kuantitas'];
                $detail->kategoriAlasan = $data['kategori_ket'];
                $detail->keterangan = $data['note'];
                $detail->statusRusakDetail = 2;
                // dd($detail);
                $detail->save();
            }

            DB::commit();
            return redirect()->route('view.AjukanBRusak')->with('success', 'Informasi Barang Retur berhasil disimpan'); 
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Validates a specific damaged item detail and updates the stock accordingly.
     *
     * This function checks if the damaged item detail with the given ID exists,
     * updates its status, and verifies the related item's stock. If the reason
     * category is expiration-related, it ensures the item has expired before
     * proceeding. It then decrements the stock quantity and updates the item's
     * status if necessary. If all details for the damaged item are validated,
     * it updates the main record's status.
     *
     * @param int $idDetailBR The ID of the damaged item detail to validate.
     * @return \Illuminate\Http\RedirectResponse A redirect response indicating success or failure.
     */


    function validBRusak($idDetailBR) {
        
        $detail = bRusakDetail::where('idDetailBR', $idDetailBR)->first();
        $detail->statusRusakDetail = 1;
        
        // Check the barang stock
        $barang = BarangDetail::where('barcode', $detail->barcode)->first();
        
        // dd($detail, $barang);
        if (!$barang) {
            return redirect()->back()->with('error', 'Data barang tidak ditemukan.');
        }

        if ($detail->kategoriAlasan == '1') {
            if(Carbon::now() > $barang->tglKadaluarsa) { // apakah hari ini melebihi tgl kadaluarsa barang
                $detail->save();
                $barang->quantity -= $detail->jumlah;
                if ($barang->quantity == 0) {
                    $barang->statusDetailBarang = 0;
                }
                $barang->save();
            } else {
                return redirect()->back()->with('error', 'Barang Belum melebihi tanggal kadaluarsa.');
            }
        } else {
            $detail->save();
            $barang->quantity -= $detail->jumlah;
            if ($barang->quantity == 0) {
                $barang->statusDetailBarang = 0;
            }
            $barang->save();
        }

        // Check if all details for this return are validated (status = 1)
        $allDetailsValidated = bRusakDetail::where('idBarangRusak', $detail->idBarangRusak)
            ->where('statusRusakDetail', '!=', 1)
            ->doesntExist();

        if ($allDetailsValidated) {
            // Update the main return status
            $bRusak = bRusak::find($detail->idBarangRusak);
            $bRusak->statusRusak = 1;
            $bRusak->save();

            return redirect()->route('view.ConfirmBRusak')
                ->with('success', 'Semua barang rusak telah divalidasi dan telah dikonfirmasi');
        } 


        return redirect()->route('detail.bRusak', ['idBarangRusak' => $detail->idBarangRusak])
            ->with('success', 'Informasi Barang Rusak berhasil diubah');
    }

    // public function bulkApproveRusak(Request $request)
    // {
    //     $selectedDetails = $request->input('selected_details', []);
        
    //     // Validate that at least one item is selected
    //     if (empty($selectedDetails)) {
    //         return back()->with('error', 'Pilih setidaknya satu item untuk disetujui.');
    //     }

    //     $errors = [];
    //     $approvedDetails = [];
    //     $idBarangRusak = null;

    //     DB::beginTransaction();

    //     try {
    //         foreach ($selectedDetails as $idDetailBR) {
    //             $detail = bRusakDetail::where('idDetailBR', $idDetailBR)->first();
                
    //             if (!$detail) {
    //                 $errors[] = "Detail dengan ID $idDetailBR tidak ditemukan.";
    //                 continue;
    //             }

    //             // Store the barang rusak ID for later use
    //             $idBarangRusak = $detail->idBarangRusak;

    //             $detail->statusRusakDetail = 1;
                
    //             $barang = BarangDetail::where('barcode', $detail->barcode)->first();
                
    //             if (!$barang) {
    //                 $errors[] = "Data barang dengan barcode {$detail->barcode} tidak ditemukan.";
    //                 continue;
    //             }

    //             if ($detail->kategoriAlasan == '1') {
    //                 if (Carbon::now() > $barang->tglKadaluarsa) {
    //                     $detail->save();
    //                     $barang->quantity -= $detail->jumlah;
    //                     if ($barang->quantity == 0) {
    //                         $barang->statusDetailBarang = 0;
    //                     }
    //                     $barang->save();
    //                     $approvedDetails[] = $idDetailBR;
    //                 } else {
    //                     $errors[] = "Barang dengan ID {$detail->idDetailBR} belum melebihi tanggal kadaluarsa.";
    //                     continue;
    //                 }
    //             } else {
    //                 $detail->save();
    //                 $barang->quantity -= $detail->jumlah;
    //                 if ($barang->quantity == 0) {
    //                     $barang->statusDetailBarang = 0;
    //                 }
    //                 $barang->save();
    //                 $approvedDetails[] = $idDetailBR;
    //             }
    //         }

    //         // Check if all details for this return are now validated (status = 1)
    //         if ($idBarangRusak) {
    //             $allDetailsValidated = bRusakDetail::where('idBarangRusak', $idBarangRusak)
    //                 ->where('statusRusakDetail', '!=', 1)
    //                 ->doesntExist();

    //             if ($allDetailsValidated) {
    //                 // Update the main return status
    //                 $bRusak = bRusak::find($idBarangRusak);
    //                 $bRusak->statusRusak = 1;
    //                 $bRusak->save();
    //             }
    //         }

    //         DB::commit();

    //         $response = redirect();
            
    //         if (!empty($approvedDetails)) {
    //             if ($allDetailsValidated ?? false) {
    //                 $response = $response->route('view.ConfirmBRusak')
    //                     ->with('success', 'Semua barang rusak telah divalidasi dan telah dikonfirmasi');
    //             } else {
    //                 $response = $response->route('detail.bRusak', ['idBarangRusak' => $idBarangRusak])
    //                     ->with('success', 'Barang rusak yang dipilih berhasil divalidasi');
    //             }
    //         }

    //         if (!empty($errors)) {
    //             $response = $response->with('errors', $errors);
    //         }

    //         return $response;

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    //     }
    // }

    function rejectBRusak($idDetailBR) {
        $detail = bRusakDetail::where('idDetailBR', $idDetailBR)->first();
        $detail->statusRusakDetail = 0;
        $detail->save();

        $barang = BarangDetail::where('barcode', $detail->barcode)->first();
        $barang->statusDetailBarang = 1;
        $barang->save();

        // Check if all details for this return are validated (status = 0)
        $allDetailsValidated = bRusakDetail::where('idBarangRusak', $detail->idBarangRusak)
            ->where('statusRusakDetail', '!=', 0)
            ->doesntExist();

        if ($allDetailsValidated) {
            // Update the main return status
            $bRusak = bRusak::find($detail->idBarangRusak);
            $bRusak->statusRusak = 0;
            $bRusak->save();

            return redirect()->route('view.ConfirmBRusak')
                ->with('success', 'Semua barang rusak telah ditolak');
        } 

        return redirect()->route('detail.bRusak', ['idBarangRusak' => $detail->idBarangRusak])
            ->with('success', 'Informasi Barang Rusak berhasil diubah');
    }
}