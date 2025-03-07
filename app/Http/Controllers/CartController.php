<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartController extends Controller
{
	protected CartService $cartService;

	public function __construct(CartService $cartService)
	{
		$this->cartService = $cartService;
	}

	public function index(): View
	{
		$user = Auth::user();
		$orderItems = $this->cartService->getAllItems($user);

		return view('cart', ['orderItems' => $orderItems]);
	}

	public function storeItem(Request $request): RedirectResponse
	{
		$user = Auth::user();
		$bookId = $request->input('book_id');
		$quantity = (int) $request->input('qty');

		$success = $this->cartService->addCartItem($user, $bookId, $quantity);

		return redirect()->route('cart.index')
			->with($success ? 'addBookSuccess' : 'notEnoughStock', true);
	}

	public function updateItem(Request $request): RedirectResponse
	{
		$user = Auth::user();
		$bookId = $request->input('book_id');
		$qty = (int) $request->input('qty');

		$this->cartService->updateCartItem($user, $bookId, $qty);

		return redirect()->route('cart.index');
	}

	public function destroyItem(Request $request): RedirectResponse
	{
		$user = Auth::user();
		$bookId = $request->query('book_id');

		$this->cartService->removeCartItem($user, $bookId);

		return redirect()->route('cart.index')->with('removeBookSuccess', true);
	}

	public function clear(): RedirectResponse
	{
		$user = Auth::user();
		$this->cartService->removeAllItems($user);

		return redirect()->route('cart.index')->with('clearCartSuccess', true);
	}
}
