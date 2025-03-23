<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class CatalogService
{
    public function getAllBooks()
    {
        return Book::query();
    }

    public function getBookById($id): Model
    {
        return Book::findOrFail($id);
    }

    // public function getBooksByCategory(string $url_slug): array
    // {
    //     // Retrieve category by slug
    //     $category = Category::where('url_slug', $url_slug)->firstOrFail();

    //     // Retrieve books associated with this category
    //     $books = Book::whereHas('categories', function ($query) use ($url_slug) {
    //         $query->where('url_slug', $url_slug);
    //     })->paginate(10) ?? collect(); // Ensure it's never null

    //     return [
    //         'books' => $books,
    //         'categoryName' => $category->name
    //     ];
    // }
    public function getBooksByCategory(string $url_slug, string $sort = 'default'): array
    {
        if ($url_slug === 'all') {
            $category = null; // No specific category
            $booksQuery = Book::query(); // Get all books
        } else {
            $category = Category::where('url_slug', $url_slug)->firstOrFail();

            // Get books only from the specified category
            $booksQuery = Book::whereHas('categories', function ($query) use ($url_slug) {
                $query->where('url_slug', $url_slug);
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
        }

        $books = $booksQuery->paginate(8)->withQueryString();

        return [
            'books' => $books,
            'categoryName' => $category->name ?? __('All books'), // Default name for 'all'
            'categorySlug' => $url_slug
        ];
    }
}
