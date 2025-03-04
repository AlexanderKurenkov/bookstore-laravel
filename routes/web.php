<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/about', [HomeController::class, 'about'])->name('home.about');
Route::get('/faq', [HomeController::class, 'faq'])->name('home.faq');

Route::prefix('catalog')->name('catalog.')->group(function () {
    Route::get('/', [HomeController::class, 'catalog'])->name('index');
    Route::get('/books/{id}', [HomeController::class, 'book'])->name('book');
});

// No need to include {id} in the URL path for the profile routes because
// profile belongs to the authenticated user.
Route::prefix('profile')->name('profile.')->middleware('auth')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('/', [ProfileController::class, 'update'])->name('update');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
});

Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/{cartId}', [CheckoutController::class, 'show'])->name('index');
    Route::post('/{cartId}', [CheckoutController::class, 'process'])->name('process');
    // Route::patch('/shipping', [CheckoutController::class, 'updateShipping'])->name('shipping');
    // Route::patch('/payment', [CheckoutController::class, 'updatePayment'])->name('payment');
});

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/item', [CartController::class, 'storeItem'])->name('item.store');
    Route::patch('/item/{id}', [CartController::class, 'updateItem'])->name('item.update');
    Route::delete('/item/{id}', [CartController::class, 'destroyItem'])->name('item.destroy');
});

Route::prefix('search')->name('search.')->group(function () {
    // Shows advanced search form.
    Route::get('/', [SearchController::class, 'index'])->name('index');
    // GET method for search allows users to bookmark search results.
    Route::get('/results', [SearchController::class, 'results'])->name('results');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/auth.php';
