<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderCancellation extends Model
{
    protected $table = 'order_cancellations';

    // protected $fillable = [
    //     'order_id',
    //     'cancellation_reason',
    //     'cancelled_at',
    //     'refunded_amount'
    // ];

    // Relationship: OrderCancellation belongs to an Order
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
