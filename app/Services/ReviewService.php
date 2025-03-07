<?php

namespace App\Services;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ReviewService
{
    public function getAllReviews(): Collection
    {
        return Review::latest()->get()->paginate(10); // latest() is a shortcut for orderBy('created_at', 'desc')
    }

    public function createReview(User $user, array $data) : Model
    {
        return $user->reviews()->create([
            'book_id' => $data['book_id'],
            'rating' => $data['rating'],
            'comment' => $data['comment'],
        ]);
    }

    public function updateReview(User $user, int $id, array $data): bool
    {
        $review = $user->reviews()->findOrFail($id);

        return $review->update([
            'rating' => $data['rating'],
            'comment' => $data['comment'],
        ]);
    }

    public function deleteReview(User $user, int $id): bool
    {
        $review = $user->reviews()->findOrFail($id);
        // Using type casting because Model::delete() returns bool|null.
        return (bool)$review->delete();
    }
}
