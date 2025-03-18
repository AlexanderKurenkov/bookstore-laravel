<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartController extends Controller
{
	protected CartService $cartService;

	public function __construct(CartService $cartService)
	{
		$this->cartService = $cartService;
	}

	public function index(): View
	{
		// $user = Auth::user();
		// $orderItems = $this->cartService->getAllItems();
		// return view('cart', ['orderItems' => $orderItems]);

		return view('cart');
	}

	public function storeItem(Request $request)//: JsonResponse
	{
		// $user = Auth::user();
		// $bookId = $request->input('bookId');
		// $quantity = (int) $request->input('quantity');

		// $success = $this->cartService->addCartItem($user, $bookId, $quantity);

		// return redirect()->route('cart.index')
		// 	->with($success ? 'addBookSuccess' : 'notEnoughStock', true);

		//=====================================
		// Validate incoming data (optional)
		$validated = $request->validate([
			'bookId' => 'required|integer|exists:books,id',
			'quantity' => 'required|integer|min:1',
		]);

		// Retrieve item ID and quantity from the request
		$id = $validated['bookId'];
		$quantity = $validated['quantity'];

		// Add the item to the cart (stored in the session for simplicity)
		$cart = session()->get('cart', []);

		if (isset($cart[$id])) {
			// If the item already exists in the cart, increase its quantity
			$cart[$id]['quantity'] += $quantity;
		} else {
			// Otherwise, add the item to the cart
			$cart[$id] = [
				'bookId' => $id,
				'quantity' => $quantity,
			];
		}

		session()->put('cart', $cart);

		// Respond with a success message (optional)
		return response()->json(['message' => 'Item added to cart successfully']);
	}

	public function updateItem(Request $request): RedirectResponse
	{
		$user = Auth::user();
		$bookId = $request->input('book_id');
		$quantity = (int) $request->input('quantity');

		$this->cartService->updateCartItem($user, $bookId, $quantity);

		return redirect()->route('cart.index');
	}

	public function destroyItem(Request $request): RedirectResponse
	{
		$user = Auth::user();
		$bookId = $request->query('book_id');

		$this->cartService->removeCartItem($user, $bookId);

		return redirect()->route('cart.index')->with('removeBookSuccess', true);
	}

	public function clear(): RedirectResponse
	{
		$user = Auth::user();
		$this->cartService->removeAllItems($user);

		return redirect()->route('cart.index')->with('clearCartSuccess', true);
	}
}
