<?php

namespace App\Http\Controllers;

use App\Models\BillingAddress;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ShippingAddress;
use App\Models\ShoppingCart;
use App\Models\User;
use App\Models\UserBilling;
use App\Models\UserPayment;
use App\Models\UserShipping;
use App\Services\BillingAddressService;
use App\Services\CartItemService;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Services\ShippingAddressService;
use App\Services\ShoppingCartService;
use App\Services\UserPaymentService;
use App\Services\UserService;
use App\Services\UserShippingService;
use App\Utility\USConstants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
	private $shippingAddress;
	private $billingAddress;
	private $payment;
	private $userService;
	private $cartItemService;
	private $shoppingCartService;
	private $shippingAddressService;
	private $billingAddressService;
	private $paymentService;
	private $userShippingService;
	private $userPaymentService;
	private $orderService;

	public function __construct(
		UserService $userService,
		CartItemService $cartItemService,
		ShoppingCartService $shoppingCartService,
		ShippingAddressService $shippingAddressService,
		BillingAddressService $billingAddressService,
		PaymentService $paymentService,
		UserShippingService $userShippingService,
		UserPaymentService $userPaymentService,
		OrderService $orderService
	) {
		$this->userService = $userService;
		$this->cartItemService = $cartItemService;
		$this->shoppingCartService = $shoppingCartService;
		$this->shippingAddressService = $shippingAddressService;
		$this->billingAddressService = $billingAddressService;
		$this->paymentService = $paymentService;
		$this->userShippingService = $userShippingService;
		$this->userPaymentService = $userPaymentService;
		$this->orderService = $orderService;
	}

	public function checkout(Request $request, $cartId)
	{
		$user = Auth::user();

		if ($cartId != $user->shoppingCart->id) {
			return redirect()->route('errors.500');
		}

		$cartItemList = $this->cartItemService->findByShoppingCart($user->shoppingCart);
		if ($cartItemList->isEmpty()) {
			return redirect()->route('shoppingCart.cart')->with('emptyCart', true);
		}

		foreach ($cartItemList as $cartItem) {
			if ($cartItem->book->in_stock_number < $cartItem->qty) {
				return redirect()->route('shoppingCart.cart')->with('notEnoughStock', true);
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

	public function checkoutPost(Request $request)
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

	public function setShippingAddress(Request $request, $userShippingId)
	{
		$user = Auth::user();
		$userShipping = $this->userShippingService->findById($userShippingId);

		if ($userShipping->user->id != $user->id) {
			return redirect()->route('errors.500');
		}

		$shippingAddress = new ShippingAddress();
		$this->shippingAddressService->setByUserShipping($userShipping, $shippingAddress);

		$cartItemList = $this->cartItemService->findByShoppingCart($user->shoppingCart);

		return view('checkout', compact(
			'shippingAddress',
			'cartItemList',
			'user'
		));
	}

	public function setPaymentMethod(Request $request, $userPaymentId)
	{
		$user = Auth::user();
		$userPayment = $this->userPaymentService->findById($userPaymentId);
		$userBilling = $userPayment->userBilling;

		if ($userPayment->user->id != $user->id) {
			return redirect()->route('errors.500');
		}

		$payment = new Payment();
		$this->paymentService->setByUserPayment($userPayment, $payment);

		$billingAddress = new BillingAddress();
		$this->billingAddressService->setByUserBilling($userBilling, $billingAddress);

		$cartItemList = $this->cartItemService->findByShoppingCart($user->shoppingCart);

		return view('checkout', compact(
			'payment',
			'billingAddress',
			'shippingAddress',
			'cartItemList',
			'user'
		));
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
