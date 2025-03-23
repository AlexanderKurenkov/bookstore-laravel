<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardPayment extends Model
{
    protected $table = 'card_payments';

    // protected $fillable = [
    //     'payment_id',
    //     'card_type',
    //     'card_last_four',
    //     'card_expiry_month',
    //     'card_expiry_year',
    //     'cardholder_name'
    // ];

    // Relationship: CardPayment belongs to a Payment
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
