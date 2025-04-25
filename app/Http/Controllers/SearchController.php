<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class SearchController extends Controller
{
	protected SearchService $searchService;

	/**
	 * Конструктор контроллера.
	 *
	 * @param SearchService $searchService Сервис поиска книг
	 */
	public function __construct(SearchService $searchService)
	{
		$this->searchService = $searchService;
	}

	/**
	 * Выполняет поиск книг по названию или описанию.
	 *
	 * @param Request $request Запрос с параметрами поиска
	 * @return View Представление с результатами поиска
	 */
	public function results(Request $request): View|RedirectResponse
	{
		// Валидация поискового запроса
		$validator = Validator::make($request->all(), [
			'query' => 'nullable|string|max:50',
		], [
			'query.max' => 'Поисковый запрос не должен превышать 50 символов.',
		]);

		// Поисковый запрос
		$query = $validator->fails() ? "" : $request->input('query');
		// Категория (если указана)
		$categorySlug = $request->input('category');
		// Тип сортировки (по умолчанию 'default')
		$sort = $request->input('sort', 'default');

		// Получаем список книг, соответствующих запросу
		[$books, $category] = $this->searchService->searchBooks($query, $sort, $categorySlug);

		return view('search.results', compact('books', 'category'))->withErrors($validator);;
	}
}
