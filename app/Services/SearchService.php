<?php

namespace App\Services;

use App\Models\Book;

class SearchService
{
    public function searchBooks($query)
    {
        return Book::where('title', 'ILIKE', "%$query%")
            ->orWhere('description', 'ILIKE', "%$query%")
            ->get();
    }

    public function prepareViewData(User $user, $bookList)
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
