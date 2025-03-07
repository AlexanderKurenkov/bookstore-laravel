<?php

namespace App\Services;

use App\Models\Book;
use App\Models\User;

class SearchService
{
    public function searchBooks($query) : mixed
    {
        return Book::where('title', 'ILIKE', "%$query%")
            ->orWhere('description', 'ILIKE', "%$query%")
            ->get();
    }

    public function prepareViewData(User $user, $bookList) : array
    {
        $viewData = [];

        if ($user) {
            $viewData['user'] = $user;
        }

        if ($bookList->isEmpty()) {
            $viewData['emptyList'] = true;
        } else {
            $viewData['bookList'] = $bookList;
        }

        return $viewData;
    }
}
