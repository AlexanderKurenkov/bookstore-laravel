<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Services\ReviewService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
	/**
	 * Сервис для работы с отзывами
	 *
	 * @var ReviewService
	 */
	protected ReviewService $reviewService;

	/**
	 * Конструктор контроллера
	 *
	 * @param ReviewService $reviewService Сервис отзывов
	 */
	public function __construct(ReviewService $reviewService)
	{
		$this->reviewService = $reviewService;
	}

	/**
	 * Сохраняет новый отзыв для книги
	 *
	 * @param Request $request HTTP-запрос с данными отзыва
	 * @param int $id ID книги
	 * @return RedirectResponse Ответ с перенаправлением назад
	 */
	public function store(Request $request, $id): RedirectResponse
	{
		// Валидируем входные данные
		$validated = $request->validate([
			'rating' => 'required|integer|min:1|max:5', // Оценка от 1 до 5
			'comment' => 'required|string|max:1000', // Обязательный текст отзыва (до 1000 символов)
		]);

		// Проверяем, существует ли книга
		$book = Book::findOrFail($id);

		// Проверка: пользователь уже оставлял отзыв для этой книги?
		if (Review::where('user_id', Auth::id())->where('book_id', $book->id)->exists()) {
			return back()->with('error', 'Вы уже оставили отзыв для этой книги.');
		}

		// Создаём новый отзыв
		Review::create([
			'user_id' => Auth::id(), // ID авторизованного пользователя
			'book_id' => $book->id, // ID книги
			'rating' => $validated['rating'], // Оценка
			'review_comment' => $validated['comment'], // Комментарий
		]);

		// Возвращаем пользователя назад с сообщением об успехе
		return back()->with('success', 'Ваш отзыв успешно добавлен.');
	}
}
