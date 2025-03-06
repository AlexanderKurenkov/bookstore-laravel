<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CartService
{
	protected Order $order;

	public function __construct()
	{
		$this->order = Auth::user()->orders->first();
	}

	public function getAllItems()
	{
		return $this->order->books;
	}

	public function addBookToCart(int $bookId, int $qty)
	{
		$book = Book::findOrFail($bookId);

		if ($qty > $book->in_stock_number) {
			return false;
		}

		// Logic to add book to the cart
		// Example: $this->order->addItem($book, $qty);

		return true;
	}

	public function updateCartItem(int $cartItemId, int $qty)
    {
        // Find the book item by ID
        $cartItem = Book::findOrFail($cartItemId);

        // Update the quantity
        $cartItem->qty = $qty;

        // Save the updated item
        $cartItem->save();
    }

	public function removeCartItem(int $cartItemId)
	{
		$cartItem = $this->order->books()->findOrFail($cartItemId);
		$cartItem->delete();
	}
}
