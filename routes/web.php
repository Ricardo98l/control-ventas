<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ReportController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Dashboard y Ventas
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/sales', [DashboardController::class, 'store'])->name('sales.store');
Route::get('/sales/{id}/edit', [DashboardController::class, 'edit'])->name('sales.edit');
Route::put('/sales/{id}', [DashboardController::class, 'update'])->name('sales.update');
Route::delete('/sales/{id}', [DashboardController::class, 'destroy'])->name('sales.destroy');

// Precios
Route::get('/precios', [PriceController::class, 'index'])->name('prices.index');
Route::post('/precios', [PriceController::class, 'store'])->name('prices.store');

// Catálogo
Route::post('/catalog/channel', [CatalogController::class, 'storeChannel'])->name('catalog.channel.store');
Route::post('/catalog/product', [CatalogController::class, 'storeProduct'])->name('catalog.product.store');
Route::post('/catalog/packaging', [CatalogController::class, 'storePackaging'])->name('catalog.packaging.store');
Route::delete('/catalog/packaging/{id}', [CatalogController::class, 'destroyPackaging'])->name('catalog.packaging.destroy');

// Catálogo
Route::post('/catalog/channel', [CatalogController::class, 'storeChannel'])->name('catalog.channel.store');
Route::post('/catalog/product', [CatalogController::class, 'storeProduct'])->name('catalog.product.store');

// NUEVAS: Editar y Eliminar Productos
Route::put('/catalog/product/{id}', [CatalogController::class, 'updateProduct'])->name('catalog.product.update');
Route::delete('/catalog/product/{id}', [CatalogController::class, 'destroyProduct'])->name('catalog.product.destroy');

// Estadísticas e Historial Completo
Route::get('/estadisticas', [ReportController::class, 'index'])->name('reports.index');

// Parche para el menú superior de Breeze
Route::get('/profile', function () { return redirect()->route('dashboard'); })->name('profile.edit');


