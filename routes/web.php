<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplierController;

use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource("suppliers", SupplierController::class)->middleware('auth');

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
