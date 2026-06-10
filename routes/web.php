<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DriverController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard sementara
Route::get('/admin/dashboard', function(){ return 'Admin Dashboard'; });
Route::get('/driver/dashboard', function(){ return 'Driver Dashboard'; });
Route::get('/customer/dashboard', function(){ return 'Customer Dashboard'; });

// Admin Routes
Route::middleware(['auth', 'role:admin'])->group(function(){
    Route::resource('drivers', DriverController::class);
    Route::patch('/drivers/{driver}/approve', [DriverController::class, 'approve'])->name('drivers.approve');
    Route::patch('/drivers/{driver}/suspend', [DriverController::class, 'suspend'])->name('drivers.suspend');
});