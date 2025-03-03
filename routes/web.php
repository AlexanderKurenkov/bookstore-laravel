<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/catalog', [HomeController::class, 'catalog'])->name('catalog.index');
Route::get('/book/{id}', [HomeController::class, 'book'])->name('catalog.book');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('profile')->name('profile.')->middleware('auth')->group(function () {
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('/', [ProfileController::class, 'update'])->name('update');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
});

Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/{cartId}', [CheckoutController::class, 'checkout'])->name('index');
    Route::post('/{cartId}', [CheckoutController::class, 'checkoutPost'])->name('process');
    Route::patch('/shipping', [CheckoutController::class, 'setShippingAddress'])->name('shipping');
    Route::patch('/payment', [CheckoutController::class, 'setPaymentMethod'])->name('payment');
});

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'showCart'])->name('index');
    Route::post('/items', [CartController::class, 'addItem'])->name('items.store');
    Route::patch('/items/{id}', [CartController::class, 'updateCartItem'])->name('items.update');
    Route::delete('/items/{id}', [CartController::class, 'removeItem'])->name('items.destroy');
});

Route::prefix('search')->name('search.')->group(function () {
    Route::get('/category', [SearchController::class, 'searchByCategory'])->name('category');
    Route::post('/books', [SearchController::class, 'searchBook'])->name('books');
});

require __DIR__ . '/auth.php';
