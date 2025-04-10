<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');

Route::prefix('catalog')->name('catalog.')->group(function () {
    Route::get('/', [CatalogController::class, 'index'])->name('index');
    Route::get('/category/{url_slug}', [CatalogController::class, 'showCategory'])->name('category');
    Route::get('/book/{id}', [CatalogController::class, 'showBook'])->name('book');
});

Route::prefix('favorites')->name('favorites.')->group(function () {
    Route::get('/', [CatalogController::class, 'listFavorites'])->name('list');
    Route::post('/toggle', [CatalogController::class, 'toggleFavorites'])->name('toggle');
});

Route::prefix('search')->name('search.')->group(function () {
    // Метод / GET для поиска позволяет пользователям добавлять в закладки результаты поиска.
    Route::get('/results', [SearchController::class, 'results'])->name('results');
});

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::patch('/item/{id}', [CartController::class, 'updateItem'])->name('item.update');
    Route::delete('/item', [CartController::class, 'destroyItem'])->name('item.destroy');
    Route::delete('/', [CartController::class, 'clear'])->name('clear');
});

// No need to include {id} in the URL path for the profile routes because
// profile belongs to the authenticated user.
// Защищенные маршруты.
Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');

        Route::patch('/update-password', [ProfileController::class, 'updatePassword'])->name('password.update');
    });


    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/{id}', [ReviewController::class, 'index'])->name('index');
        Route::post('/{id}', [ReviewController::class, 'store'])->name('store');
        Route::patch('/{id}', [ReviewController::class, 'update'])->name('update');
        Route::delete('/{id}', [ReviewController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('checkout')->name('checkout.')->group(function () {
        //TODO
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::get('/invoice', [CheckoutController::class, 'invoice'])->name('invoice');

        Route::get('/{cartId}', [CheckoutController::class, 'show'])->name('show');
        Route::post('/', [CheckoutController::class, 'process'])->name('process');

        Route::post('/{cartId}', [CheckoutController::class, 'destroy'])->name('destroy');
    });

    // Web routes
    Route::prefix('orders/returns')->name('orders.returns.')->group(function () {
        Route::get('/edit/{id}', [OrderController::class, 'edit'])->name('edit');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{id}/confirmation', [OrderController::class, 'confirmation'])->name('confirmation');
    });
    Route::post('/orders/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // API route for getting books from an order
    Route::prefix('api')->group(function () {
        Route::get('/orders/{orderId}/books', [OrderController::class, 'getOrderBooks'])
            ->name('api.orders.books');
    });
});

// API-маршруты для динамического обновления корзины
Route::prefix('api/cart')->name('api.cart.')->group(function () {
    Route::get('/', [CartController::class, 'getCart'])->name('get');
    Route::post('/add', [CartController::class, 'addItem'])->name('add');
    Route::post('/remove', [CartController::class, 'removeItem'])->name('destroy');
});

// DEV
if (app()->environment('local')) {
    Route::get('/clear-session', function () {
        session()->flush();
        return "Session cleared.";
    });
}

require __DIR__ . '/auth.php';
