<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /** @var OrderService Сервис для работы с заказами. */
    protected OrderService $orderService;

    /**
     * Конструктор контроллера заказов.
     *
     * @param OrderService $orderService Сервис для управления заказами и возвратами.
     */
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Отображает форму возврата для доставленных заказов пользователя.
     *
     * @param int $id ID пользователя (необязательно используется в методе)
     * @return \Illuminate\View\View Представление с формой возврата.
     */
    public function edit($id)
    {
        // Получаем список доставленных заказов пользователя
        $deliveredOrders = $this->orderService->getDeliveredOrdersForUser(auth()->id());

        // Возвращаем представление с переданными данными
        return view('returns.edit', compact('deliveredOrders'));
    }

    /**
     * Обрабатывает форму возврата товара и создаёт соответствующую запись.
     *
     * @param Request $request HTTP-запрос, содержащий данные формы возврата.
     * @return \Illuminate\Http\RedirectResponse Редирект на страницу подтверждения или обратно с ошибкой.
     */
    public function store(Request $request)
    {
        // Валидация входных данных
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id', // ID заказа должен существовать
            'book_id' => 'required|exists:books,id', // ID книги должен существовать
            'return_quantity' => 'required|integer|min:1', // Количество должно быть положительным числом
            'return_reason_type' => 'required|string', // Тип причины возврата обязателен
            'return_reason' => 'nullable|required_if:return_reason_type,other|string', // Причина обязательна, если выбран тип "другое"
            'agree_terms' => 'required|accepted', // Пользователь должен согласиться с условиями
        ]);

        // Если валидация не пройдена, возвращаем пользователя обратно с ошибками
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Создаем возврат с использованием сервиса заказов
            $return = $this->orderService->createReturn(auth()->id(), $request->all());
        } catch (\Exception $e) {
            // В случае ошибки возвращаем пользователя обратно с сообщением об ошибке
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }

        // Перенаправляем на страницу подтверждения возврата с сообщением об успешной отправке
        return redirect()->route('orders.returns.confirmation', $return->id)
            ->with('success', 'Ваша заявка на возврат успешно отправлена. Мы свяжемся с вами в ближайшее время.');
    }

    /**
     * Отображает страницу подтверждения возврата.
     *
     * @param int $id ID возврата
     * @return \Illuminate\View\View Представление с деталями возврата.
     */
    public function confirmation($id)
    {
        // Получаем данные возврата для пользователя
        $return = $this->orderService->getReturnForUser($id, auth()->id());

        // Возвращаем представление с переданными данными
        return view('returns.confirmation', compact('return'));
    }

    /**
     * Обрабатывает отмену заказа пользователя.
     *
     * @param Request $request HTTP-запрос с ID заказа и причиной отмены.
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse JSON-ответ (для AJAX) или редирект.
     */
    public function cancel(Request $request)
    {
        // Валидация входных данных
        $request->validate([
            'order_id' => 'required|exists:orders,id', // ID заказа должен существовать
            'cancellation_reason' => 'required|string|max:1000', // Причина отмены обязательна и ограничена 1000 символами
        ]);

        try {
            // Вызываем сервис для отмены заказа
            $this->orderService->cancelOrder(auth()->id(), $request->order_id, $request->cancellation_reason);

            // Если запрос был AJAX, возвращаем JSON-ответ
            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }

            // Перенаправляем пользователя на страницу заказов с сообщением об успехе
            return redirect()->route('profile.orders')
                ->with('success', 'Заказ успешно отменен.')
                ->with('cancellation_success', true);
        } catch (\Exception $e) {
            // Если запрос AJAX, возвращаем JSON-ответ с ошибкой
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Ошибка при отмене заказа.'], 500);
            }

            // Перенаправляем пользователя обратно с сообщением об ошибке
            return redirect()->back()
                ->with('error', 'Произошла ошибка при отмене заказа. Пожалуйста, попробуйте еще раз.');
        }
    }

    /**
     * Возвращает список книг, входящих в указанный заказ.
     *
     * @param int $orderId ID заказа
     * @return \Illuminate\Http\JsonResponse JSON-ответ со списком книг.
     */
    public function getOrderBooks($orderId)
    {
        // Получаем книги, входящие в заказ пользователя
        $books = $this->orderService->getOrderBooks($orderId, auth()->id());

        // Возвращаем данные в формате JSON
        return response()->json($books);
    }
}
