<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        return "Koneksi Aman, Jar!";
    } catch (\Exception $e) {
        return "Masih Error: " . $e->getMessage();
    }
});
