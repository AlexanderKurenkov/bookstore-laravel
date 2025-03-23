<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Payment
 *
 * @property int $id
 * @property float|null $amount
 * @property string|null $transaction_id
 * @property string|null $payment_status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $order_id
 *
 * @property Order|null $order
 *
 * @package App\Models
 */
class Payment extends Model
{
	protected $table = 'payments';

	protected $casts = [
		'amount' => 'float',
		'order_id' => 'int',
		'payment_method' => 'string'
	];

	// protected $fillable = [
	// 	'amount',
	// 	'transaction_id',
	// 	'payment_status',
	// 	'order_id'
	// ];

	public function order(): BelongsTo
	{
		return $this->belongsTo(Order::class);
	}
}
