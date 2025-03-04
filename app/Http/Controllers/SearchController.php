<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SearchController extends Controller
{
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

		$viewData = [];

		if ($user) {
			$viewData['user'] = $user;
		}

		$bookList = Book::where('title', 'ILIKE', "%$query%")
			->orWhere('description', 'ILIKE', "%$query%")
			->get();


		if ($bookList->isEmpty()) {
			$viewData['emptyList'] = true;
			return view('catalog.index', $viewData);
		}

		$viewData['bookList'] = $bookList;

		return view('catalog.index', $viewData);
	}
}
