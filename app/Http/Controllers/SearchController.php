<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
		$query = $request->input('query'); // Get search term
		$user = Auth::user();

		// Get the list of books based on the query
		$bookList = $this->searchService->searchBooks($query);

		// Prepare the view data
		$viewData = $this->searchService->prepareViewData($user, $bookList);

		// Return the appropriate view with the data
		return view('catalog.index', $viewData);
	}
}
