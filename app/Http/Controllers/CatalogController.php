<?php

namespace App\Http\Controllers;

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

	public function show($id): View
	{
		// TODO not only authenticated user can view the catalog - maybe no need to pass user to the view at all?
		// ?
		// $user = Auth::user(); // Get the currently authenticated user
		$book = $this->catalogService->getBookById($id); // Find the book by ID or fail with 404
		$qtyList = range(1, 10); // Generate a list of quantities

		return view('catalog.book', [
			// 'user' => $user,         // Pass the user data to the view
			'book' => $book,         // Pass the book details to the view
			'qtyList' => $qtyList,   // Pass the quantity list to the view
			'qty' => 1               // Default quantity
		]);
	}
}
