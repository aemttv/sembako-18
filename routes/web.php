<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\bKeluarController;
use App\Http\Controllers\bMasukController;
use App\Http\Controllers\bReturController;
use App\Http\Controllers\bRusakController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', function () {
    return view('signin');
});

Route::middleware('web')->group(function () {
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
    Route::get('login', [LoginController::class, 'viewLogin'])->name('login');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/profile/{idAkun}', [LoginController::class, 'viewProfile'])->name('profile');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware('web')->group(function () {
    Route::post('/tambah-produk', [BarangController::class, 'tambahProduk'])->name('produk.submit');
    Route::post('/tambah-merek', [BarangController::class, 'tambahMerek'])->name('merek.submit');
    Route::get('/tambah-produk', [BarangController::class, 'viewtambahProduk'])->name('view.tambah-produk');
    Route::get('/daftar-produk', [BarangController::class, 'viewBarang'])->name('view.barang');
    Route::get('/daftar-produk/search', [BarangController::class, 'search']);
    Route::get('/merek/search', [BarangController::class, 'searchMerek']);

    Route::post('/barang-detail/{idBarang}/{barcode}/soft-delete', [BarangController::class, 'softDeleteBarangDetail'])->name('soft.delete.detail');
    Route::post('/barang-detail/{idBarang}/{barcode}/soft-update', [BarangController::class, 'softUpdateBarangDetail'])->name('soft.update.detail');
    Route::post('/barang-detail/{idBarang}/update', [BarangController::class, 'updateBarangDetail'])->name('detail.barang.update');
    Route::get('/detail-produk/{idBarang}', [BarangController::class, 'viewDetailProduk'])->name('detail.produk');
    Route::get('/daftar-produk/search/barcode', [BarangController::class, 'searchBarcode']);
    Route::get('/daftar-produk/search-detail/barcode', [BarangController::class, 'searchSupplierBarcode']);
    Route::get('/daftar-produk/search-detail', [BarangController::class, 'searchDetail']);
});

Route::middleware('web')->group(function () {
    Route::get('/barcode/{barcode}', [BarcodeController::class, 'viewDetailProduk'])->name('barcode.view.detail');
});

Route::middleware('web')->group(function () {
    Route::post('/submit-supplier', [SupplierController::class, 'tambahSupplier'])->name('supplier.submit');
    Route::post('/supplier/update/{idSupplier}', [SupplierController::class, 'editSupplier'])->name('supplier.update');
    Route::get('/daftar-supplier', [SupplierController::class, 'viewSupplier'])->name('view.supplier');
    Route::get('/tambah-supplier', [SupplierController::class, 'viewTambahSupplier']);
    Route::get('/suppliers/search', [SupplierController::class, 'search']);
});

Route::middleware('web')->group(function () {
    Route::post('/barang-masuk/store', [bMasukController::class, 'tambahBMasuk'])->name('barang-masuk.submit');
    Route::get('/daftar-barang-masuk', [bMasukController::class, 'viewBMasuk'])->name('view.bMasuk');
    Route::get('/barang-masuk', [bMasukController::class, 'viewTambahBMasuk'])->name('barang-masuk');
    Route::get('/barang-masuk/detail/{idBarangMasuk}', [bMasukController::class, 'viewDetailBMasuk'])->name('detail.bMasuk');
});

Route::middleware('web')->group(function () {
    Route::post('/barang-keluar/store', [bKeluarController::class, 'buatBKeluar'])->name('barang-keluar.submit');
    Route::get('/daftar-barang-keluar', [bKeluarController::class, 'viewBKeluar'])->name('view.bKeluar');
    Route::get('/barang-keluar', [bKeluarController::class, 'viewBuatBKeluar'])->name('barang-keluar');
    Route::get('/barang-keluar/detail/{idBarangKeluar}', [bKeluarController::class, 'viewDetailBKeluar'])->name('detail.bKeluar');
});
Route::middleware('web')->group(function () {
    Route::post('/ajukan-retur/store', [bReturController::class, 'ajukanBRetur'])->name('AjukanBRetur.submit');
    Route::post('/retur-valid/{idDetailRetur}', [bReturController::class, 'validBRetur'])->name('detail.bRetur.approve');
    Route::post('/retur-reject/{idDetailRetur}', [bReturController::class, 'rejectBRetur'])->name('detail.bRetur.reject');
    Route::get('/konfirmasi-retur', [bReturController::class, 'viewConfirmBRetur'])->name('view.ConfirmBRetur');
    Route::get('/ajukan-retur', [bReturController::class, 'viewAjukanBRetur'])->name('view.AjukanBRetur');
    Route::get('/retur-barang/detail/{idBarangRetur}', [bReturController::class, 'viewDetailBKeluar'])->name('detail.bRetur');
});

Route::middleware('web')->group(function () {
    Route::post('/ajukan-barang-rusak/store', [bRusakController::class, 'ajukanBRusak'])->name('AjukanBRusak.submit');
    Route::post('/rusak-valid/{idDetailBR}', [bRusakController::class, 'validBRusak'])->name('detail.bRusak.approve');
    // Route::post('/detail-barang-rusak/bulk-approve', [bRusakController::class, 'bulkApproveRusak'])->name('detail.bRusak.bulk-approve');
    Route::post('/rusak-reject/{idDetailBR}', [bRusakController::class, 'rejectBRusak'])->name('detail.bRusak.reject');
    Route::get('/konfirmasi-rusak', [bRusakController::class, 'viewConfirmBRusak'])->name('view.ConfirmBRusak');
    Route::get('/ajukan-barang-rusak', [bRusakController::class, 'viewAjukanBRusak'])->name('view.AjukanBRusak');
    Route::get('/barang-rusak/detail/{idBarangRusak}', [bRusakController::class, 'viewDetailBKeluar'])->name('detail.bRusak');
});

Route::middleware('web')->group(function () {
    //barang masuk laporan
    Route::post('/laporan-barang-masuk/pdf/download', [PDFController::class, 'streamPDFbMasuk'])->name('streamPDF.bMasuk.view');
    Route::get('/laporan-barang-masuk', [LaporanController::class, 'viewbMasuk'])->name('laporan.bMasuk.view');
    Route::get('/laporan-barang-masuk/search', [LaporanController::class, 'searchBMasuk'])->name('laporan.bMasuk.search');

    //barang keluar laporan
    Route::post('/laporan-barang-keluar/pdf/download', [PDFController::class, 'streamPDFbKeluar'])->name('streamPDF.bKeluar.view');
    Route::get('/laporan-barang-keluar', [LaporanController::class, 'viewbKeluar'])->name('laporan.bKeluar.view');
    Route::get('/laporan-barang-keluar/search', [LaporanController::class, 'searchBKeluar'])->name('laporan.bKeluar.search');

    //stok barang laporan
    Route::post('/laporan-stok/pdf/download', [PDFController::class, 'streamPDFStokBarang'])->name('streamPDF.StokBarang.view');
    Route::get('/laporan-stok', [LaporanController::class, 'viewStokBarang'])->name('laporan.StokBarang.view');
    Route::get('/laporan-stok/search', [LaporanController::class, 'searchStokBarang'])->name('laporan.StokBarang.search');

    //barang rusak laporan
    Route::post('/laporan-barang-rusak/pdf/download', [PDFController::class, 'streamPDFbRusak'])->name('streamPDF.bRusak.view');
    Route::get('/laporan-barang-rusak', [LaporanController::class, 'viewbRusak'])->name('laporan.bRusak.view');
    Route::get('/laporan-barang-rusak/search', [LaporanController::class, 'searchBRusak'])->name('laporan.bRusak.search');

    //barang retur laporan
    Route::post('/laporan-barang-retur/pdf/download', [PDFController::class, 'streamPDFbRetur'])->name('streamPDF.bRetur.view');
    Route::get('/laporan-barang-retur', [LaporanController::class, 'viewbRetur'])->name('laporan.bRetur.view');
    Route::get('/laporan-barang-retur/search', [LaporanController::class, 'searchBRetur'])->name('laporan.bRetur.search');
});

Route::middleware('web')->group(function () {
    Route::post('/submit-akun', [AkunController::class, 'tambahAkun'])->name('akun.submit');
    Route::post('/akun/update/{idAkun}', [AkunController::class, 'editAkun'])->name('akun.update');
    Route::get('/daftar-akun', [AkunController::class, 'viewAkun'])->name('view.akun');
    Route::get('/tambah-akun', [AkunController::class, 'viewTambahAkun']);
    Route::get('/akun/search', [AkunController::class, 'search']);
});

Route::get('/log', function () {
    return view('others.log');
});

Route::post('/notifications/clear', [NotificationController::class, 'clear'])->name('notifications.clear');
Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

// Route::get('/test-error', function () {
//     return view('errors.custom', [
//         'message' => 'Ini halaman error custom.',
//         'error' => 'Contoh error detail.'
//     ]);
// });