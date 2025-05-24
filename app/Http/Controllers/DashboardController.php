<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\bKeluarDetail;
use App\Models\bMasukDetail;
use App\Enum\KategoriBarang; // adjust if needed
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

            // Sum jumlahKeluar for all barang in this category
            $jumlahKeluar = bKeluarDetail::whereHas('barang.detailBarang', function($query) use ($kategori) {
                $query->where('kategoriBarang', $kategori->value);
            })->sum('jumlahKeluar');

            $data[] = $jumlahKeluar;
        }

        $totalStok = Barang::with(['detailBarang' => function($query) {
            $query->where('statusDetailBarang', 1);
        }])->get()->sum(fn($barang) => $barang->detailBarang->sum('quantity'));

        $totalBarangMasuk = bMasukDetail::sum('jumlahMasuk');
        $totalBarangKeluar = bKeluarDetail::sum('jumlahKeluar');
        $totalDekatKadaluarsa = BarangDetail::where('kondisiBarang', 'Mendekati Kadaluarsa')->sum('quantity');

        $stokRendah = Barang::withSum(['detailBarang as total_quantity' => function($query) {
        $query->where('statusDetailBarang', 1);
            }], 'quantity')
            ->having('total_quantity', '<', 10)
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
