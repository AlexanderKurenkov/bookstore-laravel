<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Services\ReviewService;
use Illuminate\Validation\ValidationException;
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
			'rating' => 'required|integer|min:1|max:5',
			'comment' => 'required|string|max:1000',
		]);

		try {
			$this->reviewService->createReview($validated, $id);
		} catch (ValidationException $e) {
			// Возвращаем пользователя назад с сообщением об ошибке
			return back()->with('error', $e->getMessage());
		}

		// Возвращаем пользователя назад с сообщением об успехе
		return back()->with('success', 'Ваш отзыв успешно добавлен.');
	}
}
