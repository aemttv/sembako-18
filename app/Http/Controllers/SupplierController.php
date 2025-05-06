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
}
