<?php

namespace App\Services;

use App\Models\DeliveryDetail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckoutService
{
    public function prepareCheckoutData(User $user): array
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
    public function processOrder(User $user, array $validated): array
    {
        // Calculate shipping cost based on cart total
        $cartTotal = session('cart_total', 0);
        $shippingCost = ($validated['delivery_method'] === 'standard' && $cartTotal < 2000) ? 300 : 0;

        // Ensure delivery details exist
        $deliveryDetail = DeliveryDetail::firstOrCreate(
            ['user_id' => $user->id], // Find existing record for user
            [
                'address_line1' => $validated['address'],
                'city' => $validated['city'],
                'state' => $validated['region'],
                'postal_code' => $validated['postal_code'],
                'country' => 'Unknown', // Ensure a valid default country
                'phone' => $validated['phone'],
                'user_comment' => $validated['user_comment'] ?? null, // Optional
            ]
        );

        // Create order (only correct fields)
        $order = Order::create([
            'user_id' => $user->id,
            'delivery_detail_id' => $deliveryDetail->id, // ✅ Cannot be null now
            'order_status' => 'pending',
            'order_total' => $cartTotal + $shippingCost,
        ]);

        // Add books to order (many-to-many relationship)
        $cartItems = session('cart', []);
        foreach ($cartItems as $item) {
            $order->books()->attach($item['id'], [
                'quantity' => $item['quantity'],
                'price' => $item['price'], // Snapshot price at purchase time
            ]);
        }

        // Clear cart after order is placed
        Session::forget(['cart', 'cart_total']);

        return [
            'order' => $order,
            'estimatedDeliveryDate' => now()->addDays(3)->format('d.m.Y')
        ];
    }

    private function calculateTotal(User $user): mixed
    {
        $order = $user->orders()->where('order_status', 'pending')->first();
        return $order?->books()->sum(fn($book) => $book->pivot->quantity * $book->pivot->price) ?? 0;
    }
}
