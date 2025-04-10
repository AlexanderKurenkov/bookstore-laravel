<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class CartController extends Controller
{
	/**
	 * Сервис для работы с корзиной
	 */
	protected CartService $cartService;

	/**
	 * Конструктор контроллера
	 *
	 * @param CartService $cartService
	 */
	public function __construct(CartService $cartService)
	{
		$this->cartService = $cartService;
	}

	/**
	 * Отображает страницу корзины
	 *
	 * @return View
	 */
	public function index(): View
	{
		return view('cart');
	}

	/**
	 * Удаляет товар из корзины и перенаправляет обратно на страницу корзины
	 *
	 * @param Request $request
	 * @return RedirectResponse
	 */
	public function destroyItem(Request $request): RedirectResponse
	{
		$this->removeItem($request);
		return redirect()->route('cart.index')->with('removeBookSuccess', true);
	}

	/**
	 * Обновляет количество товара в корзине
	 *
	 * @param Request $request
	 * @param int $id ID книги
	 * @return RedirectResponse|null
	 */
	public function updateItem(Request $request, int $id): RedirectResponse
	{
		$request->validate([
			'quantity' => 'required|integer|min:1'
		]);

		$bookId = $id;
		$quantity = $request->quantity;

		$cart = session('cart', []);

		if (isset($cart[$bookId])) {
			$cart[$bookId]['quantity'] = $quantity;
			session(['cart' => $cart]);

			$cartTotal = $this->calculateCartTotal($cart);
			session(['cart_total' => $cartTotal]);

			return redirect()->route('cart.index');
		}
	}

	/**
	 * Очищает всю корзину
	 *
	 * @return RedirectResponse
	 */
	public function clear(): RedirectResponse
	{
		$this->cartService->removeAllItems();

		return redirect()->route('cart.index');
	}

	// ==============================================================
	// Методы API
	// ==============================================================

	/**
	 * Возвращает данные корзины для API
	 *
	 * @return JsonResponse
	 */
	public function getCart(): JsonResponse
	{
		$cart = session('cart', []);
		$cartTotal = $this->calculateCartTotal($cart);

		return response()->json([
			'success' => true,
			'count' => count($cart),
			'total' => number_format($cartTotal, 2),
			'items' => array_values($cart)
		]);
	}

	/**
	 * Добавляет товар в корзину через API
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function addItem(Request $request): JsonResponse
	{
		$request->validate([
			'id' => 'required|exists:books,id',
			'quantity' => 'required|integer|min:1'
		]);

		$bookId = $request->id;
		$quantity = $request->quantity;

		$book = Book::findOrFail($bookId);
		$cart = session('cart', []);

		if (isset($cart[$bookId])) {
			$cart[$bookId]['quantity'] += $quantity;
		} else {
			$cart[$bookId] = [
				'id' => $book->id,
				'title' => $book->title,
				'author' => $book->author,
				'price' => $book->price,
				'image' => $book->image_path,
				'quantity' => $quantity
			];
		}

		session(['cart' => $cart]);

		$cartTotal = $this->calculateCartTotal($cart);
		session(['cart_total' => $cartTotal]);

		return response()->json([
			'success' => true,
			'count' => count($cart),
			'total' => number_format($cartTotal, 2),
			'items' => array_values($cart)
		]);
	}

	/**
	 * Удаляет товар из корзины через API
	 *
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function removeItem(Request $request): JsonResponse
	{
		$request->validate([
			'id' => 'required'
		]);

		$bookId = $request->id;
		$cart = session('cart', []);

		if (isset($cart[$bookId])) {
			unset($cart[$bookId]);
			session(['cart' => $cart]);

			$cartTotal = $this->calculateCartTotal($cart);
			session(['cart_total' => $cartTotal]);

			return response()->json([
				'success' => true,
				'count' => count($cart),
				'total' => number_format($cartTotal, 2),
				'items' => array_values($cart)
			]);
		}

		return response()->json([
			'success' => false,
			'message' => 'Item not found in cart'
		], 404);
	}

	/**
	 * Подсчитывает итоговую сумму корзины
	 *
	 * @param array $cart
	 * @return float
	 */
	private function calculateCartTotal($cart): float
	{
		$total = 0;

		foreach ($cart as $item) {
			$total += $item['price'] * $item['quantity'];
		}

		return $total;
	}
}
