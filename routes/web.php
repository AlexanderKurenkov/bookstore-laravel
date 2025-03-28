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
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');

Route::prefix('catalog')->name('catalog.')->group(function () {
    Route::get('/', [CatalogController::class, 'index'])->name('index'); // displays all books
    Route::get('/category/{url_slug}', [CatalogController::class, 'showCategory'])->name('category');
    Route::get('/book/{id}', [CatalogController::class, 'showBook'])->name('book');
});

// TODO make API
Route::prefix('favorites')->name('favorites.')->group(function () {
    Route::get('/', [CatalogController::class, 'listFavorites'])->name('list');
    Route::post('/toggle', [CatalogController::class, 'toggleFavorites'])->name('toggle');
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
    Route::delete('/item', [CartController::class, 'destroyItem'])->name('item.destroy');
    Route::delete('/', [CartController::class, 'clear'])->name('clear');
});

// Protected routes.
Route::middleware(['auth', 'verified'])->group(function () {
    // No need to include {id} in the URL path for the profile routes because
    // profile belongs to the authenticated user.
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');

        Route::patch('/update-password', [ProfileController::class, 'updatePassword'])->name('password.update');
    });

    // TODO API?
    // Route::prefix('addresses')->name('addresses.')->group(function () {
    //     Route::post('/', [ProfileController::class, 'storeAddress'])->name('store');
    //     Route::delete('/{id}', [ProfileController::class, 'destroyAddress'])->name('destroy');

    // });


    //TODO make '/checkout' protected
    Route::prefix('checkout')->name('checkout.')->group(function () {
        //TODO
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::get('/invoice', [CheckoutController::class, 'invoice'])->name('invoice');

        Route::get('/{cartId}', [CheckoutController::class, 'show'])->name('show');
        Route::post('/', [CheckoutController::class, 'process'])->name('process');

        Route::post('/{cartId}', [CheckoutController::class, 'destroy'])->name('destroy');

        // Route::patch('/shipping', [CheckoutController::class, 'updateShipping'])->name('shipping');
        // Route::patch('/payment', [CheckoutController::class, 'updatePayment'])->name('payment');
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

// Route::get('/profile', function () {
//     return view('profile');
// })->middleware(['auth', 'verified'])->name('profile');

// Cart API routes for dynamic updates
Route::prefix('api/cart')->group(function () {
    Route::get('/', [CartController::class, 'getCart'])->name('cart.api.get');
    Route::post('/add', [CartController::class, 'addItem'])->name('cart.api.add');
    Route::post('/remove', [CartController::class, 'removeItem'])->name('cart.api.destroy');
    Route::post('/update', [CartController::class, 'updateItem'])->name('cart.api.update');
});

// DEV
if (app()->environment('local')) {
    Route::get('/clear-session', function () {
        session()->flush();
        return "Session cleared.";
    });
}

require __DIR__ . '/auth.php';
