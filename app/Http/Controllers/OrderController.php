<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Display the return form.
     */
    public function edit($id)
    {
        $deliveredOrders = $this->orderService->getDeliveredOrdersForUser(auth()->id());

        return view('returns.edit', compact('deliveredOrders'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'book_id' => 'required|exists:books,id',
            'return_quantity' => 'required|integer|min:1',
            'return_reason_type' => 'required|string',
            'return_reason' => 'nullable|required_if:return_reason_type,other|string',
            'agree_terms' => 'required|accepted',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $return = $this->orderService->createReturn(auth()->id(), $request->all());
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }

        return redirect()->route('orders.returns.confirmation', $return->id)
            ->with('success', 'Ваша заявка на возврат успешно отправлена. Мы свяжемся с вами в ближайшее время.');
    }

    /**
     * Display return confirmation page.
     */
    public function confirmation($id)
    {
        $return = $this->orderService->getReturnForUser($id, auth()->id());

        return view('returns.confirmation', compact('return'));
    }


    /**
     * Cancel an order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function cancel(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'cancellation_reason' => 'required|string|max:1000',
        ]);

        try {
            $this->orderService->cancelOrder(auth()->id(), $request->order_id, $request->cancellation_reason);

            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }

            return redirect()->route('profile.orders')
                ->with('success', 'Заказ успешно отменен.')
                ->with('cancellation_success', true);
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Ошибка при отмене заказа.'], 500);
            }

            return redirect()->back()
                ->with('error', 'Произошла ошибка при отмене заказа. Пожалуйста, попробуйте еще раз.');
        }
    }

    public function getOrderBooks($orderId)
    {
        $books = $this->orderService->getOrderBooks($orderId, auth()->id());

        return response()->json($books);
    }
}
