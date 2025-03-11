<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Order;
use App\Models\User;

class CartService
{
	public function getAllItems(User $user) : \Illuminate\Support\Collection | \Illuminate\Database\Eloquent\Collection
	{
		$order = $this->getActiveOrder($user);
		return $order ? $order->books : collect();
	}

	public function addCartItem(User $user, int $bookId, int $qty): bool
	{
		$book = Book::findOrFail($bookId);

		if ($qty > $book->in_stock_number) {
			return false;
		}

		$order = $this->getOrCreateOrder($user);

		// Check if the book is already in the order
		$existingItem = $order->books()->where('book_id', $book->id)->first();

		if ($existingItem) {
			$existingItem->pivot->quantity += $qty;
			$existingItem->pivot->save();
		} else {
			$order->books()->attach($book->id, [
				'quantity' => $qty,
				'price' => $book->price,
			]);
		}

		$this->updateOrderTotal($order);
		return true;
	}

	public function updateCartItem(User $user, int $bookId, int $qty): bool
	{
		$order = $this->getActiveOrder($user);

		if (!$order) {
			return false;
		}

		$cartItem = $order->books()->where('book_id', $bookId)->first();

		if (!$cartItem) {
			return false;
		}

		$cartItem->pivot->quantity = $qty;
		$cartItem->pivot->save();

		$this->updateOrderTotal($order);
		return true;
	}

	public function removeCartItem(User $user, int $bookId): bool
	{
		$order = $this->getActiveOrder($user);

		if (!$order) {
			return false;
		}

		$order->books()->detach($bookId);
		$this->updateOrderTotal($order);

		return true;
	}

	public function removeAllItems(User $user): bool
	{
		$order = $this->getActiveOrder($user);

		if (!$order) {
			return false;
		}

		$order->books()->detach();
		$this->updateOrderTotal($order);

		return true;
	}

	private function updateOrderTotal(Order $order): void
	{
		$total = $order->books->sum(fn($book) => $book->pivot->quantity * $book->pivot->price);
		$order->order_total = $total;
		$order->save();
	}

	private function getActiveOrder(User $user): ?Order
	{
		return $user->orders()->where('order_status', 'pending')->first();
	}

	private function getOrCreateOrder(User $user): Order
	{
		return $this->getActiveOrder($user) ?? Order::create([
			'user_id' => $user->id,
			'order_status' => 'pending',
			'order_total' => 0.0,
		]);
	}
}
