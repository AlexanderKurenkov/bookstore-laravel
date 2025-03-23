<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    protected $table = 'deliveries';

    // protected $fillable = [
    //     'order_id',
    //     'courier',
    //     'tracking_number',
    //     'delivery_status',
    //     'shipped_at',
    //     'expected_delivery',
    //     'delivered_at'
    // ];

    // Relationship: Delivery belongs to an Order
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
