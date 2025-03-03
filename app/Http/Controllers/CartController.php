<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\CartItem;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
	/**
	 * Display the shopping cart.
	 */
	public function showCart(): \Illuminate\View\View
	{
		$user = Auth::user();
		$shoppingCart = $user->shoppingCart;
		$cartItemList = $shoppingCart->cartItems;

		// Ensure the shopping cart is updated (assume a service or model method exists for this logic)
		$shoppingCart->updateCart();

		return view('shoppingCart', [
			'cartItemList' => $cartItemList,
			'shoppingCart' => $shoppingCart,
		]);
	}

	/**
	 * Add an item to the shopping cart.
	 */
	public function addItem(Request $request): \Illuminate\Http\RedirectResponse
	{
		$user = Auth::user();
		$book = Book::findOrFail($request->input('book_id'));
		$qty = (int)$request->input('qty');

		if ($qty > $book->in_stock_number) {
			return redirect()->route('book', ['id' => $book->id])
				->with('notEnoughStock', true);
		}

		// Assume addBookToCartItem is a method implemented in the ShoppingCart or CartItem model/service
		CartItem::addBookToCartItem($book, $user, $qty);

		return redirect()->route('book', ['id' => $book->id])
			->with('addBookSuccess', true);
	}

	/**
	 * Update an item in the shopping cart.
	 */
	public function updateCartItem(Request $request): \Illuminate\Http\RedirectResponse
	{
		$cartItemId = $request->input('id');
		$qty = (int)$request->input('qty');

		$cartItem = CartItem::findOrFail($cartItemId);
		$cartItem->qty = $qty;
		$cartItem->save();

		return redirect()->route('shoppingCart.showCart');
	}

	/**
	 * Remove an item from the shopping cart.
	 */
	public function removeItem(Request $request): \Illuminate\Http\RedirectResponse
	{
		$cartItemId = $request->query('id');

		$cartItem = CartItem::findOrFail($cartItemId);
		$cartItem->delete();

		return redirect()->route('shoppingCart.showCart');
	}
}
