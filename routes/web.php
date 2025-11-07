<?php

use App\Http\Controllers\manajemenStockBarangController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StockHistoryController;
use App\Http\Controllers\ReportController;

// Guest routes (hanya bisa diakses jika belum login)
Route::middleware("guest")->group(function () {
    // Login
    Route::get("/", [AuthController::class, "showLogin"])->name("login");
    Route::get("/login", [AuthController::class, "showLogin"]);
    Route::post("/login", [AuthController::class, "login"])->name("login.post");
    
    // Register
    Route::get("/register", [AuthController::class, "showRegisterForm"])->name("register");
    Route::post("/register", [AuthController::class, "register"]);
    
    // Forgot Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
    
    // 2FA Verification (untuk user yang sedang proses login dengan 2FA)
    Route::get('/two-factor/verify', [TwoFactorController::class, 'showVerifyForm'])->name('two-factor.verify');
    Route::post('/two-factor/verify', [TwoFactorController::class, 'verifyTwoFactorCode']);
    Route::post('/two-factor/resend', [TwoFactorController::class, 'resendCode'])->name('two-factor.resend');
});

// Protected routes (harus login)
Route::middleware("auth")->group(function () {
    // Dashboard - untuk Kalel
    Route::get("/dashboard", function () {
        return view("dashboard");
    })->name("dashboard");

    // Logout
    Route::post("/logout", [AuthController::class, "logout"])->name("logout");
    
    // 2FA Settings (untuk user yang sudah login)
    Route::get('/two-factor/settings', [TwoFactorController::class, 'showSettings'])->name('two-factor.settings');
    Route::post('/two-factor/enable', [TwoFactorController::class, 'enableTwoFactor'])->name('two-factor.enable');
    Route::post('/two-factor/disable', [TwoFactorController::class, 'disableTwoFactor'])->name('two-factor.disable');

    // Products - untuk Dafa
    // Route::resource('products', ProductController::class);
    Route::prefix("products")->group(function () {
        Route::get("/", [manajemenStockBarangController::class, "index"])->name(
            "products.index",
        );
        Route::get("/getProductById", [
            manajemenStockBarangController::class,
            "getProductById",
        ])->name("products.getProductById");
        Route::get("/getallproduct", [
            manajemenStockBarangController::class,
            "getProducts",
        ])->name("products.getAllProduct");
        Route::get("/getSuppliers", [
            manajemenStockBarangController::class,
            "getSuppliers",
        ])->name("products.getSuppliers");
        Route::post("/addProduct", [
            manajemenStockBarangController::class,
            "insertProduct",
        ])->name("products.addProduct");
        Route::put("/updateProduct/{id}", [
            manajemenStockBarangController::class,
            "updateProduct",
        ])->name("products.updateProduct");
        Route::delete("/deleteProduct/{id}", [
            manajemenStockBarangController::class,
            "deleteProduct",
        ])->name("products.deleteProduct");
    });

    // Suppliers - untuk Rafli
    Route::resource("suppliers", SupplierController::class);

    // Stock History / Logs - untuk Darryl
    Route::get("/stock-history", [
        StockHistoryController::class,
        "index",
    ])->name("stock-history.index");
    Route::get("/product-logs/search", [
        StockHistoryController::class,
        "search",
    ])->name("stock-history.search");

    // Reports
    Route::get("/reports", [ReportController::class, "index"])->name(
        "reports.index",
    );
});
