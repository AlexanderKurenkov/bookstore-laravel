<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReviewService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReviewController extends Controller
{
	protected ReviewService $reviewService;

	public function __construct(ReviewService $reviewService)
	{
		$this->reviewService = $reviewService;
	}

	public function index(): View
	{
		$reviews = $this->reviewService->getAllReviews();
		return view('reviews.index', compact('reviews'));
	}

	public function store(Request $request): RedirectResponse
	{
		$this->reviewService->createReview($request->user(), $request->all());
		return redirect()->back()->with('success', 'Review added successfully.');
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
