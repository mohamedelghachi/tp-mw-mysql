<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('home'))->name('home');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware('auth')
    ->name('dashboard');
Route::get('/admin', fn() => view('admin'))
    ->middleware(['auth', 'role:admin'])
    ->name('admin');

Route::get('/contact', [ContactController::class, 'showContactForm'])->name('contact');
Route::post('/contact', [ContactController::class, 'storeContactForm'])->name('contact.store');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Routes pour les produits et commandes - accessibles uniquement aux utilisateurs connectés
Route::middleware('auth')->group(function () {
    Route::resource('products', ProductController::class);
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', function () {
        return view('orders_create');
    })->name('orders.create');
    Route::post('/orders', [\App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
});
