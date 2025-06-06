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
        if(!isOwner() || !isUserLoggedIn()) {
        abort(403, 'Unauthorized action.');
        }

        // Eager load nested relationships for deeper info
        $barangMasukLogs = bMasuk::with('detailMasuk.barangDetail.barang')->limit(5)->orderby('idBarangMasuk', 'desc')->orderby('tglMasuk', 'desc')->get();
        $barangKeluarLogs = bKeluar::with('detailKeluar')->limit(5)->orderby('idBarangKeluar', 'desc')->orderby('tglKeluar', 'desc')->get();
        $barangReturLogs = bRetur::with('detailRetur')->limit(5)->orderby('idBarangRetur', 'desc')->orderby('tglRetur', 'desc')->get();
        $barangRusakLogs = bRusak::with('detailRusak')->limit(5)->orderby('idBarangRusak', 'desc')->orderby('tglRusak', 'desc')->get();

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
