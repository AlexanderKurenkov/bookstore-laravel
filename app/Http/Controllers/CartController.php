<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

	// public function storeItem(Request $request) //: JsonResponse
	// {
	// 	// $user = Auth::user();
	// 	// $bookId = $request->input('bookId');
	// 	// $quantity = (int) $request->input('quantity');

	// 	// $success = $this->cartService->addCartItem($user, $bookId, $quantity);

	// 	// return redirect()->route('cart.index')
	// 	// 	->with($success ? 'addBookSuccess' : 'notEnoughStock', true);

	// 	//=====================================
	// 	// Validate incoming data (optional)
	// 	$validated = $request->validate([
	// 		'bookId' => 'required|integer|exists:books,id',
	// 		'quantity' => 'required|integer|min:1',
	// 	]);

	// 	// Retrieve item ID and quantity from the request
	// 	$id = $validated['bookId'];
	// 	$quantity = $validated['quantity'];

	// 	// Add the item to the cart (stored in the session for simplicity)
	// 	$cart = session()->get('cart', []);

	// 	if (isset($cart[$id])) {
	// 		// If the item already exists in the cart, increase its quantity
	// 		$cart[$id]['quantity'] += $quantity;
	// 	} else {
	// 		// Otherwise, add the item to the cart
	// 		$cart[$id] = [
	// 			'bookId' => $id,
	// 			'quantity' => $quantity,
	// 		];
	// 	}

	// 	session()->put('cart', $cart);

	// 	// Respond with a success message (optional)
	// 	return response()->json(['message' => 'Item added to cart successfully']);
	// }

	// CHECK same method below
	// public function updateItem(Request $request): RedirectResponse
	// {
	// 	$user = Auth::user();
	// 	$bookId = $request->input('book_id');
	// 	$quantity = (int) $request->input('quantity');

	// 	$this->cartService->updateCartItem($user, $bookId, $quantity);

	// 	return redirect()->route('cart.index');
	// }

	public function destroyItem(Request $request): RedirectResponse
	{
		// $user = Auth::user();
		// $bookId = $request->query('book_id');

		// $this->cartService->removeCartItem($user, $bookId);

		// return redirect()->route('cart.index')->with('removeBookSuccess', true);
		//

		$this->removeItem($request);
		return redirect()->route('cart.index')->with('removeBookSuccess', true);
	}


	public function updateItem(Request $request, int $id) : RedirectResponse
	{
		$request->validate([
			// 'id' => 'required',
			'quantity' => 'required|integer|min:1'
		]);

		$bookId = $id;
		$quantity = $request->quantity;

		// Get current cart
		$cart = session('cart', []);

		// Update quantity if item exists
		if (isset($cart[$bookId])) {
			$cart[$bookId]['quantity'] = $quantity;
			session(['cart' => $cart]);

			// Calculate cart total
			$cartTotal = $this->calculateCartTotal($cart);
			session(['cart_total' => $cartTotal]);

			return redirect()->route('cart.index');
		}
	}

	public function clear(): RedirectResponse
	{
		$this->cartService->removeAllItems();

		return redirect()->route('cart.index');
	}

	//===============================
	/**
	 * Get cart data for API
	 */
	public function getCart()
	{
		$cart = session('cart', []);
		$cartTotal = $this->calculateCartTotal($cart);

		return response()->json([
			'success' => true,
			'count' => count($cart),
			'total' => number_format($cartTotal, 2),
			'items' => array_values($cart)
		]);
	}

	/**
	 * Add item to cart via API
	 */
	public function addItem(Request $request)
	{
		$request->validate([
			'id' => 'required|exists:books,id',
			'quantity' => 'required|integer|min:1'
		]);

		$bookId = $request->id;
		$quantity = $request->quantity;

		// Get the book
		$book = Book::findOrFail($bookId);

		// Get current cart
		$cart = session('cart', []);

		// Check if book already in cart
		if (isset($cart[$bookId])) {
			$cart[$bookId]['quantity'] += $quantity;
		} else {
			// Add new item to cart
			$cart[$bookId] = [
				'id' => $book->id,
				'title' => $book->title,
				'author' => $book->author,
				'price' => $book->price,
				'image' => $book->image_path,
				'quantity' => $quantity
			];
		}

		// Update session
		session(['cart' => $cart]);

		// Calculate cart total
		$cartTotal = $this->calculateCartTotal($cart);
		session(['cart_total' => $cartTotal]);

		return response()->json([
			'success' => true,
			'count' => count($cart),
			'total' => number_format($cartTotal, 2),
			'items' => array_values($cart)
		]);
	}

	/**
	 * Remove item from cart via API
	 */
	public function removeItem(Request $request)
	{
		$request->validate([
			'id' => 'required'
		]);

		$bookId = $request->id;

		// Get current cart
		$cart = session('cart', []);

		// Remove item if exists
		if (isset($cart[$bookId])) {
			unset($cart[$bookId]);
			session(['cart' => $cart]);

			// Calculate cart total
			$cartTotal = $this->calculateCartTotal($cart);
			session(['cart_total' => $cartTotal]);

			return response()->json([
				'success' => true,
				'count' => count($cart),
				'total' => number_format($cartTotal, 2),
				'items' => array_values($cart)
			]);
		}

		return response()->json([
			'success' => false,
			'message' => 'Item not found in cart'
		], 404);
	}

	/**
	 * Calculate cart total
	 */
	private function calculateCartTotal($cart)
	{
		$total = 0;

		foreach ($cart as $item) {
			$total += $item['price'] * $item['quantity'];
		}

		return $total;
	}
}
