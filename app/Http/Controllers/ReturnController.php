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

    public function getOrderBooks($orderId)
    {
        // Verify the order belongs to the authenticated user and is delivered
        $order = Order::where('id', $orderId)
            ->where('user_id', auth()->id())
            ->where('order_status', 'delivered')
            ->firstOrFail();

        // Get books from this order via pivot table (orders_books)
        $books = $order->books()
            ->withPivot('quantity', 'price') // Ensure we get pivot data
            ->get()
            ->map(function ($book) use ($orderId) {
                // Calculate the quantity already returned
                $returnedQuantity = OrderReturn::where('order_id', $orderId)
                    ->where('book_id', $book->id)
                    ->whereIn('return_status', ['approved', 'processed']) // Only valid returns
                    ->sum('return_quantity');

                // Get ordered quantity from pivot table
                $orderedQuantity = $book->pivot->quantity;

                // Calculate remaining returnable quantity
                $remainingQuantity = $orderedQuantity - $returnedQuantity;

                if ($remainingQuantity <= 0) {
                    return null; // Skip books that can't be returned
                }

                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'quantity' => $remainingQuantity
                ];
            })
            ->filter()
            ->values();

        return response()->json($books);
    }

    public function store(Request $request)
    {
        // Validate the request
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

        // Verify the order belongs to the authenticated user and is delivered
        $order = Order::where('id', $request->order_id)
            ->where('user_id', auth()->id())
            ->where('order_status', 'delivered')
            ->firstOrFail();

        // Verify the book is part of this order and can be returned
        $orderItem = $order->books()
            ->where('books.id', $request->book_id)
            ->firstOrFail();

        // Get ordered quantity from the pivot table
        $orderedQuantity = $orderItem->pivot->quantity;

        // Check how many copies have already been returned
        $returnedQuantity = OrderReturn::where('order_id', $order->id)
            ->where('book_id', $request->book_id)
            ->whereIn('return_status', ['approved', 'processed']) // Only valid returns
            ->sum('return_quantity');

        // Calculate remaining quantity available for return
        $remainingQuantity = $orderedQuantity - $returnedQuantity;

        if ($request->return_quantity > $remainingQuantity) {
            return redirect()->back()
                ->with('error', "Вы можете вернуть максимум {$remainingQuantity} экземпляров этой книги.")
                ->withInput();
        }

        // Format the return reason
        $reasonMap = [
            'damaged' => 'Товар поврежден',
            'wrong_item' => 'Получен не тот товар',
            'quality_issue' => 'Проблема с качеством',
            'not_as_described' => 'Не соответствует описанию',
            'changed_mind' => 'Передумал(а)'
        ];

        $returnReason = $reasonMap[$request->return_reason_type] ?? $request->return_reason;

        // Create the return record
        $return = OrderReturn::create([
            'order_id' => $request->order_id,
            'book_id' => $request->book_id,
            'return_quantity' => $request->return_quantity,
            'return_reason' => $returnReason,
            'return_status' => 'pending'
        ]);

        // Ограничение по функционалу: возврат на заказ можно оформить только 1 раз
        // ->
        // Check if all books in the order have been fully returned
        // $allBooksReturned = $order->books()->get()->every(function ($book) use ($order) {
        //     $orderedQuantity = $book->pivot->quantity;
        //     $returnedQuantity = OrderReturn::where('order_id', $order->id)
        //         ->where('book_id', $book->id)
        //         ->whereIn('return_status', ['approved', 'processed'])
        //         ->sum('return_quantity');

        //     return $orderedQuantity <= $returnedQuantity; // Check if all copies of the book were returned
        // });

        // If all books are returned, update the order status to 'returned'
        // if ($allBooksReturned) {
        //     $order->update(['order_status' => 'returned']);
        // }
        // <-

        $order->update(['order_status' => 'returned']);

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
