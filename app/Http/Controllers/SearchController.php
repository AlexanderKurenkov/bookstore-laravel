<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
	protected SearchService $searchService;

	public function __construct(SearchService $searchService)
	{
		$this->searchService = $searchService;
	}

	// Shows advanced search form.
	public function index(): View
	{
		return view('search.index'); // View for the advanced search form
	}

	/**
	 * Search books by title or description.
	 */
	public function results(Request $request): View
	{
		$query = $request->input('query');
		$categorySlug = $request->input('category');
        $sort = $request->input('sort', 'default');

		// Get the list of books based on the query
		[$books, $category] = $this->searchService->searchBooks($query, $sort, $categorySlug);

        return view('search.results', compact('books', 'category'));
	}
}
