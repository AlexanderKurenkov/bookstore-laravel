<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

use App\Models\Book;
use App\Models\Category;

class SearchService
{
    /**
     * Выполняет поиск книг по заданным параметрам.
     *
     * @param string|null $query Поисковый запрос
     * @param string $sort Тип сортировки
     * @param string|null $categorySlug Слаг категории
     * @return array Возвращает массив [пагинированный список книг, информация о категории]
     */
    public function searchBooks($query, $sort, $categorySlug): array
    {
        // Начинаем формирование запроса
        $booksQuery = Book::query();

        $category = null;
        // Получаем информацию о категории, если она указана
        if ($categorySlug) {
            $category = Category::where('url_slug', $categorySlug)->first(); // first() вернёт null, если категория не найдена
            if ($category) {
                $booksQuery->whereHas('categories', fn(Builder $q) => $q->where('categories.id', $category->id));
            }
        }

        // Применяем фильтр поиска
        if ($query) {
$booksQuery->where(function (Builder $q) use ($query) {
    $q->where('title', 'ILIKE', "%{$query}%")
        ->orWhere('author', 'ILIKE', "%{$query}%")
        ->orWhere('description', 'ILIKE', "%{$query}%")
        ->orWhere('publisher', 'ILIKE', "%{$query}%");
});
        }

        // Применяем сортировку
        switch ($sort) {
            case 'price_asc':
                $booksQuery->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $booksQuery->orderBy('price', 'desc');
                break;
            case 'date_added':
                $booksQuery->orderBy('created_at', 'desc');
                break;
            case 'title':
                $booksQuery->orderBy('title', 'asc');
                break;
            case 'rating':
                $booksQuery->leftJoin('reviews', 'books.id', '=', 'reviews.book_id')
                    ->selectRaw('books.*, COALESCE(AVG(reviews.rating), 0) as avg_rating')
                    ->groupBy('books.id')
                    ->orderByRaw('avg_rating DESC, books.created_at DESC');
                break;
            default:
                // Сортировка по умолчанию: по релевантности (если есть запрос) или по дате добавления
                if ($query) {
                    // Простая реализация сортировки по релевантности
                    $booksQuery->orderByRaw(
                        "
                        CASE
                            WHEN title ILIKE ? THEN 1
                            WHEN author ILIKE ? THEN 2
                            WHEN publisher ILIKE ? THEN 3
                            WHEN description ILIKE ? THEN 4
                            ELSE 5
                        END",
                        ["%{$query}%", "%{$query}%", "%{$query}%", "%{$query}%"]
                    );
                } else {
                    $booksQuery->orderBy('created_at', 'desc');
                }
        }

        // Получаем пагинированный результат
        return [$booksQuery->paginate(8)->withQueryString(), $category];
    }
}
