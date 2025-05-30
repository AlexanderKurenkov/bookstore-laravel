<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Order;
use App\Models\User;

/**
 * Сервис для управления корзиной пользователя.
 *
 * Предоставляет методы для удаления отдельных товаров из корзины,
 * полной очистки корзины, а также обновления общей суммы заказа.
 */
class CartService
{
	public function removeCartItem(User $user, int $bookId): bool
	{
		// Получаем активный заказ пользователя
		$order = $this->getActiveOrder($user);

		// Если активного заказа нет, возвращаем false
		if (!$order) {
			return false;
		}

		// Удаляем книгу из заказа
		$order->books()->detach($bookId);

		// Обновляем общую сумму заказа после удаления товара
		$this->updateOrderTotal($order);

		return true; // Операция успешно выполнена
	}

	public function removeAllItems(): bool
	{
		// Удаляем данные о корзине из сессии
		if (session()->forget(['cart', 'cart_total'])) {
			return true; // Операция успешно выполнена
		}

		return false; // Ошибка при очистке корзины
	}

	private function updateOrderTotal(Order $order): void
	{
		$total = $order->books->sum(fn($book) => $book->pivot->quantity * $book->pivot->price);
		$order->order_total = $total;
		$order->save();
	}

	private function getActiveOrder(User $user): ?Order
	{
		return $user->orders()->where('order_status', 'pending')->first();
	}
}
