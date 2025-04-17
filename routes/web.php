<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('signin');
});

Route::get('/dashboard', function () {
    return view('menu.dashboard');
});

Route::get('/daftar-produk', function () {
    return view('menu.produk');
});

Route::get('/daftar-supplier', function () {
    return view('menu.supplier');
});

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

Route::get('/daftar-akun', function () {
    return view('account.akun');
});

Route::get('/backup-database', function () {
    return view('others.bDatabase');
});

Route::get('/log', function () {
    return view('others.log');
});

