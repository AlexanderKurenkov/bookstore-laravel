<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartController extends Controller
{
	/**
	 * Display the shopping cart.
	 */
	public function index(): View
	{
		$user = Auth::user();
		// TODO
		// $shoppingCart = $user->shoppingCart;
		// $cartItemList = $shoppingCart->cartItems;

		// Ensure the shopping cart is updated (assume a service or model method exists for this logic)
		$shoppingCart->updateCart();

		return view('cart.index', [
			'cartItemList' => $cartItemList,
			'shoppingCart' => $shoppingCart,
		]);
	}

	/**
	 * Add an item to the shopping cart.
	 */
	public function storeItem(Request $request): \Illuminate\Http\RedirectResponse
	{
		$user = Auth::user();
		$book = Book::findOrFail($request->input('book_id'));
		$qty = (int)$request->input('qty');

		if ($qty > $book->in_stock_number) {
			return redirect()->route('book', ['id' => $book->id])
				->with('notEnoughStock', true);
		}

		// TODO
		// Assume addBookToCartItem is a method implemented in the ShoppingCart or CartItem model/service
		// Book::addBookToCartItem($book, $user, $qty);

		return redirect()->route('catalog', ['id' => $book->id])
			->with('addBookSuccess', true);
	}

	/**
	 * Update an item in the shopping cart.
	 */
	public function updateItem(Request $request): \Illuminate\Http\RedirectResponse
	{
		$cartItemId = $request->input('id');
		$qty = (int)$request->input('qty');

		$cartItem = Book::findOrFail($cartItemId);
		$cartItem->qty = $qty;
		$cartItem->save();

		return redirect()->route('cart.index');
	}

	/**
	 * Remove an item from the shopping cart.
	 */
	public function destroyItem(Request $request): \Illuminate\Http\RedirectResponse
	{
		$cartItemId = $request->query('id');

		$cartItem = Book::findOrFail($cartItemId);
		$cartItem->delete();

		return redirect()->route('cart.index');
	}
}
