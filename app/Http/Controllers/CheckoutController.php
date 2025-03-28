<?php

namespace App\Http\Controllers;

use App\Services\CheckoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CheckoutController extends Controller
{
    protected CheckoutService $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    /**
     * Show the checkout form.
     */
    public function index()
    {
        // If user is authenticated, get their data
        $user = Auth::user();
        
        // Get cart data
        $cartItems = session('cart', []);
        $cartTotal = session('cart_total', 0);
        
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Ваша корзина пуста');
        }
        
        return view('checkout', compact('user', 'cartItems', 'cartTotal'));
    }

    /**
     * Show the invoice page.
     */
    public function invoice()
    {
        // Get the authenticated user
        $user = Auth::user();
        
        // For demo purposes, create a sample order
        $order = (object)[
            'order_number' => 'ORD-' . rand(10000, 99999),
            'created_at' => now()->format('d.m.Y H:i'),
            'payment_method' => 'Банковская карта',
            'card_last4' => '1234',
            'delivery_method' => 'Стандартная доставка',
            'expected_delivery' => now()->addDays(3)->format('d.m.Y'),
            'subtotal' => session('cart_total', 1190),
            'shipping_cost' => session('cart_total', 0) >= 2000 ? 0 : 300,
            'total' => session('cart_total', 1190) + (session('cart_total', 0) >= 2000 ? 0 : 300),
            'items' => collect(session('cart', []))->map(function($item) {
                return (object)$item;
            })
        ];
        
        return view('invoice', compact('user', 'order'));
    }

    public function show(): View|RedirectResponse
    {
        $user = Auth::user();
        $result = $this->checkoutService->prepareCheckoutData($user);

        if (isset($result['redirect'])) {
            return redirect()->route('cart.index')->with($result['redirect']);
        }

        return view('checkout', $result);
    }

    public function process(Request $request): View|RedirectResponse
    {
        $user = Auth::user();
        $result = $this->checkoutService->processOrder($user, $request);

        if (isset($result['redirect'])) {
            return redirect()->route('checkout.show')->with($result['redirect']);
        }

        // ? cart view or something different (maybe new view like 'cart.submitted')
        return view('cart', $result);
    }
}

