<?php

use App\Http\Controllers\manajemenStockBarangController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StockHistoryController;
use App\Http\Controllers\ReportController;

// Guest routes (hanya bisa diakses jika belum login)
Route::middleware("guest")->group(function () {
    Route::get("/", [AuthController::class, "showLogin"])->name("login");
    Route::get("/login", [AuthController::class, "showLogin"]);
    Route::post("/login", [AuthController::class, "login"])->name("login.post");
    Route::get("/register", [AuthController::class, "showRegisterForm"])->name(
        "register",
    );
    Route::post("/register", [AuthController::class, "register"]);
    
    // Forgot Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});

// Protected routes (harus login)
Route::middleware("auth")->group(function () {
    // Dashboard - untuk Kalel
    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

    // Logout
    Route::post("/logout", [AuthController::class, "logout"])->name("logout");

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
    Route::resource("suppliers", SupplierController::class)->except([
        "create",
        "show",
        "edit",
    ]);

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

    // Admin: user management (CRUD + approve)
    Route::get("/admin/users", [
        App\Http\Controllers\UserController::class,
        "index",
    ])->name("admin.users.index");
    Route::get("/admin/users/create", [
        App\Http\Controllers\UserController::class,
        "create",
    ])->name("admin.users.create");
    Route::post("/admin/users", [
        App\Http\Controllers\UserController::class,
        "store",
    ])->name("admin.users.store");
    Route::get("/admin/users/{user}/edit", [
        App\Http\Controllers\UserController::class,
        "edit",
    ])->name("admin.users.edit");
    Route::put("/admin/users/{user}", [
        App\Http\Controllers\UserController::class,
        "update",
    ])->name("admin.users.update");
    Route::delete("/admin/users/{user}", [
        App\Http\Controllers\UserController::class,
        "destroy",
    ])->name("admin.users.destroy");
    Route::post("/admin/users/{user}/approve", [
        App\Http\Controllers\UserController::class,
        "approve",
    ])->name("admin.users.approve");
});
