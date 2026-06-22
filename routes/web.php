<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard sementara
Route::get('/driver/dashboard', function(){ return 'Driver Dashboard'; });

// Customer Routes
Route::middleware(['auth', 'role:customer'])->group(function(){
    Route::get('/customer/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
    Route::get('/customer/daftar-driver', [CustomerController::class, 'daftarDriver'])->name('customer.daftar-driver');
    Route::post('/customer/daftar-driver', [CustomerController::class, 'submitDaftarDriver'])->name('customer.submit-daftar-driver');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->group(function(){
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Driver Route
    Route::resource('drivers', DriverController::class);
    Route::patch('/drivers/{driver}/approve', [DriverController::class, 'approve'])->name('drivers.approve');
    Route::patch('/drivers/{driver}/suspend', [DriverController::class, 'suspend'])->name('drivers.suspend');
});

//Order Routes
Route::resource('orders', OrderController::class)
    ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
 
// Complete order — pakai POST supaya tidak bisa di-trigger dari URL langsung
Route::post('/orders/{id}/complete', [OrderController::class, 'complete'])
    ->name('orders.complete');

    // Vehicle Routes
    Route::resource('vehicles', VehicleController::class);
    Route::patch('/vehicles/{vehicle}/verify', [VehicleController::class, 'verify'])->name('vehicles.verify');
    Route::patch('/vehicles/{vehicle}/reject', [VehicleController::class, 'reject'])->name('vehicles.reject');
});
