Я планирую получить бакалавр по программной инженерии и занимаюсь написанием отчета по практике "Разработка интернет-магазина по продаже книг".
Мне нужуно, что бы ты описал, как представленный ниже код реализует функциональность корзины в онлайн магазине.
ВАЖНО:
- в ответе не используй списки текста (только непрерывный текст).
- проиллюстрируй свой ответ фрагментами кода, представленного ниже.
```
<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Order;
use App\Models\User;

class CartService
{
	public function getAllItems(User $user): \Illuminate\Support\Collection | \Illuminate\Database\Eloquent\Collection
	{
		// Получаем активный (незавершенный) заказ пользователя
		$order = $this->getActiveOrder($user);

		// Если заказ найден, возвращаем связанные с ним книги, иначе — пустую коллекцию
		return $order ? $order->books : collect();
	}

	public function addCartItem(User $user, int $bookId, int $qty): bool
	{
		// Находим книгу по ID или выбрасываем исключение, если она не найдена
		$book = Book::findOrFail($bookId);

		// Проверяем, есть ли достаточное количество книги на складе
		if ($qty > $book->in_stock_number) {
			return false; // Если недостаточно, операция отклоняется
		}

		// Получаем активный заказ пользователя или создаем новый
		$order = $this->getOrCreateOrder($user);

		// Проверяем, есть ли уже эта книга в заказе
		$existingItem = $order->books()->where('book_id', $book->id)->first();

		if ($existingItem) {
			// Если книга уже есть в заказе, увеличиваем ее количество
			$existingItem->pivot->quantity += $qty;
			$existingItem->pivot->save();
		} else {
			// Если книги нет в заказе, добавляем новую запись в промежуточную таблицу
			$order->books()->attach($book->id, [
				'quantity' => $qty,
				'price' => $book->price,
			]);
		}

		// Обновляем общую сумму заказа
		$this->updateOrderTotal($order);
		return true; // Операция успешно выполнена
	}


	public function updateCartItem(User $user, int $bookId, int $qty): bool
	{
		$order = $this->getActiveOrder($user);

		if (!$order) {
			return false;
		}

		$cartItem = $order->books()->where('book_id', $bookId)->first();

		if (!$cartItem) {
			return false;
		}

		$cartItem->pivot->quantity = $qty;
		$cartItem->pivot->save();

		$this->updateOrderTotal($order);
		return true;
	}

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

	private function getOrCreateOrder(User $user): Order
	{
		return $this->getActiveOrder($user) ?? Order::create([
			'user_id' => $user->id,
			'order_status' => 'pending',
			'order_total' => 0.0,
		]);
	}
}
