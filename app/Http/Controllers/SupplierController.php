<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    function viewSupplier()
    {
        $supplier = Supplier::where('status', 1)->paginate(10);

        return view('menu.supplier', ['supplier' => $supplier]);
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
}
