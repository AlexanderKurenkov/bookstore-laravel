<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Services\ReviewService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReviewController extends Controller
{
	protected ReviewService $reviewService;

	public function __construct(ReviewService $reviewService)
	{
		$this->reviewService = $reviewService;
	}

	public function show($id) //: View
	{
		// $reviews = $this->reviewService->getAllReviews($id);
		// return view('reviews.index', compact('reviews'));

		// $reviews = Book::findOrFail($id)->reviews()->paginate(10);
		// return response()->json($reviews);
		return $reviews;
	}

	// public function store(Request $request): RedirectResponse
	// {
	// 	$this->reviewService->createReview($request->user(), $request->all());
	// 	return redirect()->back()->with('success', 'Review added successfully.');
	// }
	public function store(Request $request, $id)
	{
		// Validate request data
		$validated = $request->validate([
			'rating' => 'required|integer|min:1|max:5',
			'comment' => 'required|string|max:1000',
		]);

		// Ensure the book exists
		$book = Book::findOrFail($id);

		// Prevent duplicate reviews (one user can only review a book once)
		if (Review::where('user_id', Auth::id())->where('book_id', $book->id)->exists()) {
			return back()->with('error', 'Вы уже оставили отзыв для этой книги.');
		}

		// Create a new review
		Review::create([
			'user_id' => Auth::id(), // Get authenticated user ID
			'book_id' => $book->id,
			'rating' => $validated['rating'],
			'review_comment' => $validated['comment'],
		]);

		return back()->with('success', 'Ваш отзыв успешно добавлен.');
	}

	public function update(Request $request, int $id): RedirectResponse
	{
		$this->reviewService->updateReview($request->user(), $id, $request->all());
		return redirect()->back()->with('success', 'Review updated successfully.');
	}

	public function destroy(Request $request, int $id): RedirectResponse
	{
		$this->reviewService->deleteReview($request->user(), $id);
		return redirect()->back()->with('success', 'Review deleted successfully.');
	}
}
