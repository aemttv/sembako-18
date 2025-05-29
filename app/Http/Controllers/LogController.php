<?php

namespace App\Http\Controllers;

use App\enum\Alasan;
use App\Models\bKeluar;
use App\Models\bMasuk;
use App\Models\bRetur;
use App\Models\bRusak;

class LogController extends Controller
{
    public function index()
    {
        if(!isOwner() || isUserLoggedIn()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Fetch logs for each activity type, order by date descending
        $barangMasukLogs = bMasuk::with('detailMasuk')->limit(5)->get();
        $barangKeluarLogs = bKeluar::with('detailKeluar')->limit(5)->get(); 
        $barangReturLogs = bRetur::with('detailRetur')->limit(5)->get();
        $barangRusakLogs = bRusak::with('detailRusak')->limit(5)->get();

        $kategoriAlasan = Alasan::cases();

        return view('others.log', compact(
            'barangMasukLogs',
            'barangKeluarLogs',
            'barangReturLogs',
            'barangRusakLogs',
            'kategoriAlasan'
        ));
    }
}
