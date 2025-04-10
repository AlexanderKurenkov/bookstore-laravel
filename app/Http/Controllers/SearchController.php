<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\Request;
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
	public function results(Request $request): View
	{
		// Поисковый запрос
		$query = $request->input('query');
		// Категория (если указана)
		$categorySlug = $request->input('category');
		// Тип сортировки (по умолчанию 'default')
		$sort = $request->input('sort', 'default');

		// Получаем список книг, соответствующих запросу
		[$books, $category] = $this->searchService->searchBooks($query, $sort, $categorySlug);

		return view('search.results', compact('books', 'category'));
	}
}
