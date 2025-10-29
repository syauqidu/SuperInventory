<?php

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
    // Register route untuk Uqi nanti
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
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
    Route::resource('products', ProductController::class);
    
    // Suppliers - untuk Siganteng
    Route::resource('suppliers', SupplierController::class);
    
    // Stock History / Logs - untuk Darryl
    Route::get('/stock-history', [StockHistoryController::class, 'index'])->name('stock-history.index');
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});
