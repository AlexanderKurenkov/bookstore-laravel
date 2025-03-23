<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryDetail extends Model
{
	protected $table = 'delivery_details';

	// protected $fillable = [
	// 	'user_id',
	// 	'address_line1',
	// 	'address_line2',
	// 	'city',
	// 	'state',
	// 	'postal_code',
	// 	'country',
	// 	'phone',
	// 	'user_comment'
	// ];

	// Relationship: DeliveryDetail belongs to a User
	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}
