<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Book
 *
 * @property int $id
 * @property string $title
 * @property string $author
 * @property string $publisher
 * @property int $publication_year
 * @property float $price
 * @property int $quantity_in_stock
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|BookCategory[] $book_categories
 * @property Collection|Review[] $reviews
 * @property Collection|Order[] $orders
 *
 * @package App\Models
 */
class Book extends Model
{
	protected $table = 'books';

	protected $casts = [
		'publication_year' => 'int',
		'price' => 'float',
		'quantity_in_stock' => 'int'
	];

	protected $fillable = [
		'title',
		'author',
		'publisher',
		'publication_year',
		'price',
		'quantity_in_stock',
		'description'
	];

	public function reviews(): HasMany
	{
		return $this->hasMany(Review::class);
	}

	public function categories(): BelongsToMany
	{
		return $this->belongsToMany(Category::class, 'book_categories');
	}

	public function orders(): BelongsToMany
	{
		return $this->belongsToMany(Order::class, 'order_books')
			->withPivot('quantity', 'price');
	}
}
