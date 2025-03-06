<?php

namespace App\Services;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ReviewService
{
    public function getAllReviews(): Collection
    {
        return Review::latest()->get();
    }

    public function createReview(User $user, array $data): void
    {
        $user->reviews()->create([
            'book_id' => $data['book_id'],
            'rating' => $data['rating'],
            'comment' => $data['comment'],
        ]);
    }

    public function updateReview(User $user, int $id, array $data): void
    {
        $review = $user->reviews()->findOrFail($id);
        $review->update([
            'rating' => $data['rating'],
            'comment' => $data['comment'],
        ]);
    }

    public function deleteReview(User $user, int $id): void
    {
        $review = $user->reviews()->findOrFail($id);
        $review->delete();
    }
}
