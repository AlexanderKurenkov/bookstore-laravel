
<?php

namespace App\Services;

use App\Models\DeliveryDetail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckoutService
{
    public function prepareCheckoutData(User $user): array
    {
        $cartItems = $user->orders()->where('order_status', 'pending')->first()?->books() ?? collect();

        if ($cartItems->isEmpty()) {
            return ['redirect' => ['emptyCart' => true]];
        }

        foreach ($cartItems as $cartItem) {
            if ($cartItem->pivot->quantity > $cartItem->quantity_in_stock) {
                return ['redirect' => ['notEnoughStock' => true]];
            }
        }

        return compact('cartItems', 'user');
    }

    /**
     * Обрабатывает создание заказа.
     *
     * Метод получает валидированные данные оформления заказа, рассчитывает стоимость доставки
     * и сохраняет заказ в базе данных. Также проверяется наличие данных о доставке,
     * при необходимости создается соответствующая запись. После этого товары из корзины
     * привязываются к заказу, а корзина очищается.
     *
     * В результате метод возвращает созданный заказ и предполагаемую дату доставки.
     *
     * @param User $user Аутентифицированный пользователь, оформляющий заказ.
     * @param array $validated Валидированные данные запроса, содержащие информацию о заказе.
     * @return array Массив с созданным заказом и предполагаемой датой доставки.
     */
    public function processOrder(User $user, array $validated): array
    {
        // Рассчитываем стоимость доставки в зависимости от метода доставки
        $cartTotal = session('cart_total', 0);
        $shippingCost = ($validated['delivery_method'] === 'standard') ? 300 : 0;

        // Проверяем существование данных о доставке и создаем их при необходимости
        $deliveryDetail = DeliveryDetail::firstOrCreate(
            ['user_id' => $user->id], // Поиск существующей записи о доставке для пользователя
            [
                'address_line1' => $validated['address'],
                'city' => $validated['city'],
                'state' => $validated['region'],
                'postal_code' => $validated['postal_code'],
                'country' => 'Unknown', // Устанавливаем значение по умолчанию
                'phone' => $validated['phone'],
                'user_comment' => $validated['user_comment'] ?? null, // Опциональный комментарий пользователя
            ]
        );

        // Создаем заказ
        $order = Order::create([
            'user_id' => $user->id,
            'delivery_detail_id' => $deliveryDetail->id,
            'order_status' => 'pending',
            'order_total' => $cartTotal + $shippingCost,
        ]);

        // Добавляем книги в заказ (отношение многие ко многим)
        $cartItems = session('cart', []);
        foreach ($cartItems as $item) {
            $order->books()->attach($item['id'], [
                'quantity' => $item['quantity'],
                'price' => $item['price'], // Фиксируем цену на момент покупки
            ]);
        }

        // Очищаем корзину после оформления заказа
        Session::forget(['cart', 'cart_total']);

        return [
            'order' => $order,
            'estimatedDeliveryDate' => now()->addDays(3)->format('d.m.Y')
        ];
    }


    private function calculateTotal(User $user): mixed
    {
        $order = $user->orders()->where('order_status', 'pending')->first();
        return $order?->books()->sum(fn($book) => $book->pivot->quantity * $book->pivot->price) ?? 0;
    }
}
