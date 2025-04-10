<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SportsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\Admin\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;


Route::get('/', [ProductsController::class, 'welcome'])->name('welcome');

Route::get('/register', [AuthController::class, 'showRegister'])->name('show.register');
Route::get('/login', [AuthController::class, 'showLogin'])->name('show.login');
Route::get('/admin', [AuthController::class, 'showAdmin'])->name('show.admin');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index')->middleware('auth');
Route::get('/cart/checkout', [CartController::class, 'showCheckout'])->name('cart.showCheckout')->middleware('auth');
Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders.index')->middleware('auth');

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/admin', [AuthController::class, 'admin'])->name('admin');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add')->middleware('auth');
Route::post('/cart', [CartController::class, 'processCheckout'])->name('cart.processCheckout')->middleware('auth');
Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear')->middleware('auth');

Route::middleware(['isAdmin'])->group(function () {
    Route::post('/sports', [ProductsController::class, 'store'])->name('sports.store');
    Route::get('/sports/create', [ProductsController::class, 'create'])->name('sports.create');
    Route::get('/sports', [ProductsController::class, 'index'])->name('sports.index');
    Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/sports/{product}/edit', [ProductsController::class, 'edit'])->name('sports.edit');
    Route::patch('/admin/orders/{order}/complete', [OrderController::class, 'markAsCompleted'])->name('admin.orders.complete');
    Route::patch('/sports/{product}', [ProductsController::class, 'update'])->name('sports.update');
});

Route::get('/sports/{product}', [ProductsController::class, 'show'])->name('sports.show')->middleware('auth');
Route::delete('/sports/{product}', [ProductsController::class, 'destroy'])->name('sports.destroy');
Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove')->middleware('auth');
Route::patch('/cart/{id}/increment', [CartController::class, 'incrementQuantity'])->name('cart.increment')->middleware('auth');
Route::patch('/cart/{id}/decrement', [CartController::class, 'decrementQuantity'])->name('cart.decrement')->middleware('auth');