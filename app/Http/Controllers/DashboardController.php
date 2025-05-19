<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangDetail;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Eager load both 'detailBarang' and 'merek' relationships
        // $barang = BarangDetail::count('quantity');


        // Return view with data
        return view('menu.dashboard');
    }
}
