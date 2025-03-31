<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderCancellation;
use App\Models\OrderReturn;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * Отмена заказа пользователем.
     *
     * @param int $userId Идентификатор пользователя
     * @param int $orderId Идентификатор заказа
     * @param string $reason Причина отмены
     */
    public function cancelOrder(int $userId, int $orderId, string $reason): void
    {
        // Получаем заказ, если он принадлежит пользователю и его статус позволяет отмену
        $order = Order::where('id', $orderId)
            ->where('user_id', $userId)
            ->whereIn('order_status', ['pending', 'processing', 'shipped'])
            ->firstOrFail();

        DB::beginTransaction();
        try {
            // Обновляем статус заказа на "отменён"
            $order->order_status = 'cancelled';
            $order->save();

            // Создаем запись об отмене заказа
            $cancellation = new OrderCancellation();
            $cancellation->order_id = $order->id;
            $cancellation->cancellation_reason = $reason;

            // Если заказ был оплачен, сохраняем сумму возврата
            if ($order->payment_status === 'paid') {
                $cancellation->refunded_amount = $order->order_total;
            }

            $cancellation->save();

            // Фиксация транзакции
            DB::commit();
        } catch (\Exception $e) {
            // Отказ транзакции при ошибке
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Получение списка доставленных заказов пользователя.
     *
     * @param int $userId Идентификатор пользователя
     * @return Collection Коллекция заказов
     */
    public function getDeliveredOrdersForUser(int $userId): Collection
    {
        return Order::where('user_id', $userId)
            ->where('order_status', 'delivered')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Получение списка книг из доставленного заказа.
     *
     * @param int $orderId Идентификатор заказа
     * @param int $userId Идентификатор пользователя
     * @return Collection Коллекция доступных книг
     */
    public function getOrderBooks(int $orderId, int $userId): Collection
    {
        // Проверяем, принадлежит ли заказ пользователю и доставлен ли он
        $order = Order::where('id', $orderId)
            ->where('user_id', $userId)
            ->where('order_status', 'delivered')
            ->firstOrFail();

        // Получаем книги из заказа с учетом уже возвращённых экземпляров
        return $order->books()
            ->withPivot('quantity', 'price')
            ->get()
            ->map(function ($book) use ($orderId) {
                // Подсчитываем количество возвращённых экземпляров книги
                $returnedQuantity = OrderReturn::where('order_id', $orderId)
                    ->where('book_id', $book->id)
                    ->whereIn('return_status', ['approved', 'processed'])
                    ->sum('return_quantity');

                // Вычисляем оставшееся количество
                $remainingQuantity = $book->pivot->quantity - $returnedQuantity;

                return $remainingQuantity > 0 ? [
                    'id' => $book->id,
                    'title' => $book->title,
                    'quantity' => $remainingQuantity
                ] : null;
            })
            ->filter() // Убираем книги с нулевым количеством
            ->values();
    }

    /**
     * Создание запроса на возврат книги.
     *
     * @param int $userId Идентификатор пользователя
     * @param array $data Данные запроса на возврат
     * @return OrderReturn Запись возврата
     * @throws \Exception Если количество книг для возврата превышает доступное
     */
    public function createReturn(int $userId, array $data): OrderReturn
    {
        // Проверяем существование заказа и его принадлежность пользователю
        $order = Order::where('id', $data['order_id'])
            ->where('user_id', $userId)
            ->where('order_status', 'delivered')
            ->firstOrFail();

        // Проверяем, есть ли эта книга в заказе
        $orderItem = $order->books()->where('books.id', $data['book_id'])->firstOrFail();

        // Подсчитываем уже возвращённые экземпляры
        $returnedQuantity = OrderReturn::where('order_id', $order->id)
            ->where('book_id', $data['book_id'])
            ->whereIn('return_status', ['approved', 'processed'])
            ->sum('return_quantity');

        // Вычисляем доступное количество для возврата
        $remainingQuantity = $orderItem->pivot->quantity - $returnedQuantity;

        if ($data['return_quantity'] > $remainingQuantity) {
            throw new \Exception("Вы можете вернуть максимум {$remainingQuantity} экземпляров этой книги.");
        }

        // Определяем причину возврата
        $reasonMap = [
            'damaged' => 'Товар поврежден',
            'wrong_item' => 'Получен не тот товар',
            'quality_issue' => 'Проблема с качеством',
            'not_as_described' => 'Не соответствует описанию',
            'changed_mind' => 'Передумал(а)'
        ];
        $returnReason = $reasonMap[$data['return_reason_type']] ?? $data['return_reason'];

        // Создаём запись возврата
        $return = OrderReturn::create([
            'order_id' => $data['order_id'],
            'book_id' => $data['book_id'],
            'return_quantity' => $data['return_quantity'],
            'return_reason' => $returnReason,
            'return_status' => 'pending'
        ]);

        // Обновляем статус заказа
        $order->update(['order_status' => 'returned']);

        return $return;
    }

    /**
     * Получение информации о возврате для пользователя.
     *
     * @param int $returnId Идентификатор возврата
     * @param int $userId Идентификатор пользователя
     * @return OrderReturn Запись возврата
     */
    public function getReturnForUser(int $returnId, int $userId): OrderReturn
    {
        return OrderReturn::where('id', $returnId)
            ->whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->firstOrFail();
    }
}
