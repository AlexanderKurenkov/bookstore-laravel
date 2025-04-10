<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Контроллер для управления корзиной товаров
 */
class CartController extends Controller
{
	/**
	 * Сервис для работы с корзиной
	 */
	protected CartService $cartService;

	/**
	 * Конструктор контроллера
	 *
	 * @param CartService $cartService Сервис корзины
	 */
	public function __construct(CartService $cartService)
	{
		$this->cartService = $cartService;
	}

	/**
	 * Отображает страницу корзины
	 *
	 * @return View Представление корзины
	 */
	public function index(): View
	{
		return view('cart');
	}

	/**
	 * Удаляет товар из корзины и делает редирект на страницу корзины
	 *
	 * @param Request $request HTTP-запрос
	 * @return RedirectResponse Редирект с флагом успешного удаления
	 */
	public function destroyItem(Request $request): RedirectResponse
	{
		$this->removeItem($request);
		return redirect()->route('cart.index')->with('removeBookSuccess', true);
	}

	/**
	 * Обновляет количество товара в корзине
	 *
	 * @param Request $request HTTP-запрос с новым количеством
	 * @param int $id ID книги
	 * @return RedirectResponse Редирект на страницу корзины
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
			// Обновляем количество
			$cart[$bookId]['quantity'] = $quantity;
			session(['cart' => $cart]);

			// Пересчитываем общую сумму
			$cartTotal = $this->calculateCartTotal($cart);
			session(['cart_total' => $cartTotal]);

			return redirect()->route('cart.index');
		}
	}

	/**
	 * Очищает всю корзину
	 *
	 * @return RedirectResponse Редирект на страницу корзины
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
	 * Возвращает содержимое корзины (API)
	 *
	 * @return JsonResponse JSON-ответ с данными корзины
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
	 * Добавляет товар в корзину (API)
	 *
	 * @param Request $request HTTP-запрос с ID книги и количеством
	 * @return JsonResponse JSON-ответ с обновлённой корзиной
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

		// Если книга уже есть в корзине — увеличиваем количество
		if (isset($cart[$bookId])) {
			$cart[$bookId]['quantity'] += $quantity;
		} else {
			// Иначе — добавляем новую запись
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

		// Обновляем сумму
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
	 * Удаляет товар из корзины (API)
	 *
	 * @param Request $request HTTP-запрос с ID книги
	 * @return JsonResponse JSON-ответ об успешности операции
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

		// Если товар не найден
		return response()->json([
			'success' => false,
			'message' => 'Товар не найден в корзине'
		], 404);
	}

	/**
	 * Подсчитывает общую стоимость корзины
	 *
	 * @param array $cart Массив товаров в корзине
	 * @return float Общая сумма
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
