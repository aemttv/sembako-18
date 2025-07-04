<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\bKeluarDetail;
use App\Models\bMasukDetail;
use App\enum\KategoriBarang; // adjust if needed
use App\Models\BarangDetail;

class DashboardController extends Controller
{

    public function index()
    {
        // Get all categories (from enum or table)
        $categories = KategoriBarang::cases();

        // Prepare an array for chart labels and data
        $labels = [];
        $data = [];

        foreach ($categories as $kategori) {
            $labels[] = $kategori->namaKategori();

            // Remove with('satuan') as 'satuan' is not a relationship
            $barangs = Barang::whereHas('detailBarang', function($query) use ($kategori) {
                $query->where('kategoriBarang', $kategori->value);
            })->get();

            $jumlahKeluarKategori = 0;

            foreach ($barangs as $barang) {
                if ($barang->satuan && $barang->satuan->value == 2) {
                    $jumlahKeluarBarang = bKeluarDetail::where('idBarang', $barang->idBarang)->count();
                } else {
                    $jumlahKeluarBarang = bKeluarDetail::where('idBarang', $barang->idBarang)->sum('jumlahKeluar');
                }
                $jumlahKeluarKategori += $jumlahKeluarBarang;
            }

            $data[] = $jumlahKeluarKategori;
        }

        $totalStok = Barang::with(['detailBarang' => function($query) {
            $query->where('statusDetailBarang', 1);
            }])->get()->sum(function($barang) {
                // If satuan == 2, count the details; else, sum the quantity
                if ($barang->satuan->value == 2) {
                    return $barang->detailBarang->count();
                } else {
                    return $barang->detailBarang->sum('quantity');
                }
            });

        $bMasukDetails = bMasukDetail::with('barangDetail.barang')->get();
        $bKeluarDetails = bKeluarDetail::with('barangDetailKeluar.barang')->get();

        // Calculate totalBarangMasuk
        $totalBarangMasuk = $bMasukDetails->groupBy('idBarang')->reduce(function ($carry, $details) {
            $barang = $details->first()->barang;
            if ($barang && $barang->satuan && $barang->satuan->value === 2) {
                // Count the number of records for this barang_id
                return $carry + $details->count();
            } else {
                // Sum jumlahMasuk for this barang_id
                return $carry + $details->sum('jumlahMasuk');
            }
        }, 0);

        // Calculate totalBarangKeluar
        $totalBarangKeluar = $bKeluarDetails->groupBy('idBarang')->reduce(function ($carry, $details) {
            $barang = $details->first()->barang;
            if ($barang && $barang->satuan && $barang->satuan->value === 2) {
                // Count the number of records for this barang_id
                return $carry + $details->count();
            } else {
                // Sum jumlahKeluar for this barang_id
                return $carry + $details->sum('jumlahKeluar');
            }
        }, 0);

        $totalDekatKadaluarsa = BarangDetail::where('kondisiBarang', 'Mendekati Kadaluarsa')->sum('quantity');

        $stokRendah = Barang::withSum(['detailBarang as total_quantity' => function($query) {
        $query->where('statusDetailBarang', 1);
            }], 'quantity')
            ->having('total_quantity', '<', 10)
            ->take(1)
            ->pluck('namaBarang');

        return view('menu.dashboard', [
            'totalStok' => $totalStok,
            'totalBarangMasuk' => $totalBarangMasuk,
            'totalBarangKeluar' => $totalBarangKeluar,
            'chartLabels' => $labels,
            'chartData' => $data,
            'totalDekatKadaluarsa' => $totalDekatKadaluarsa,
            'stokRendah' => $stokRendah
        ]);
    }
}
