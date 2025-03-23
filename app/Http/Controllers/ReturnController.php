<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderReturn;
use App\Mail\ReturnRequestSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ReturnController extends Controller
{
    /**
     * Display the return form.
     */
    public function edit($id)
    {
        // TODO why $id param not used below?

        // Get all delivered orders for the authenticated user
        $deliveredOrders = Order::where('user_id', auth()->id())
            ->where('order_status', 'delivered')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('returns.edit', compact('deliveredOrders'));
    }

    /**
     * Get books from an order for the return form (AJAX endpoint).
     */
    public function getOrderBooks($orderId)
    {
        // Verify the order belongs to the authenticated user
        $order = Order::where('id', $orderId)
            ->where('user_id', auth()->id())
            ->where('order_status', 'delivered')
            ->firstOrFail();

        // Get books from this order that can be returned
        // This would need to be adjusted based on your actual database structure
        // and how you track order items and previous returns
        $books = $order->items()
            ->with('book')
            ->get()
            ->map(function ($item) {
                // Calculate remaining quantity that can be returned
                // (original quantity minus already returned quantity)
                $returnedQuantity = OrderReturn::where('order_id', $item->order_id)
                    ->where('book_id', $item->book_id)
                    ->where('return_status', '!=', 'rejected')
                    ->sum('return_quantity');

                $remainingQuantity = $item->quantity - $returnedQuantity;

                if ($remainingQuantity <= 0) {
                    return null;
                }

                return [
                    'id' => $item->book_id,
                    'title' => $item->book->title,
                    'quantity' => $remainingQuantity
                ];
            })
            ->filter()
            ->values();

        return response()->json($books);
    }

    /**
     * Store a new return request.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'book_id' => 'required|exists:books,id',
            'return_quantity' => 'required|integer|min:1',
            'return_reason_type' => 'required|string',
            'return_reason' => 'required_if:return_reason_type,other|string|nullable',
            'agree_terms' => 'required|accepted',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify the order belongs to the authenticated user and is delivered
        $order = Order::where('id', $request->order_id)
            ->where('user_id', auth()->id())
            ->where('order_status', 'delivered')
            ->firstOrFail();

        // Verify the book is part of this order and can be returned
        // This would need to be adjusted based on your actual database structure
        $orderItem = $order->items()
            ->where('book_id', $request->book_id)
            ->firstOrFail();

        // Check if the requested return quantity is valid
        $returnedQuantity = OrderReturn::where('order_id', $order->id)
            ->where('book_id', $request->book_id)
            ->where('return_status', '!=', 'rejected')
            ->sum('return_quantity');

        $remainingQuantity = $orderItem->quantity - $returnedQuantity;

        if ($request->return_quantity > $remainingQuantity) {
            return redirect()->back()
                ->with('error', "Вы можете вернуть максимум {$remainingQuantity} экземпляров этой книги.")
                ->withInput();
        }

        // Format the return reason
        $returnReason = $request->return_reason_type;
        if ($request->return_reason_type === 'other') {
            $returnReason = $request->return_reason;
        } else {
            // Map reason type to human-readable text
            $reasonMap = [
                'damaged' => 'Товар поврежден',
                'wrong_item' => 'Получен не тот товар',
                'quality_issue' => 'Проблема с качеством',
                'not_as_described' => 'Не соответствует описанию',
                'changed_mind' => 'Передумал(а)'
            ];
            $returnReason = $reasonMap[$request->return_reason_type] ?? $request->return_reason_type;
        }

        // Create the return record
        $return = OrderReturn::create([
            'order_id' => $request->order_id,
            'book_id' => $request->book_id,
            'return_quantity' => $request->return_quantity,
            'return_reason' => $returnReason,
            'return_status' => 'pending'
        ]);

        // Send email notification
        try {
            Mail::to('returns@' . config('app.domain'))
                ->send(new ReturnRequestSubmitted($return));
        } catch (\Exception $e) {
            // Log the error but don't fail the request
            \Log::error('Failed to send return request email: ' . $e->getMessage());
        }

        // Redirect with success message
        return redirect()->route('returns.confirmation', $return->id)
            ->with('success', 'Ваша заявка на возврат успешно отправлена. Мы свяжемся с вами в ближайшее время.');
    }

    /**
     * Display return confirmation page.
     */
    public function confirmation($id)
    {
        $return = OrderReturn::where('id', $id)
            ->whereHas('order', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->firstOrFail();

        return view('returns.confirmation', compact('return'));
    }
}
