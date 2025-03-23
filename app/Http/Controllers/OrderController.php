<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderCancellation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
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

        // Verify the order belongs to the authenticated user
        $order = Order::where('id', $request->order_id)
            ->where('user_id', auth()->id())
            ->whereIn('order_status', ['pending', 'processing', 'shipped'])
            ->firstOrFail();

        DB::beginTransaction();
        try {
            // Update order status
            $order->order_status = 'cancelled';
            $order->save();

            // Create cancellation record
            $cancellation = new OrderCancellation();
            $cancellation->order_id = $order->id;
            $cancellation->cancellation_reason = $request->cancellation_reason;
            
            // If the order was paid, set the refunded amount
            if ($order->payment_status === 'paid') {
                $cancellation->refunded_amount = $order->order_total;
            }
            
            $cancellation->save();

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }

            return redirect()->route('profile.orders')
                ->with('success', 'Заказ успешно отменен.')
                ->with('cancellation_success', true);
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Ошибка при отмене заказа.'], 500);
            }

            return redirect()->back()
                ->with('error', 'Произошла ошибка при отмене заказа. Пожалуйста, попробуйте еще раз.');
        }
    }
}

