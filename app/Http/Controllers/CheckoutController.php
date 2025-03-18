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

    public function index()
    {
        return view('checkout');
    }

    public function invoice()
    {
        return view('invoice');
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
