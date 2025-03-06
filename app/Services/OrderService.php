<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderService
{
	public function createOrder(array $cartItems, float $totalPrice)
	{
		return DB::transaction(function () use ($cartItems, $totalPrice) {
			$order = Order::create([
				'user_id' => Auth::id(),
				'total_price' => $totalPrice,
				'status' => 'pending'
			]);

			foreach ($cartItems as $item) {
				$order->books->attach($item['book_id'], ['qty' => $item['qty']]);
			}

			return $order;
		});
	}
}
