<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Review
 *
 * @property int $id
 * @property int|null $rating
 * @property string|null $review_comment
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $book_id
 * @property int|null $user_id
 *
 * @property Book|null $book
 * @property User|null $user
 *
 * @package App\Models
 */
class Review extends Model
{
	protected $table = 'reviews';

	protected $casts = [
		'rating' => 'int',
		'book_id' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'rating',
		'review_comment',
		'book_id',
		'user_id'
	];

	public function book(): BelongsTo
	{
		return $this->belongsTo(Book::class);
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}
