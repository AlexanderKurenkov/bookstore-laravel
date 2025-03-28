<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutProcessRequest;
use App\Models\Order;
use App\Services\CheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
    public function invoice(): View|RedirectResponse
    {
        // Retrieve order ID from session
        $orderId = session('order_id');

        // Prevent direct access
        if (!$orderId) {
            return redirect()->route('checkout.index')->withErrors('Access denied.');
        }

        // Retrieve the order and remove it from session (one-time access)
        $order = Order::find($orderId);
        session()->forget('order_id');

        return view('invoice', compact('order'));
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

    public function process(CheckoutProcessRequest $request): View|RedirectResponse
    {
        $user = Auth::user();

        // Process the order with validated data
        $result = $this->checkoutService->processOrder($user, $request->validated());

        // Redirect to checkout page if there's an issue
        if (isset($result['redirect'])) {
            return redirect()->route('checkout.index')->with($result['redirect']);
        }

        // Store the order ID in session
        session(['order_id' => $result['order']->id]);


        // Redirect to invoice route
        return redirect()->route('checkout.invoice');
    }
}
