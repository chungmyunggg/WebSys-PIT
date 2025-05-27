<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;
// use App\Http\Controllers\InventoryController; // Commented out InventoryController import
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ✅ Register full resource routes for products
    Route::resource('products', ProductController::class);

    // Other resource routes
    Route::resource('orders', OrderController::class);

    // Route::resource('inventory', InventoryController::class); // Commented out inventory routes

    Route::resource('reports', ReportsController::class);
});

require __DIR__.'/auth.php';
