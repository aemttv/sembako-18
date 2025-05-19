<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\bKeluarController;
use App\Http\Controllers\bMasukController;
use App\Http\Controllers\DashboardController;
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
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware('web')->group(function () {
    Route::post('/tambah-produk', [BarangController::class, 'tambahProduk'])->name('produk.submit');
    Route::post('/tambah-merek', [BarangController::class, 'tambahMerek'])->name('merek.submit');
    Route::get('/tambah-produk', [BarangController::class, 'viewtambahProduk'])->name('view.tambah-produk');
    Route::get('/daftar-produk', [BarangController::class, 'viewBarang'])->name('view.barang');
    Route::get('/daftar-produk/search', [BarangController::class, 'search']);
    Route::get('/merek/search', [BarangController::class, 'searchMerek']);
});

Route::middleware('web')->group(function () {
    Route::get('/detail-produk/{idBarang}', [BarangController::class, 'viewDetailProduk'])->name('detail.produk');
    Route::get('/daftar-produk/search/barcode', [BarangController::class, 'searchBarcode']);
    Route::get('/daftar-produk/search-detail', [BarangController::class, 'searchDetail']);
    Route::post('/barang-detail/{idBarang}/{barcode}/soft-delete', [BarangController::class, 'softDeleteBarangDetail'])->name('soft.delete.detail');
});

Route::middleware('web')->group(function () {
    Route::post('/submit-supplier', [SupplierController::class, 'tambahSupplier'])->name('supplier.submit');
    Route::get('/daftar-supplier', [SupplierController::class, 'viewSupplier'])->name('view.supplier');
    Route::get('/tambah-supplier', [SupplierController::class, 'viewTambahSupplier']);
    Route::get('/suppliers/search', [SupplierController::class, 'search']);
});

Route::middleware('web')->group(function () {
    Route::post('/barang-masuk/store', [bMasukController::class, 'tambahBMasuk'])->name('barang-masuk.submit');
    Route::get('/daftar-barang-masuk', [bMasukController::class, 'viewBMasuk'])->name('view.bMasuk');
    Route::get('/barang-masuk', [bMasukController::class, 'viewTambahBMasuk'])->name('barang-masuk');
});

Route::middleware('web')->group(function () {
    Route::post('/barang-keluar/store', [bKeluarController::class, 'buatBKeluar'])->name('barang-keluar.submit');
    Route::get('/daftar-barang-keluar', [bKeluarController::class, 'viewBKeluar'])->name('view.bKeluar');
    Route::get('/barang-keluar', [bKeluarController::class, 'viewBuatBKeluar'])->name('barang-keluar');
    Route::get('/barang-keluar/detail/{idBarangKeluar}', [bKeluarController::class, 'viewDetailBKeluar'])->name('detail.bKeluar');
});

Route::get('/retur-barang', function () {
    return view('menu.icare.confirm-bRetur');
});

Route::get('/barang-rusak', function () {
    return view('menu.icare.confirm-bRusak');
});


Route::get('/laporan-stok', function () {
    return view('menu.laporan.stok');
});

Route::middleware('web')->group(function () {
    Route::post('/submit-akun', [AkunController::class, 'tambahAkun'])->name('akun.submit');
    Route::get('/daftar-akun', [AkunController::class, 'viewAkun'])->name('view.akun');
    Route::get('/tambah-akun', [AkunController::class, 'viewTambahAkun']);
    Route::get('/akun/search', [AkunController::class, 'search']);
});

Route::get('/log', function () {
    return view('others.log');
});

