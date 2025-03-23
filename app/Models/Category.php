<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Category
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|BookCategory[] $book_categories
 *
 * @package App\Models
 */
class Category extends Model
{
	protected $table = 'categories';

	// protected $fillable = [
	// 	'name',
	// 	'description'
	// ];

	public function books() : BelongsToMany
	{
		return $this->belongsToMany(Book::class, 'books_categories');
	}
}
