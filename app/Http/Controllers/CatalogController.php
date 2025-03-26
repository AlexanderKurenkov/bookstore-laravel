<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Services\CatalogService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class CatalogController extends Controller
{
	protected CatalogService $catalogService;

	public function __construct(CatalogService $catalogService)
	{
		$this->catalogService = $catalogService;
	}

	public function index(): View
	{
		// TODO not only authenticated user can view the catalog - maybe no need to pass user to the view at all?
		// ?
		// $user = Auth::user(); // Get the currently authenticated user
		$books = $this->catalogService->getAllBooks()->paginate(8); // Fetch all books & paginate

		return view('catalog.index', compact('books'));
	}

	public function showCategory(string $url_slug): View
	{
		// $data = $this->catalogService->getBooksByCategory($url_slug);

		// return view('catalog.index', $data);

		$sort = request('sort', 'default');

		$data = $this->catalogService->getBooksByCategory($url_slug, $sort);

		return view('catalog.index', $data);
	}

	public function showBook(int $id): View
	{
		// TODO not only authenticated user can view the catalog - maybe no need to pass user to the view at all?
		// ?
		$book = $this->catalogService->getBookById($id); // Find the book by ID or fail with 404

		return view('catalog.book', compact('book'));
	}

	public function listFavorites()
	{
		$favorites = auth()->user()->favorites->map(function ($favorite) {
			return [
				'id' => $favorite->id,
				'title' => $favorite->title,
				'author' => $favorite->author,
				'price' => number_format($favorite->price, 2),
				'image' => $favorite->image_path ?? '/placeholder.svg?height=120&width=80',
			];
		});

		return response()->json([
			'count' => $favorites->count(),
			'favorites' => $favorites,
		]);
	}

	public function toggleFavorites(Request $request)
	{
		// Ensure the user is authenticated
		if (!auth()->check()) {
			return response()->json(['error' => 'Unauthenticated'], 401);
		}

		$user = auth()->user();
		$bookId = $request->input('bookId');

		// Find the book by its ID
		$book = Book::find($bookId);

		if (!$book) {
			return response()->json(['error' => 'Book not found'], 404);
		}

		// Toggle the favorite status
		if ($user->favorites->contains($bookId)) {
			// If the book is already in the user's favorites, remove it
			$user->favorites()->detach($bookId);
			$isFavorite = false;
		} else {
			// If the book is not in the user's favorites, add it
			$user->favorites()->attach($bookId);
			$isFavorite = true;
		}

		// Optionally, update the favorites count for the user in the navbar
		$favoritesCount = $user->favorites()->count();

		return response()->json([
			'isFavorite' => $isFavorite,
			'favoritesCount' => $favoritesCount
		]);
	}
}
