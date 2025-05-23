<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    function viewSupplier()
    {
        $supplier = Supplier::orderBy('idSupplier', 'desc')->paginate(10);

        return view('menu.supplier.indexSupplier', ['supplier' => $supplier]);
    }

    function viewTambahSupplier() {
        return view('menu.supplier.tambah');
    }

    public function search(Request $request)
    {
        $query = $request->get('q'); // Search query from input

        // Search for suppliers with names that contain the query string
        $suppliers = Supplier::where('nama', 'like', "%$query%")
                            ->select('idSupplier', 'nama')
                            ->get();  // Only retrieve id and name fields for efficiency

        return response()->json($suppliers); // Return matched suppliers as JSON
    }

    function tambahSupplier(Request $request) {
        
        DB::beginTransaction();

        foreach ($request->supplier_input as $jsonItem) {
            $item = json_decode($jsonItem, true);

            $supplier = new Supplier();
            $supplier->idSupplier = Supplier::generateNewIdSupplier();
            $supplier->nama = $item['nama'];
            $supplier->alamat = $item['alamat'];
            $supplier->nohp = $item['no_hp'];
            $supplier->status = 1;
            $supplier->save();
        }

        DB::commit();    

        return redirect()->route('view.supplier')->with('success', 'Supplier berhasil ditambahkan');
    }

    function editSupplier(Request $request, $idSupplier) {
        try {

            $supplier = Supplier::where('idSupplier', $idSupplier)->first();

            if (!$supplier) {
                return redirect()->route('view.akun')->with(['success' => false, 'message' => 'Supplier not found'], 404);
            }

            $updateData = [
                'nama' => $request->nama,
                'nohp' => $request->nohp,
                'alamat' => $request->alamat,
                'status' => $request->status,
            ];
            // dd($updateData);
            
            $supplier->update($updateData);

            return redirect()->route('view.supplier')->with('success', 'Informasi Staff berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal mengubah supplier: ' . $e->getMessage())
                ->withInput();
        }
    }
}
