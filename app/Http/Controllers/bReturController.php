<?php

namespace App\Http\Controllers;

use App\enum\Alasan;
use App\enum\satuan;
use App\Models\Akun;
use App\Models\BarangDetail;
use App\Models\bRetur;
use App\Models\bReturDetail;
use App\Models\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class bReturController extends Controller
{
    public function viewConfirmBRetur()
    {
        if(!isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

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

        if(!isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

        $bRetur = bRetur::with(['detailRetur.detailBarangRetur.barang']) // Load nested relationships
                    ->where('idBarangRetur', $idBarangRetur)
                    ->firstOrFail();

        $kategoriAlasan = Alasan::cases();

        return view('menu.icare.retur.detail-bRetur', ['bRetur' => $bRetur, 'kategoriAlasan' => $kategoriAlasan]);
    }

    function searchList(Request $request) {
        $search = $request->input('q');

        // Query for all bRetur with statusRetur = 2
        $bReturQuery = bRetur::with(['detailRetur', 'detailRetur.barang'])
            ->where('statusRetur', 2);

        // Query for staff-specific bRetur
        $staffBReturQuery = bRetur::with(['detailRetur', 'detailRetur.barang'])
            ->where('statusRetur', 2)
            ->where('penanggungJawab', session('idAkun'));

        // Apply search filter if provided
        if ($search) {
            $bReturQuery->where(function ($query) use ($search) {
                $query->where('idBarangRetur', 'like', '%' . $search . '%')
                    ->orWhere('idSupplier', 'like', '%' . $search . '%')
                    ->orWhere('penanggungJawab', 'like', '%' . $search . '%');
            });

            $staffBReturQuery->where(function ($query) use ($search) {
                $query->where('idBarangRetur', 'like', '%' . $search . '%')
                    ->orWhere('idSupplier', 'like', '%' . $search . '%')
                    ->orWhere('penanggungJawab', 'like', '%' . $search . '%');
            });
        }

        $bRetur = $bReturQuery->paginate(10)->appends(['q' => $search]);
        $staffBRetur = $staffBReturQuery->paginate(10)->appends(['q' => $search]);

        return view('menu.icare.retur.confirm-bRetur', [
            'bRetur' => $bRetur,
            'staffBRetur' => $staffBRetur,
            'search' => $search,
        ]);
    }

    function viewAjukanBRetur()
    {
        if(!isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

        $satuan = satuan::cases();

        return view('menu.icare.retur.tambah', ['satuan' => $satuan]);
    }

    public function ajukanBRetur(Request $request)
    {
        DB::beginTransaction();
// dd(session('user_data'));   
        try {
            // Step 1: Create the main bRetur record
            $retur = new bRetur();
            $retur->idBarangRetur = bRetur::generateNewIdReturBarang();
            $retur->tglRetur = $request->retur[1]['tanggal_retur']; // Use the first row to get the date
            $retur->idSupplier = $request->retur[1]['id_supplier']; // Use the first row to get the supplier
            $retur->penanggungJawab = session('user_data')->idAkun; // Or get from session if needed
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

            //notification sending to all users
            $owners = Akun::where('peran', 1)->get();
            $ownerIds = $owners->pluck('idAkun');
            $staffId = $retur->penanggungJawab;

            //owner
            foreach ($owners as $o) {
                Notifications::create([
                    'idAkun' => $o->idAkun,
                    'title' => 'Pengajuan Retur Baru',
                    'message' => 'Terdapat pengajuan retur barang baru.',
                    'data' => json_encode([
                        'idBarangRetur' => $retur->idBarangRetur,
                        'added_by' => session('user_data')->nama ?? 'Unknown'
                    ]),
                ]);
            }

            // Only notify the staff if they are not already an owner
            if (!$ownerIds->contains($staffId)) {
                Notifications::create([
                    'idAkun' => $staffId,
                    'title' => 'Pengajuan Retur Anda Berhasil',
                    'message' => 'Pengajuan retur Anda berhasil diajukan.',
                    'data' => json_encode([
                        'idBarangRetur' => $retur->idBarangRetur,
                        'added_by' => session('user_data')->nama ?? 'Unknown'
                    ]),
                ]);
            }

            return redirect()->route('view.AjukanBRetur')->with('success', 'Barang telah diajukan untuk retur, Silahkan menunggu konfirmasi');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage());
        }
    }

    private function updateBReturStatus($idBarangRetur)
    {
        $details = bReturDetail::where('idBarangRetur', $idBarangRetur)->get();
        $accepted = $details->where('statusReturDetail', 1)->count();
        $rejected = $details->where('statusReturDetail', 0)->count();
        $total = $details->count();

        $bRetur = bRetur::find($idBarangRetur);
        if (!$bRetur) return;

        if ($accepted === $total) {
            $bRetur->statusRetur = 1; // all accepted
        } elseif ($rejected === $total) {
            $bRetur->statusRetur = 0; // all rejected
        } else {
            $bRetur->statusRetur = 3; // mixed/partial
        }
        $bRetur->save();
    }

    public function validBRetur($idDetailRetur) {
        try {
            // Update the detail status
            $detail = bReturDetail::where('idDetailRetur', $idDetailRetur)->first();
            if (!$detail) {
                return redirect()->back()->with('error', 'Detail retur tidak ditemukan.');
            }
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
            $pendingBarang->statusDetailBarang = 3; // approved

            $detail->save();

            // Check if all details for this return are validated (status = 1)
            $pendingCount = bReturDetail::where('idBarangRetur', $detail->idBarangRetur)
                ->where('statusReturDetail', 2) // 2 = pending
                ->count();

               if ($pendingCount === 0) {
                    $bRetur = bRetur::find($detail->idBarangRetur);
                    $this->updateBReturStatus($detail->idBarangRetur);

                    // Notification sending to all users
                    $owners = Akun::where('peran', 1)->get();
                    foreach ($owners as $o) {
                        Notifications::create([
                            'idAkun' => $o->idAkun,
                            'title' => 'Pengajuan Barang Retur Divalidasi',
                            'message' => 'Pengajuan barang retur telah divalidasi.',
                            'data' => json_encode([
                                'idBarangRetur' => $detail->idBarangRetur,
                                'validated_by' => session('user_data')->nama ?? 'Unknown'
                            ]),
                        ]);
                    }

                    // Notification sending to staff
                    $staffId = $bRetur->penanggungJawab;
                    Notifications::create([
                        'idAkun' => $staffId,
                        'title' => 'Pengajuan Barang Retur Divalidasi',
                        'message' => 'Pengajuan barang retur telah divalidasi.',
                        'data' => json_encode([
                            'idBarangRetur' => $detail->idBarangRetur,
                            'validated_by' => session('user_data')->nama ?? 'Unknown'
                        ]),
                    ]);

                return redirect()->route('view.ConfirmBRetur')
                    ->with('success', 'Sukses!');
            }

            return redirect()->route('detail.bRetur', ['idBarangRetur' => $detail->idBarangRetur])
                ->with('success', 'Informasi Barang Retur berhasil diubah');
        } catch (\Exception $e) {
            Log::error('Error in validBRetur: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memvalidasi barang retur. Silakan coba lagi.');
        }
    }

    /**
     * Rejects a specific item return by updating its detail status and managing
     * associated inventory records. Restores inventory quantities for active items
     * if necessary. If all return details are rejected, updates the main return
     * status. Redirects with success messages upon completion.
     *
     * @param int $idDetailRetur The ID of the return detail to be rejected.
     * @return \Illuminate\Http\RedirectResponse Redirects to the appropriate route with a success message.
     */

    function rejectBRetur($idDetailRetur) {
        try {
            // Update the detail status
            $detail = bReturDetail::where('idDetailRetur', $idDetailRetur)->first();
            if (!$detail) {
                return redirect()->back()->with('error', 'Detail retur tidak ditemukan.');
            }
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

            $pendingCount = bReturDetail::where('idBarangRetur', $detail->idBarangRetur)
                ->where('statusReturDetail', 2) // 2 = pending
                ->count();

            if ($pendingCount === 0) {
                $bRetur = bRetur::find($detail->idBarangRetur);
                $this->updateBReturStatus($detail->idBarangRetur);


                // Notification sending to all users
                $owners = Akun::where('peran', 1)->get();
                foreach ($owners as $o) {
                    Notifications::create([
                        'idAkun' => $o->idAkun,
                        'title' => 'Pengajuan Barang Retur Ditolak',
                        'message' => 'Pengajuan barang retur telah ditolak.',
                        'data' => json_encode([
                            'idBarangRetur' => $detail->idBarangRetur,
                            'rejected_by' => session('user_data')->nama ?? 'Unknown'
                        ]),
                    ]);
                }

                // Notification sending to staff
                $staffId = $bRetur->penanggungJawab;
                Notifications::create([
                    'idAkun' => $staffId,
                    'title' => 'Pengajuan Barang Retur Anda Ditolak',
                    'message' => 'Pengajuan barang retur Anda telah ditolak.',
                    'data' => json_encode([
                        'idBarangRetur' => $detail->idBarangRetur,
                        'rejected_by' => session('user_data')->nama ?? 'Unknown'
                    ]),
                ]);

                return redirect()->route('view.ConfirmBRetur')
                    ->with('success', 'Sukses!');
            }

            return redirect()->route('detail.bRetur', ['idBarangRetur' => $detail->idBarangRetur])
                ->with('success', 'Informasi Barang Retur berhasil diubah');
        } catch (\Exception $e) {
            Log::error('Error in rejectBRetur: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menolak barang retur. Silakan coba lagi.');
        }
    }

}
