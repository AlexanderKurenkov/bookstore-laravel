<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ReviewService
{
    /**
     * Создаёт новый отзыв к книге от текущего авторизованного пользователя.
     *
     * @param array $validated Валидированные данные отзыва (оценка и комментарий)
     * @param int $bookId ID книги, для которой создаётся отзыв
     *
     * @throws \Illuminate\Validation\ValidationException Если отзыв от этого пользователя для данной книги уже существует
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Если книга с указанным ID не найдена
     *
     * @return void
     */
    public function createReview(array $validated, int $bookId): void
    {
        // Проверка существования книги
        $book = Book::findOrFail($bookId);

        // Проверка: пользователь уже оставлял отзыв
        if (Review::where('user_id', Auth::id())->where('book_id', $book->id)->exists()) {
            throw ValidationException::withMessages([
                'duplicate' => 'Вы уже оставили отзыв для этой книги.',
            ]);
        }

        // Создание отзыва
        Review::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'rating' => $validated['rating'],
            'review_comment' => $validated['comment'],
        ]);
    }

    /**
     * Возвращает среднюю оценку книги по её отзывам.
     *
     * @param int $bookId ID книги, для которой необходимо вычислить среднюю оценку
     *
     * @return float|null Средняя оценка (округлённая до двух знаков после запятой) или null, если отзывов нет либо книга не найдена
     */
    public function getBookRating(int $bookId): ?float
    {
        $book = Book::with('reviews')->find($bookId);

        if (!$book || $book->reviews->isEmpty()) {
            return null; // No reviews, so no rating available
        }

        return round($book->reviews->avg('rating'), 2);
    }
}
