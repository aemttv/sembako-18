<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('signin');
});

// Route::get('login', [LoginController::class, 'viewLogin'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.submit');


Route::get('/dashboard', function () {
    return view('menu.dashboard');
});

// Route::get('/daftar-produk', function () {
//     return view('menu.produk');
// });
Route::get('/daftar-produk', [BarangController::class, 'viewBarang']);


Route::get('/daftar-supplier', [SupplierController::class, 'viewSupplier']);

Route::get('/retur-barang', function () {
    return view('menu.icare.confirm-bRetur');
});

Route::get('/barang-rusak', function () {
    return view('menu.icare.confirm-bRusak');
});

Route::get('/barang-masuk', function () {
    return view('menu.manajemen.bMasuk');
});

Route::get('/barang-keluar', function () {
    return view('menu.manajemen.bKeluar');
});

Route::get('/laporan-stok', function () {
    return view('menu.laporan.stok');
});

// Route::get('/daftar-akun', function () {
//     return view('account.akun');
// });

Route::get('/daftar-akun', [AkunController::class, 'viewAkun']);


Route::get('/backup-database', function () {
    return view('others.bDatabase');
});

Route::get('/log', function () {
    return view('others.log');
});

