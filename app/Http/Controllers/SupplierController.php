<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Notifications;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SupplierController extends Controller
{
    function viewSupplier()
    {
        $supplier = Supplier::orderBy('idSupplier', 'desc')->paginate(10);

        return view('menu.supplier.indexSupplier', ['supplier' => $supplier]);
    }

    function viewTambahSupplier()
    {
        return view('menu.supplier.tambah');
    }

    public function search(Request $request)
    {
        $query = $request->get('q'); // Search query from input

        // Search for suppliers with names that contain the query string
        $suppliers = Supplier::where('nama', 'like', "%$query%")
            ->select('idSupplier', 'nama')
            ->get(); // Only retrieve id and name fields for efficiency

        return response()->json($suppliers); // Return matched suppliers as JSON
    }

    function searchList(Request $request)
    {
        $search = $request->input('q');

        $supplier = Supplier::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')->orWhere('idSupplier', 'like', '%' . $search . '%');
                });
            })
            ->paginate(10)
            ->appends(['q' => $search]);

        return view('menu.supplier.indexSupplier', [
            'supplier' => $supplier,
            'search' => $search,
        ]);
    }

    function tambahSupplier(Request $request)
    {
        try {
            DB::beginTransaction();

            $addedSuppliers = [];

            foreach ($request->supplier_input as $jsonItem) {
                $item = json_decode($jsonItem, true);

                $supplier = new Supplier();
                $supplier->idSupplier = Supplier::generateNewIdSupplier();
                $supplier->nama = $item['nama'];
                $supplier->alamat = $item['alamat'];
                $supplier->nohp = $item['no_hp'];
                $supplier->status = 1;
                $supplier->save();

                $addedSuppliers[] = $supplier;
            }

            DB::commit();

            $owners = Akun::where('peran', 1)->get();

            foreach ($addedSuppliers as $supplier) {
                foreach ($owners as $o) {
                    Notifications::create([
                        'idAkun' => $o->idAkun,
                        'title' => 'Supplier Baru Ditambahkan',
                        'message' => 'Supplier baru telah ditambahkan.',
                        'data' => json_encode([
                            'nama_supplier' => $supplier->nama,
                            'id_supplier' => $supplier->idSupplier,
                            'added_by' => session('user_data')->nama ?? 'Unknown',
                        ]),
                    ]);
                }
            }

            return redirect()->route('view.supplier')->with('success', 'Supplier berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding supplier: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menambahkan supplier. Silakan coba lagi.');
        }
    }

    function editSupplier(Request $request, $idSupplier)
    {
        try {
            $supplier = Supplier::where('idSupplier', $idSupplier)->first();

            if (!$supplier) {
                return redirect()
                    ->route('view.akun')
                    ->with(['success' => false, 'message' => 'Supplier not found'], 404);
            }

            $updateData = [
                'nama' => $request->nama,
                'nohp' => $request->nohp,
                'alamat' => $request->alamat,
                'status' => $request->status,
            ];

            $supplier->update($updateData);

            $owners = Akun::where('peran', 1)->get();

            foreach ($owners as $o) {
                Notifications::create([
                    'idAkun' => $o->idAkun,
                    'title' => 'Supplier Diperbarui',
                    'message' => 'Informasi supplier telah diperbarui.',
                    'data' => json_encode([
                        'nama_supplier' => $supplier->nama,
                        'id_supplier' => $supplier->idSupplier,
                        'edited_by' => session('user_data')->nama ?? 'Unknown',
                    ]),
                ]);
            }

            return redirect()->route('view.supplier')->with('success', 'Informasi Staff berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal mengubah supplier: ' . $e->getMessage())
                ->withInput();
        }
    }
}
