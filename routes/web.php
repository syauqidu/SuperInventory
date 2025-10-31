<?php

use App\Http\Controllers\manajemenStockBarangController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StockHistoryController;
use App\Http\Controllers\ReportController;

// Guest routes (hanya bisa diakses jika belum login)
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login', [AuthController::class, 'showLogin']);
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Protected routes (harus login)
Route::middleware('auth')->group(function () {
    // Dashboard - untuk Kalel
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Products - untuk Dafa
    // Route::resource('products', ProductController::class);
    Route::prefix('products')->group(function () {
        Route::get('/', [manajemenStockBarangController::class, 'index'])->name('products.index');
        Route::get('/getProductById', [manajemenStockBarangController::class, 'getProductById'])->name('products.getProductById');
        Route::get('/getallproduct', [manajemenStockBarangController::class, 'getProducts'])->name('products.getAllProduct');
        Route::get('/getSuppliers', [manajemenStockBarangController::class, 'getSuppliers'])->name('products.getSuppliers');
        Route::post('/addProduct', [manajemenStockBarangController::class, 'insertProduct'])->name('products.addProduct');
        Route::put('/updateProduct/{id}', [manajemenStockBarangController::class, 'updateProduct'])->name('products.updateProduct');
        Route::delete('/deleteProduct/{id}', [manajemenStockBarangController::class, 'deleteProduct'])->name('products.deleteProduct');
    });

    // Suppliers - untuk Siganteng
    Route::resource('suppliers', SupplierController::class);

    // Stock History / Logs - untuk Darryl
    Route::get('/stock-history', [StockHistoryController::class, 'index'])->name('stock-history.index');
    Route::get('/product-logs/search', [StockHistoryController::class, 'search'])->name('stock-history.search');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});