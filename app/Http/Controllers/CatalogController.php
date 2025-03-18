<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\CatalogService;
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

	public function showCategory(string $name): View
	{
		$books = Book::whereHas('categories', function ($query) use ($name) {
			$query->where('name', $name);
		})->paginate(10);

		return view('catalog.index', [
			'books' => $books,
			'categoryName' => ucfirst($name), // Format name if needed
		]);
	}

	public function showBook(int $id): View
	{
		// TODO not only authenticated user can view the catalog - maybe no need to pass user to the view at all?
		// ?
		$book = $this->catalogService->getBookById($id); // Find the book by ID or fail with 404
		// $qtyList = range(1, 10); // Generate a list of quantities

		return view('catalog.book', compact('book'));
	}
}
