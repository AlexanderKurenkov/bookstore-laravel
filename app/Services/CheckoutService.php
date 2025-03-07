<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;

class CheckoutService
{
    public function prepareCheckoutData(User $user)
    {
        $cartItems = $user->orders()->where('order_status', 'pending')->first()?->books ?? collect();

        if ($cartItems->isEmpty()) {
            return ['redirect' => ['emptyCart' => true]];
        }

        foreach ($cartItems as $cartItem) {
            if ($cartItem->pivot->quantity > $cartItem->quantity_in_stock) {
                return ['redirect' => ['notEnoughStock' => true]];
            }
        }

        return compact('cartItems', 'user');
    }

    public function processOrder(User $user)
    {
        $order = Order::create([
            'user_id' => $user->id,
            'order_status' => 'confirmed',
            'order_total' => $this->calculateTotal($user),
        ]);

        // Optionally send confirmation email
        // Mail::to($user->email)->send(new OrderConfirmationMail($user, $order));

        return ['estimatedDeliveryDate' => now()->addDays(3)];
    }

    private function calculateTotal(User $user)
    {
        $order = $user->orders()->where('order_status', 'pending')->first();
        return $order?->books->sum(fn($book) => $book->pivot->quantity * $book->pivot->price) ?? 0;
    }
}
