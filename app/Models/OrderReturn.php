<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderReturn extends Model
{
    protected $table = 'order_returns';

    protected $fillable = [
        'order_id',
        'book_id',
        'return_quantity',
        'return_reason',
        'return_status'
    ];

    // Relationship: OrderReturn belongs to an Order
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Relationship: OrderReturn belongs to a Book
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
