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
     * Show the invoice page.
     */
    public function invoice(): View|RedirectResponse
    {
        // Retrieve order ID from session
        $orderId = session('order_id');

        // Prevent direct access
        if (!$orderId) {
            return redirect()->route('checkout.index')->withErrors('Access denied.');
        }

        // Retrieve the order and remove it from session (one-time access)
        $order = Order::find($orderId);
        session()->forget('order_id');

        return view('invoice', compact('order'));
    }

    public function show(): View|RedirectResponse
    {
        // Получаем данные пользователя, если он авторизован
        $user = Auth::user();
        $result = $this->checkoutService->prepareCheckoutData($user);

        if (isset($result['redirect'])) {
            return redirect()->route('cart.index')->with($result['redirect']);
        }

        return view('checkout', $result);
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
