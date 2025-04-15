<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('signin');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});
