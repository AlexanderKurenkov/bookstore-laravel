<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\BillingAddress;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckoutController extends Controller
{
	public function show(Request $request, $cartId)
	{
		$user = Auth::user();

		// ? redundant
		// if ($cartId != $user->shoppingCart->id) {
		// 	abort(500);
		// }

		$cartItemList = $this->cartItemService->findByShoppingCart($user->shoppingCart);
		if ($cartItemList->isEmpty()) {
			return redirect()->route('cart.index')->with('emptyCart', true);
		}

		foreach ($cartItemList as $cartItem) {
			if ($cartItem->book->in_stock_number < $cartItem->qty) {
				return redirect()->route('cart.index')->with('notEnoughStock', true);
			}
		}

		$userShippingList = $user->userShipping;
		$userPaymentList = $user->userPayment;

		$shippingAddress = new ShippingAddress();
		$billingAddress = new BillingAddress();
		$payment = new Payment();

		foreach ($userShippingList as $userShipping) {
			if ($userShipping->is_default) {
				$this->shippingAddressService->setByUserShipping($userShipping, $shippingAddress);
			}
		}

		foreach ($userPaymentList as $userPayment) {
			if ($userPayment->is_default) {
				$this->paymentService->setByUserPayment($userPayment, $payment);
				$this->billingAddressService->setByUserBilling($userPayment->userBilling, $billingAddress);
			}
		}

		return view('checkout', compact(
			'shippingAddress',
			'payment',
			'billingAddress',
			'cartItemList',
			'userShippingList',
			'userPaymentList',
			'user',
			'cartId'
		));
	}

	public function process(Request $request): View
	{
		$user = Auth::user();
		$shoppingCart = $user->shoppingCart;

		$cartItemList = $this->cartItemService->findByShoppingCart($shoppingCart);

		if ($request->billing_same_as_shipping == 'true') {
			$billingAddress = new BillingAddress($request->shippingAddress->toArray());
		}

		if ($this->validateRequiredFields($request, $billingAddress)) {
			return redirect()->route('checkout', ['id' => $shoppingCart->id])->with('missingRequiredField', true);
		}

		$order = $this->orderService->createOrder($shoppingCart, $request->shippingAddress, $billingAddress, $request->payment, $request->shippingMethod, $user);

		// Optionally send confirmation email
		// Mail::to($user->email)->send(new OrderConfirmationMail($user, $order));

		$this->shoppingCartService->clearShoppingCart($shoppingCart);

		$estimatedDeliveryDate = now()->addDays($request->shippingMethod == 'groundShipping' ? 5 : 3);

		return view('orderSubmittedPage', compact('estimatedDeliveryDate'));
	}

	private function validateRequiredFields(Request $request, $billingAddress)
	{
		return empty($request->shippingAddress->street1) || empty($request->shippingAddress->city) ||
			empty($request->shippingAddress->state) || empty($request->shippingAddress->zipcode) ||
			empty($request->payment->card_number) || empty($request->payment->cvc) ||
			empty($billingAddress->street1) || empty($billingAddress->city) ||
			empty($billingAddress->state) || empty($billingAddress->zipcode);
	}
}
