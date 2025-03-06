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
		$user = Auth::user(); // Get the currently authenticated user
		$bookList = $this->catalogService->getAllBooks(); // Fetch all books

		return view('catalog.index', [
			'user' => $user,               // Pass the user data to the view
			'bookList' => $bookList,       // Pass the list of books to the view
			'activeAll' => true            // Add additional data for the view
		]);
	}

	public function show($id): View
	{
		$user = Auth::user(); // Get the currently authenticated user
		$book = $this->catalogService->getBookById($id); // Find the book by ID or fail with 404
		$qtyList = range(1, 10); // Generate a list of quantities

		return view('catalog.book', [
			'user' => $user,         // Pass the user data to the view
			'book' => $book,         // Pass the book details to the view
			'qtyList' => $qtyList,   // Pass the quantity list to the view
			'qty' => 1               // Default quantity
		]);
	}
}
