<?php

use Illuminate\Support\Facades\Route;

// Route default (welcome page Laravel)
Route::get('/', function () {
    return view('welcome');
});

// Route sederhana untuk test koneksi
Route::get('/ping', function () {
    return 'OK';
});
