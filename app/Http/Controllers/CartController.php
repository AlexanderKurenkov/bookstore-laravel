<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use App\Services\CartService;
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

	/**
	 * Display all itmes in the shopping cart.
	 */
	public function index(): View
	{
		// Get the authenticated user
		$user = Auth::user();

		// Fetch the user's open order (cart)
		$order = $user->orders->where('order_status', 'pending')->first();

		if (!$order) {
			// If no pending order, create one (i.e., empty cart)
			$order = Order::create([
				'user_id' => $user->id,
				'order_status' => 'pending',
				'order_total' => 0.0,
			]);
		}

		$orderItems = $order->books; // Get the books in the order

		return view('cart', [
			'order' => $order,
			'orderItems' => $orderItems
		]);

		// return view('cart', [
		// 	'cartItemList' => $this->cartService->getAllItems(),
		// ]);
	}

	/**
	 * Add an item to the shopping cart.
	 */
	public function storeItem(Request $request): RedirectResponse
	{
		$user = Auth::user();
		$book = Book::findOrFail($request->input('book_id'));
		$quantity = (int) $request->input('qty');

		// Fetch the user's open order (cart)
		$order = $user->orders()->where('order_status', 'pending')->first();

		if (!$order) {
			// If no pending order exists, create one
			$order = Order::create([
				'user_id' => $user->id,
				'order_status' => 'pending',
				'order_total' => 0.0,
			]);
		}

		// Check if the book is already in the order
		$existingItem = $order->books()->where('book_id', $book->id)->first();

		if ($existingItem) {
			// If the book is already in the order, update the quantity
			$existingItem->pivot->quantity += $quantity;
			$existingItem->pivot->save();
		} else {
			// Add new book to the order
			$order->books()->attach($book->id, [
				'quantity' => $quantity,
				'price' => $book->price // Assuming a `price` attribute exists on the Book model
			]);
		}

		// Update the order total (recalculate if needed)
		$this->updateOrderTotal($order);

		return redirect()->route('cart.index')
			->with('addBookSuccess', true);

		// $success = $this->cartService->addBookToCart(
		// 	$request->input('book_id'),
		// 	(int) $request->input('quantity')
		// );

		// return redirect()->route('cart.index')
		// 	->with($success ? 'addBookSuccess' : 'notEnoughStock', true);
	}

	/**
	 * Update an item in the shopping cart.
	 */
	public function updateItem(Request $request): RedirectResponse
	{
		$cartItemId = $request->input('id');
		$qty = (int)$request->input('qty');

		// Call the CartService to update the cart item
		$this->cartService->updateCartItem($cartItemId, $qty);

		return redirect()->route('cart.index');
	}

	/**
	 * Remove an item from the shopping cart.
	 */
	public function destroyItem(Request $request): RedirectResponse
	{
		$bookId = $request->query('book_id');
        $user = Auth::user();

        // Fetch the user's open order (cart)
        $order = $user->orders->where('order_status', 'pending')->first();

        if (!$order) {
            return redirect()->route('cart.index')->with('error', 'No active order found.');
        }

        // Remove the book from the order
        $order->books()->detach($bookId);

        // Update the order total
        $this->updateOrderTotal($order);

        return redirect()->route('cart.index')
            ->with('removeBookSuccess', true);

		// $this->cartService->removeCartItem($request->query('id'));
		// return redirect()->route('cart.index');
	}

	/**
     * Remove all items from the user's order (cart).
     */
    public function clear(): RedirectResponse
    {
        $user = Auth::user();

        // Fetch the user's open order (cart)
        $order = $user->orders->where('order_status', 'pending')->first();

        if (!$order) {
            return redirect()->route('cart.index')->with('error', 'No active order found.');
        }

        // Detach all books from the order (empty the cart)
        $order->books()->detach();

        // Update the order total
        $this->updateOrderTotal($order);

        return redirect()->route('cart.index')->with('clearCartSuccess', true);
    }

	/**
     * Update the order's total price.
     */
    private function updateOrderTotal(Order $order)
    {
        $total = 0;

        // Calculate the total price of the books in the order
        foreach ($order->books as $book) {
            $total += $book->pivot->quantity * $book->pivot->price;
        }

        // Update the order's total price
        $order->order_total = $total;
        $order->save();
    }
}
