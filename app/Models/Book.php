<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Модель книги.
 *
 * @property int $id Идентификатор книги
 * @property string $title Название книги
 * @property string $author Автор книги
 * @property string $publisher Издательство
 * @property int $publication_year Год издания
 * @property float $price Цена книги
 * @property int $quantity_in_stock Количество на складе
 * @property string|null $description Описание книги
 * @property Carbon|null $created_at Дата создания записи
 * @property Carbon|null $updated_at Дата последнего обновления
 *
 * @property Collection|BookCategory[] $book_categories Категории книги
 * @property Collection|Review[] $reviews Отзывы на книгу
 * @property Collection|Order[] $orders Заказы, в которых присутствует книга
 */
class Book extends Model
{
    /** @var string Название таблицы в базе данных */
    protected $table = 'books';

    /**
     * Автоматическое преобразование типов полей модели.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'publication_year' => 'integer',  // Год публикации
        'price' => 'float',               // Цена книги
        'quantity_in_stock' => 'integer', // Количество на складе
        'circulation' => 'integer',       // Тираж
        'pages' => 'integer',             // Количество страниц
        'weight' => 'float',              // Вес книги
    ];

    /**
     * Поля, доступные для массового заполнения.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',             // Название книги
        'author',            // Автор книги
        'publisher',         // Издательство
        'image_path',        // Путь к изображению обложки
        'sample_page_images',// Примеры страниц
        'publication_year',  // Год издания
        'price',             // Цена
        'quantity_in_stock', // Количество в наличии
        'description',       // Описание книги
        'binding_type',      // Тип переплёта
        'publication_type',  // Тип издания
        'isbn',              // ISBN книги
        'edition',           // Издание
        'circulation',       // Тираж
        'language',          // Язык книги
        'pages',             // Количество страниц
        'weight',            // Вес книги
        'size',              // Размер книги
    ];

    /**
     * Получает отзывы о книге.
     *
     * @return HasMany Связь один ко многим с моделью Review
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Получает категории, к которым относится книга.
     *
     * @return BelongsToMany Связь многие ко многим с моделью Category
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'books_categories');
    }

    /**
     * Получает заказы, в которых присутствует книга.
     *
     * @return BelongsToMany Связь многие ко многим с моделью Order
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'orders_books')
            ->withPivot('quantity', 'price', 'created_at', 'updated_at');
    }

    /**
     * Аксессор для поля sample_page_images.
     * Преобразует строку изображений в массив.
     *
     * @param string|null $value Строка изображений, разделённых запятыми
     * @return array<int, string> Массив путей к изображениям
     */
    public function getSamplePageImagesAttribute($value): array
    {
        return $value ? explode(',', trim($value, '{}')) : [];
    }
}
