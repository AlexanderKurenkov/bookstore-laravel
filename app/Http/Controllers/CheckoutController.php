<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutProcessRequest;
use App\Models\Order;
use App\Services\CheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    protected CheckoutService $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    /**
     * Отображает страницу оформления заказа.
     *
     * Метод проверяет, есть ли у пользователя товары в корзине. Если корзина пуста,
     * выполняется редирект на страницу корзины с сообщением об ошибке. В противном случае
     * загружается представление страницы оформления заказа с данными пользователя, списком товаров
     * и общей стоимостью корзины.
     *
     * @return View|RedirectResponse Представление страницы оформления заказа или редирект на страницу корзины.
     */
    public function index()
    {
        // Получаем данные пользователя, если он авторизован
        $user = Auth::user();

        // Получаем данные корзины
        $cartItems = session('cart', []);
        $cartTotal = session('cart_total', 0);

        // Если корзина пуста, перенаправляем пользователя на страницу корзины
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Ваша корзина пуста');
        }

        // Отображаем страницу оформления заказа
        return view('checkout', compact('user', 'cartItems', 'cartTotal'));
    }

    /**
     * Отображает страницу счета (инвойса) после оформления заказа.
     *
     * Проверяет наличие идентификатора заказа в сессии.
     * Если идентификатор отсутствует, выполняется редирект с сообщением об ошибке.
     * После получения заказа идентификатор удаляется из сессии (одноразовый доступ).
     *
     * @return View|RedirectResponse Представление страницы счета или редирект при ошибке доступа
     */
    public function invoice(): View|RedirectResponse
    {
        // Получение ID заказа из сессии
        $orderId = session('order_id');

        // Предотвращение прямого доступа без ID заказа
        if (!$orderId) {
            return redirect()->route('checkout.index')->withErrors('Доступ запрещён.');
        }

        // Получение заказа по ID и удаление ID из сессии (одноразовый доступ)
        $order = Order::find($orderId);
        session()->forget('order_id');

        // Отображение представления счета с переданным заказом
        return view('invoice', compact('order'));
    }

    /**
     * Обрабатывает заказ на этапе оформления.
     *
     * Метод получает данные из формы, выполняет их валидацию и передает в сервис оформления заказа.
     * Если в процессе обработки возникает ошибка (например, недостаточное количество товара),
     * пользователя перенаправляют обратно на страницу оформления заказа с соответствующим сообщением.
     * В случае успешного оформления заказ сохраняется, его идентификатор записывается в сессию,
     * после чего выполняется редирект на страницу счета (invoice).
     *
     * @param CheckoutProcessRequest $request Входящий HTTP-запрос с валидированными данными оформления заказа.
     * @return View|RedirectResponse Представление страницы счета или редирект обратно на страницу оформления.
     */
    public function process(CheckoutProcessRequest $request): View|RedirectResponse
    {
        // Получаем данные пользователя, если он авторизован
        $user = Auth::user();

        // Обрабатываем заказ с валидированными данными
        $result = $this->checkoutService->processOrder($user, $request->validated());

        // Если возникли ошибки, перенаправляем пользователя обратно на страницу оформления
        if (isset($result['redirect'])) {
            return redirect()->route('checkout.index')->with($result['redirect']);
        }

        // Сохраняем идентификатор заказа в сессии
        session(['order_id' => $result['order']->id]);

        // Перенаправляем пользователя на страницу счета
        return redirect()->route('checkout.invoice');
    }
}
