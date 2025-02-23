<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Order
 *
 * @property int $id
 * @property string|null $order_status
 * @property float $order_total
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $user_id
 *
 * @property User|null $user
 * @property Collection|Payment[] $payments
 * @property Collection|Book[] $books
 *
 * @package App\Models
 */
class Order extends Model
{
	protected $table = 'orders';

	protected $casts = [
		'order_total' => 'float',
		'user_id' => 'int'
	];

	protected $fillable = [
		'order_status',
		'order_total',
		'user_id'
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function payments(): HasMany
	{
		return $this->hasMany(Payment::class);
	}

	public function books(): BelongsToMany
	{
		return $this->belongsToMany(Book::class, 'order_books')
			->withPivot('quantity', 'price');
	}
}
