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
        'publication_year' => 'integer',
        'price' => 'float',
        'quantity_in_stock' => 'integer',
        'circulation' => 'integer',
        'pages' => 'integer',
        'weight' => 'float',
        'sample_page_images' => 'array', // Since PostgreSQL uses TEXT[], storing it as JSON
    ];

	// protected $fillable = [
    //     'title',
    //     'author',
    //     'publisher',
    //     'image_path',
    //     'sample_page_images',
    //     'publication_year',
    //     'price',
    //     'quantity_in_stock',
    //     'description',
    //     'binding_type',
    //     'publication_type',
    //     'isbn',
    //     'edition',
    //     'circulation',
    //     'language',
    //     'pages',
    //     'weight',
    //     'size',
    // ];

	public function reviews(): HasMany
	{
		return $this->hasMany(Review::class);
	}

	public function categories(): BelongsToMany
	{
		return $this->belongsToMany(Category::class, 'books_categories');
	}

	public function orders(): BelongsToMany
	{
		return $this->belongsToMany(Order::class, 'orders_books')
			->withPivot('quantity', 'price', 'created_at', 'updated_at');
	}
}
