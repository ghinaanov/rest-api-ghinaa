<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Menampilkan semua produk dalam format JSON (data dari database)
Route::get('/products', [ProductController::class, 'index']);

// Menampilkan halaman admin dengan daftar produk
Route::get('/admin/products', [ProductController::class, 'adminIndex']);

// Menampilkan formulir untuk menambahkan produk baru
Route::get('/create-product', [ProductController::class, 'create'])->name('create.product');

// Menyimpan produk baru ke database dan memperbarui file JSON
Route::post('/store-product', [ProductController::class, 'store'])->name('store.product');

// Mengembalikan data produk dari file JSON
Route::get('/products-json', [ProductController::class, 'getProductsJson']);

Route::get('/products-json', function () {
    return response()->file(storage_path('app/public/products/products.json'));
});
