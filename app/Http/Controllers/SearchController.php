<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
	/**
	 * Search books by category.
	 */
	public function searchByCategory(Request $request): \Illuminate\View\View
	{
		$category = $request->query('category');
		$user = Auth::user();

		$viewData = [];

		if ($user) {
			$viewData['user'] = $user;
		}

		$classActiveCategory = 'active' . preg_replace('/[&\s]+/', '', $category);
		$viewData[$classActiveCategory] = true;

		$bookList = Book::where('category', $category)->get();

		if ($bookList->isEmpty()) {
			$viewData['emptyList'] = true;
			return view('catalog', $viewData);
		}

		$viewData['bookList'] = $bookList;

		return view('catalog', $viewData);
	}

	/**
	 * Search books by keyword.
	 */
	public function searchByTitle(Request $request): \Illuminate\View\View
	{
		$keyword = $request->input('keyword');
		$user = Auth::user();

		$viewData = [];

		if ($user) {
			$viewData['user'] = $user;
		}

		$bookList = Book::where('title', 'LIKE', "%$keyword%")
			->orWhere('description', 'LIKE', "%$keyword%")
			->get();

		if ($bookList->isEmpty()) {
			$viewData['emptyList'] = true;
			return view('catalog', $viewData);
		}

		$viewData['bookList'] = $bookList;

		return view('catalog', $viewData);
	}
}
