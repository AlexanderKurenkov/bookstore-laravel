<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Сервис для работы с каталогом книг.
 */
class CatalogService
{
    /**
     * Получает запрос (Query Builder) для всех книг.
     *
     * @return Builder Запрос для получения всех книг
     */
    public function getAllBooks(): Builder
    {
        return Book::query();
    }

    /**
     * Получает книгу по её идентификатору.
     *
     * @param int $id Идентификатор книги
     * @return Model Найденная книга
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Если книга не найдена
     */
    public function getBookById(int $id): Model
    {
        return Book::findOrFail($id);
    }

    /**
     * Получает книги из указанной категории с возможностью сортировки.
     *
     * @param string $url_slug URL-идентификатор категории
     * @param string $sort Метод сортировки (по умолчанию — 'default')
     * @return array<string, mixed> Массив с книгами и информацией о категории
     *
     * Возможные значения $sort:
     * - 'price_asc'        Сортировка по цене (возрастание)
     * - 'price_desc'       Сортировка по цене (убывание)
     * - 'date_added'       Сортировка по дате добавления (новые сверху)
     * - 'title'            Сортировка по названию (алфавитный порядок)
     * - 'rating'           Сортировка по среднему рейтингу (сначала лучшие)
     */
    public function getBooksByCategory(string $url_slug, string $sort = 'default'): array
    {
        if ($url_slug === 'all') {
            $category = null; // Нет конкретной категории
            $booksQuery = Book::query(); // Получаем все книги
        } else {
            $category = Category::where('url_slug', $url_slug)->firstOrFail();

            // Получаем книги только из указанной категории
            $booksQuery = Book::whereHas('categories', function ($query) use ($url_slug) {
                $query->where('url_slug', $url_slug);
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
        }

        // Пагинация книг (8 книг на страницу)
        $books = $booksQuery->paginate(8)->withQueryString();

        return [
            'books' => $books,
            // Название категории (или "Все книги" по умолчанию)
            'categoryName' => $category->name ?? __('All books'),
            'categorySlug' => $url_slug
        ];
    }
}
