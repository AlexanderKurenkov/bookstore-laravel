<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderCancellation;
use App\Models\OrderReturn;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function cancelOrder(int $userId, int $orderId, string $reason): void
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', $userId)
            ->whereIn('order_status', ['pending', 'processing', 'shipped'])
            ->firstOrFail();

        DB::beginTransaction();
        try {
            $order->order_status = 'cancelled';
            $order->save();

            $cancellation = new OrderCancellation();
            $cancellation->order_id = $order->id;
            $cancellation->cancellation_reason = $reason;

            if ($order->payment_status === 'paid') {
                $cancellation->refunded_amount = $order->order_total;
            }

            $cancellation->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getDeliveredOrdersForUser(int $userId): Collection
    {
        return Order::where('user_id', $userId)
            ->where('order_status', 'delivered')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getOrderBooks(int $orderId, int $userId): Collection
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', $userId)
            ->where('order_status', 'delivered')
            ->firstOrFail();

        return $order->books()
            ->withPivot('quantity', 'price')
            ->get()
            ->map(function ($book) use ($orderId) {
                $returnedQuantity = OrderReturn::where('order_id', $orderId)
                    ->where('book_id', $book->id)
                    ->whereIn('return_status', ['approved', 'processed'])
                    ->sum('return_quantity');

                $remainingQuantity = $book->pivot->quantity - $returnedQuantity;

                return $remainingQuantity > 0 ? [
                    'id' => $book->id,
                    'title' => $book->title,
                    'quantity' => $remainingQuantity
                ] : null;
            })
            ->filter()
            ->values();
    }

    public function createReturn(int $userId, array $data): OrderReturn
    {
        $order = Order::where('id', $data['order_id'])
            ->where('user_id', $userId)
            ->where('order_status', 'delivered')
            ->firstOrFail();

        $orderItem = $order->books()->where('books.id', $data['book_id'])->firstOrFail();

        $returnedQuantity = OrderReturn::where('order_id', $order->id)
            ->where('book_id', $data['book_id'])
            ->whereIn('return_status', ['approved', 'processed'])
            ->sum('return_quantity');

        $remainingQuantity = $orderItem->pivot->quantity - $returnedQuantity;

        if ($data['return_quantity'] > $remainingQuantity) {
            throw new \Exception("Вы можете вернуть максимум {$remainingQuantity} экземпляров этой книги.");
        }

        $reasonMap = [
            'damaged' => 'Товар поврежден',
            'wrong_item' => 'Получен не тот товар',
            'quality_issue' => 'Проблема с качеством',
            'not_as_described' => 'Не соответствует описанию',
            'changed_mind' => 'Передумал(а)'
        ];
        $returnReason = $reasonMap[$data['return_reason_type']] ?? $data['return_reason'];

        $return = OrderReturn::create([
            'order_id' => $data['order_id'],
            'book_id' => $data['book_id'],
            'return_quantity' => $data['return_quantity'],
            'return_reason' => $returnReason,
            'return_status' => 'pending'
        ]);

        $order->update(['order_status' => 'returned']);

        return $return;
    }

    public function getReturnForUser(int $returnId, int $userId): OrderReturn
    {
        return OrderReturn::where('id', $returnId)
            ->whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->firstOrFail();
    }
}
