<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\bMasukController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('signin');
});

// Route::get('login', [LoginController::class, 'viewLogin'])->name('login');

Route::middleware('web')->group(function () {
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
    Route::get('login', [LoginController::class, 'viewLogin'])->name('login');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::get('/dashboard', function () {
    return view('menu.dashboard');
});

// Route::get('/daftar-produk', function () {
//     return view('menu.produk');
// });
Route::get('/daftar-produk', [BarangController::class, 'viewBarang'])->name('view.barang');
Route::get('/daftar-produk/search', [BarangController::class, 'search']);
Route::get('/detail-produk/{idBarang}', [BarangController::class, 'viewDetailProduk'])->name('detail.produk');

Route::middleware('web')->group(function () {
    Route::post('/submit-supplier', [SupplierController::class, 'tambahSupplier'])->name('supplier.submit');
    Route::get('/daftar-supplier', [SupplierController::class, 'viewSupplier'])->name('view.supplier');
    Route::get('/tambah-supplier', [SupplierController::class, 'viewTambahSupplier']);
    Route::get('/suppliers/search', [SupplierController::class, 'search']);
});

Route::get('/barang-masuk', [bMasukController::class, 'viewbMasuk'])->name('barang-masuk');
Route::post('/barang-masuk/store', [bMasukController::class, 'tambahBMasuk'])->name('barang-masuk.store');


Route::get('/barang-keluar', function () {
    return view('menu.manajemen.bKeluar');
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
});

Route::get('/log', function () {
    return view('others.log');
});

