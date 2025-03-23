<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');

Route::prefix('catalog')->name('catalog.')->group(function () {
    Route::get('/', [CatalogController::class, 'index'])->name('index'); // displays all books
    Route::get('/category/{url_slug}', [CatalogController::class, 'showCategory'])->name('category');
    Route::get('/book/{id}', [CatalogController::class, 'showBook'])->name('book');
});

Route::prefix('favorites')->name('favorites.')->group(function () {
    // TODO
    Route::get('/', [CatalogController::class, 'TODO'])->name('index');
    Route::get('/toggle', [CatalogController::class, 'TODO'])->name('toggle');
});

Route::prefix('reviews')->name('reviews.')->group(function () {
    Route::get('/{id}', [ReviewController::class, 'index'])->name('index');
    Route::post('/{id}', [ReviewController::class, 'store'])->name('store');
    Route::patch('/{id}', [ReviewController::class, 'update'])->name('update');
    Route::delete('/{id}', [ReviewController::class, 'destroy'])->name('destroy');
});

Route::prefix('search')->name('search.')->group(function () {
    // Shows advanced search form.
    Route::get('/', [SearchController::class, 'index'])->name('index');
    // GET method for search allows users to bookmark search results.
    Route::get('/results', [SearchController::class, 'results'])->name('results');
});

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/item', [CartController::class, 'storeItem'])->name('item.store');
    Route::patch('/item/{id}', [CartController::class, 'updateItem'])->name('item.update');
    Route::delete('/item/{id}', [CartController::class, 'destroyItem'])->name('item.destroy');
    Route::delete('/', [CartController::class, 'clear'])->name('clear');
});

//TODO make '/checkout' protected
Route::prefix('checkout')->name('checkout.')->group(function () {
    //TODO
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::get('/invoice', [CheckoutController::class, 'invoice'])->name('invoice');

    Route::get('/{cartId}', [CheckoutController::class, 'show'])->name('show');
    Route::post('/{cartId}', [CheckoutController::class, 'process'])->name('process');

    Route::post('/{cartId}', [CheckoutController::class, 'destroy'])->name('destroy');

    // Route::patch('/shipping', [CheckoutController::class, 'updateShipping'])->name('shipping');
    // Route::patch('/payment', [CheckoutController::class, 'updatePayment'])->name('payment');
});

// Protected routes.
Route::middleware(['auth', 'verified'])->group(function () {
    // No need to include {id} in the URL path for the profile routes because
    // profile belongs to the authenticated user.
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');

    });

    // TODO API?
    Route::prefix('addresses')->name('addresses.')->group(function () {
        Route::post('/', [ProfileController::class, 'storeAddress'])->name('store');
        Route::delete('/{id}', [ProfileController::class, 'destroyAddress'])->name('destroy');

    });

    Route::prefix('returns')->name('returns.')->group(function () {
        Route::get('/edit/{id}', [ReturnController::class, 'edit'])->name('edit');
        Route::post('/', [ReturnController::class, 'store'])->name('store');
        Route::get('/{id}/confirmation', [ReturnController::class, 'confirmation'])->name('confirmation');
    });
});


// API route for getting books from an order
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::get('/orders/{orderId}/books', [ReturnController::class, 'getOrderBooks']);
});

// Order cancellation
Route::middleware(['auth'])->group(function () {
    Route::post('/orders/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/auth.php';
