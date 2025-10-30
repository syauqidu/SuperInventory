<?php

use App\Http\Controllers\manajemenStockBarangController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('manajemenStock')->group(function () {

    Route::get('/', [manajemenStockBarangController::class, 'index'])->name('stock.index');
    Route::get('/getProductById', [manajemenStockBarangController::class, 'getProductById'])->name('stock.getProductById');
    Route::get('/getallproduct', [manajemenStockBarangController::class, 'getProducts'])->name('stock.getAllProduct');
    Route::post('/addProduct', [manajemenStockBarangController::class, 'insertProduct'])->name('stock.addProduct');
    Route::put('/updateProduct/{id}', [manajemenStockBarangController::class, 'updateProduct']);
    Route::delete('/deleteProduct/{id}', [manajemenStockBarangController::class, 'deleteProduct'])->name('stock.deleteProduct');
});