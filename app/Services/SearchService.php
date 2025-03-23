<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

use App\Models\Book;
use App\Models\Category;

class SearchService
{
    public function searchBooks($query, $sort, $categorySlug): mixed
    {
        // Start building the query
        $booksQuery = Book::query();

        $category = null;
        // Get category information if specified
        if ($categorySlug) {
            $category = Category::where('url_slug', $categorySlug)->first(); // first() returns null when no record is found.
            if ($category) {
                $booksQuery->whereHas('categories', fn(Builder $q) => $q->where('categories.id', $category->id));
            }
        }

        // Apply search filter
        if ($query) {
            $booksQuery->where(function (Builder $q) use ($query) {
                $q->where('title', 'ILIKE', "%{$query}%")
                    ->orWhere('author', 'ILIKE', "%{$query}%")
                    ->orWhere('description', 'ILIKE', "%{$query}%")
                    ->orWhere('publisher', 'ILIKE', "%{$query}%");
            });
        }

        // Apply sorting
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
                // Default sorting by relevance (for search) or newest
                if ($query) {
                    // For search results, we might want to order by relevance
                    // This is a simple implementation - in a real app, you might use
                    // more sophisticated relevance scoring
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

        // Get paginated results
        return [$booksQuery->paginate(8)->withQueryString(), $category];
    }
}
