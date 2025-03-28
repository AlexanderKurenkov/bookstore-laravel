<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class CheckoutService
{
    public function prepareCheckoutData(User $user) : array
    {
        $cartItems = $user->orders()->where('order_status', 'pending')->first()?->books() ?? collect();

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

    /**
     * Process an order.
     *
     * @param User $user The authenticated user
     * @param Request $request The request data
     * @return array
     */
    public function processOrder(User $user, Request $request) : array
    {
        // Validate the order data
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'region' => 'nullable|string|max:255',
            'postal_code' => 'required|string|max:20',
            'delivery_method' => 'required|in:standard,pickup',
            'payment_method' => 'required|in:card,cash',
            'terms' => 'required|accepted',
        ]);
        
        // If payment method is card, validate card details
        if ($request->payment_method === 'card') {
            $request->validate([
                'card_number' => 'required|string|max:19',
                'card_expiry' => 'required|string|max:5',
                'card_cvv' => 'required|string|max:4',
                'card_holder' => 'required|string|max:255',
            ]);
        }
        
        // Create the order
        $order = Order::create([
            'user_id' => $user->id,
            'order_status' => 'confirmed',
            'order_number' => 'ORD-' . rand(10000, 99999),
            'customer_name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'region' => $validated['region'],
            'postal_code' => $validated['postal_code'],
            'delivery_method' => $validated['delivery_method'],
            'payment_method' => $validated['payment_method'],
            'order_total' => $this->calculateTotal($user),
            'shipping_cost' => $validated['delivery_method'] === 'standard' ? 
                (session('cart_total', 0) >= 2000 ? 0 : 300) : 0,
        ]);
        
        // Add order items from cart
        $cartItems = session('cart', []);
        foreach ($cartItems as $item) {
            $order->items()->create([
                'book_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }
        
        // Clear the cart
        session()->forget(['cart', 'cart_total']);
        
        // Optionally send confirmation email
        // Mail::to($user->email)->send(new OrderConfirmationMail($user, $order));
        
        return [
            'order' => $order,
            'estimatedDeliveryDate' => now()->addDays(3)->format('d.m.Y')
        ];
    }

    private function calculateTotal(User $user) : mixed
    {
        $order = $user->orders()->where('order_status', 'pending')->first();
        return $order?->books()->sum(fn($book) => $book->pivot->quantity * $book->pivot->price) ?? 0;
    }
}

