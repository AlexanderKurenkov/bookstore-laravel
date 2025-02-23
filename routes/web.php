<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ShoppingCartController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/catalog', [HomeController::class, 'catalog'])->name('catalog');
Route::get('/book/{id}', [HomeController::class, 'book'])->name('book');

Route::get('/dashboard', function () {
	return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('profile')->name('profile')->group(function () {
	Route::get('/edit', [ProfileController::class, 'edit'])->name('edit'); // profile.edit
	Route::put('/update', [ProfileController::class, 'update'])->name('update'); // profile.update
	Route::delete('/destroy', [ProfileController::class, 'destroy'])->name('destroy'); // profile.destroy
});

Route::prefix('checkout')->group(function () {
	Route::get('/{cartId}', [CheckoutController::class, 'checkout'])->name('checkout');
	Route::post('/{cartId}', [CheckoutController::class, 'checkoutPost'])->name('checkout.post');
	Route::get('/setShippingAddress', [CheckoutController::class, 'setShippingAddress'])->name('checkout.shipping');
	Route::get('/setPaymentMethod', [CheckoutController::class, 'setPaymentMethod'])->name('checkout.payment');
});

Route::prefix('shoppingCart')->group(function () {
	Route::get('/cart', [ShoppingCartController::class, 'showCart'])->name('cart.index');
	Route::post('/addItem', [ShoppingCartController::class, 'addItem'])->name('shoppingCart.addItem');
	Route::post('/updateCartItem', [ShoppingCartController::class, 'updateCartItem'])->name('shoppingCart.updateCartItem');
	Route::get('/removeItem', [ShoppingCartController::class, 'removeItem'])->name('shoppingCart.removeItem');
});

Route::prefix('search')->group(function () {
	Route::get('/category', [SearchController::class, 'searchByCategory'])->name('search.category');
	Route::post('/book', [SearchController::class, 'searchBook'])->name('search.book');
});


Route::middleware('auth')->group(function () {
	Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
	Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
	Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
