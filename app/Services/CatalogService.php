<?php

namespace App\Services;

use App\Models\Book;

class CatalogService
{
    public function getAllBooks()
    {
        return Book::all();
    }

    public function getBookById($id)
    {
        return Book::findOrFail($id);
    }

    public function getQtyList()
    {
        return range(1, 10);
    }
}
