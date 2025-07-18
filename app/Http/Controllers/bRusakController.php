<?php

namespace App\Http\Controllers;

use App\enum\Alasan;
use App\enum\satuan;
use App\Models\Akun;
use App\Models\BarangDetail;
use App\Models\bRusak;
use App\Models\bRusakDetail;
use App\Models\Notifications;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class bRusakController extends Controller
{
    public function viewConfirmBRusak()
    {
        if(!isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }

        $bRusak = bRusak::with(['detailRusak', 'detailRusak.barang', 'akun']) // Load nested relationships
            ->where('statusRusak', 2)
            ->latest()
            ->paginate(10);

        $staffBRusak = bRusak::with(['detailRusak', 'detailRusak.barang']) // Load nested relationships
            ->where('statusRusak', 2)
            ->where('penanggungJawab', session('idAkun'))
            ->latest()
            ->paginate(10);

        // dd($bRusak->items(),$staffBRusak->items());

        return view('menu.icare.rusak.confirm-bRusak', ['bRusak' => $bRusak, 'staffBRusak' => $staffBRusak]);
    }

    public function viewAjukanBRusak()
    {
        if(!isUserLoggedIn()){
            abort(403, 'Unauthorized action.');
        }
        $satuan = satuan::cases();

        return view('menu.icare.rusak.tambah' , ['satuan' => $satuan]);
    }

    public function searchList(Request $request)
    {
        $search = $request->input('q');

        // Owner: all bRusak with statusRusak = 2
        $bRusakQuery = bRusak::with(['detailRusak', 'detailRusak.barang', 'akun'])->where('statusRusak', 2);

        // Staff: only bRusak assigned to this staff
        $staffBRusakQuery = bRusak::with(['detailRusak', 'detailRusak.barang'])
            ->where('statusRusak', 2)
            ->where('penanggungJawab', session('idAkun'));

        // Apply search filter if provided
        if ($search) {
            $bRusakQuery->where(function ($query) use ($search) {
                $query->where('idBarangRusak', 'like', '%' . $search . '%')->orWhere('penanggungJawab', 'like', '%' . $search . '%');
            });

            $staffBRusakQuery->where(function ($query) use ($search) {
                $query->where('idBarangRusak', 'like', '%' . $search . '%')->orWhere('penanggungJawab', 'like', '%' . $search . '%');
            });
        }

        $bRusak = $bRusakQuery->paginate(10)->appends(['q' => $search]);
        $staffBRusak = $staffBRusakQuery->paginate(10)->appends(['q' => $search]);

        return view('menu.icare.rusak.confirm-bRusak', [
            'bRusak' => $bRusak,
            'staffBRusak' => $staffBRusak,
            'search' => $search,
        ]);
    }

    function viewDetailBKeluar($idBarangRusak)
    {
        $bRusak = bRusak::with(['detailRusak.detailBarangRusak.barang']) // Load nested relationships
            ->where('idBarangRusak', $idBarangRusak)
            ->firstOrFail();

        $kategoriAlasan = Alasan::cases();

        return view('menu.icare.rusak.detail-bRusak', ['bRusak' => $bRusak, 'kategoriAlasan' => $kategoriAlasan]);
    }

    private function updateBRusakStatus($idBarangRusak)
    {
        $details = bRusakDetail::where('idBarangRusak', $idBarangRusak)->get();
        $accepted = $details->where('statusRusakDetail', 1)->count();
        $rejected = $details->where('statusRusakDetail', 0)->count();
        $total = $details->count();

        $bRusak = bRusak::find($idBarangRusak);
        if (!$bRusak) return;

        if ($accepted === $total) {
            $bRusak->statusRusak = 1; // all accepted
        } elseif ($rejected === $total) {
            $bRusak->statusRusak = 0; // all rejected
        } else {
            $bRusak->statusRusak = 3; // mixed/partial
        }
        $bRusak->save();
    }

    public function ajukanBRusak(Request $request)
    {
        DB::beginTransaction();

        try {
            // Step 1: Create the main bRusak record
            $rusak = new bRusak();
            $rusak->idBarangRusak = bRusak::generateNewIdBarangRusak();
            $rusak->tglRusak = $request->rusak[1]['tanggal_rusak']; // Use the first row to get the date
            $rusak->penanggungJawab =  session('user_data')->idAkun; // Or get from session if needed
            $rusak->statusRusak = 2; // pending
            $rusak->save();

            // Step 2: Loop over each row submitted
            foreach ($request->rusak as $data) {
                // Get the item to check available quantity
                $barang = BarangDetail::where('barcode', $data['barcode'])->first();
                if (!$barang) {
                    return redirect()->back()->with('error', 'Data barang tidak ditemukan.');
                }

                // Check stock quantity
                if ($data['kuantitas'] > $barang->quantity) {
                    return redirect()
                        ->back()
                        ->with('error', 'Jumlah barang rusak melebihi stok yang tersedia untuk barang ID: ' . $data['id_barang']);
                }

                if ($data['kuantitas'] < $barang->quantity) {
                    // 1. Reduce the original's quantity
                    $barang->quantity -= $data['kuantitas'];
                    $barang->save();

                    // 2. Create a new BarangDetail for the damaged quantity, status pending rusak
                    $pendingBarang = $barang->replicate();
                    $pendingBarang->idDetailBarang = BarangDetail::generateNewIdBarangDetail();
                    $pendingBarang->quantity = $data['kuantitas'];
                    $pendingBarang->statusDetailBarang = 2; // pending rusak
                    $pendingBarang->save();
                } else {
                    // If marking all as damaged, just update status to pending rusak
                    $barang->statusDetailBarang = 2;
                    $barang->save();
                }

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
                $detail->save();
            }

            DB::commit();

            //notification sending to all users
            $owners = Akun::where('peran', 1)->get();
            $ownerIds = $owners->pluck('idAkun');
            $staffId = $rusak->penanggungJawab;

            //owner
            foreach ($owners as $o) {
                Notifications::create([
                    'idAkun' => $o->idAkun,
                    'title' => 'Pengajuan Barang Rusak',
                    'message' => 'Terdapat pengajuan rusak barang baru.',
                    'data' => json_encode([
                        'idBarangRusak' => $rusak->idBarangRusak,
                        'added_by' => session('user_data')->nama ?? 'Unknown',
                    ]),
                ]);
            }

            // Only notify the staff if they are not already an owner
            if (!$ownerIds->contains($staffId)) {
                Notifications::create([
                    'idAkun' => $staffId,
                    'title' => 'Pengajuan Barang Rusak Anda Berhasil',
                    'message' => 'Pengajuan Barang Rusak Anda berhasil diajukan.',
                    'data' => json_encode([
                        'idBarangRusak' => $rusak->idBarangRusak,
                        'added_by' => session('user_data')->nama ?? 'Unknown',
                    ]),
                ]);
            }

            return redirect()->route('view.AjukanBRusak')->with('success', 'Informasi Barang Rusak berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
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

    public function validBRusak($idDetailBR)
    {
        $detail = bRusakDetail::where('idDetailBR', $idDetailBR)->first();
        $detail->statusRusakDetail = 1; // approved

        // Find the pending BarangDetail (status 2, matching barcode and quantity)
        $pendingBarang = BarangDetail::where('barcode', $detail->barcode)->where('statusDetailBarang', 2)->where('quantity', $detail->jumlah)->first();

        if (!$pendingBarang) {
            return redirect()->back()->with('error', 'Data barang (pending) tidak ditemukan.');
        }

        $pendingBarang->statusDetailBarang = 3; // approved
        $detail->save();

        // Check if all details for this rusak are validated (status = 1)
        $pendingCount = bRusakDetail::where('idBarangRusak', $detail->idBarangRusak)
        ->where('statusRusakDetail', 2) // 2 = pending
        ->count();

        if ($pendingCount === 0) {
            $bRusak = bRusak::find($detail->idBarangRusak);
            $this->updateBRusakStatus($detail->idBarangRusak);

            //notification sending to all users
            $owners = Akun::where('peran', 1)->get();
            $ownerIds = $owners->pluck('idAkun');
            $staffId = $bRusak->penanggungJawab;

            //owner
            foreach ($owners as $o) {
                Notifications::create([
                    'idAkun' => $o->idAkun,
                    'title' => 'Pengajuan Barang Rusak Divalidasi',
                    'message' => 'Pengajuan rusak telah divalidasi.',
                    'data' => json_encode([
                        'idBarangRusak' => $bRusak->idBarangRusak,
                        'validated_by' => session('user_data')->nama ?? 'Unknown',
                    ]),
                ]);
            }

            // Only notify the staff if they are not already an owner
            if (!$ownerIds->contains($staffId)) {
                Notifications::create([
                    'idAkun' => $staffId,
                    'title' => 'Pengajuan Barang Rusak Divalidasi',
                    'message' => 'Pengajuan Barang Rusak Anda telah divalidasi.',
                    'data' => json_encode([
                        'idBarangRusak' => $bRusak->idBarangRusak,
                        'validated_by' => session('user_data')->nama ?? 'Unknown',
                    ]),
                ]);
            }

            return redirect()->route('view.ConfirmBRusak')->with('success', 'Sukses!');
        }

        return redirect()
            ->route('detail.bRusak', ['idBarangRusak' => $detail->idBarangRusak])
            ->with('success', 'Informasi Barang Rusak berhasil diubah');
    }

    public function rejectBRusak($idDetailBR)
    {
        // Update the detail status
        $detail = bRusakDetail::where('idDetailBR', $idDetailBR)->first();
        $detail->statusRusakDetail = 0; // rejected
        $detail->save();

        // Find the pending BarangDetail (status 2, matching barcode and quantity)
        $pendingBarang = BarangDetail::where('barcode', $detail->barcode)->where('statusDetailBarang', 2)->where('quantity', $detail->jumlah)->first();

        // Find the original active BarangDetail (status 1, same barcode)
        $activeBarang = BarangDetail::where('barcode', $detail->barcode)->where('statusDetailBarang', 1)->first();

        if ($pendingBarang && $activeBarang) {
            // Restore the quantity
            $activeBarang->quantity += $pendingBarang->quantity;
            $activeBarang->save();

            // Delete the pendingBarang row
            $pendingBarang->delete();
        } elseif ($pendingBarang && !$activeBarang) {
            // If no activeBarang exists (maybe all was marked rusak before), just set pendingBarang back to active
            $pendingBarang->statusDetailBarang = 1;
            $pendingBarang->save();
        }

        // Check if all details for this rusak are validated (status = 0)
        $pendingCount = bRusakDetail::where('idBarangRusak', $detail->idBarangRusak)
        ->where('statusRusakDetail', 2) // 2 = pending
        ->count();

        if ($pendingCount === 0) {
            $bRusak = bRusak::find($detail->idBarangRusak);
            $this->updateBRusakStatus($detail->idBarangRusak);

            //notification sending to all users
            $owners = Akun::where('peran', 1)->get();
            $ownerIds = $owners->pluck('idAkun');
            $staffId = $bRusak->penanggungJawab;

            //owner
            foreach ($owners as $o) {
                Notifications::create([
                    'idAkun' => $o->idAkun,
                    'title' => 'Pengajuan Barang Rusak Ditolak',
                    'message' => 'Pengajuan rusak telah ditolak.',
                    'data' => json_encode([
                        'idBarangRusak' => $bRusak->idBarangRusak,
                        'rejected_by' => session('user_data')->nama ?? 'Unknown',
                    ]),
                ]);
            }

            // Only notify the staff if they are not already an owner
            if ($staffId && !$ownerIds->contains($staffId)) {
                Notifications::create([
                    'idAkun' => $staffId,
                    'title' => 'Pengajuan Barang Rusak Ditolak',
                    'message' => 'Pengajuan rusak telah ditolak.',
                    'data' => json_encode([
                        'idBarangRusak' => $bRusak->idBarangRusak,
                        'rejected_by' => session('user_data')->nama ?? 'Unknown',
                    ]),
                ]);
            }

            return redirect()->route('view.ConfirmBRusak')->with('success', 'Sukses!');
        }

        return redirect()
            ->route('detail.bRusak', ['idBarangRusak' => $detail->idBarangRusak])
            ->with('success', 'Informasi Barang Rusak berhasil diubah');
    }
}
