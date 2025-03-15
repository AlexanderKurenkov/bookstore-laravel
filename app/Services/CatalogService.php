<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Database\Eloquent\Model;

class CatalogService
{
    public function getAllBooks()
    {
        return Book::query();
    }

    public function getBookById($id) : Model
    {
        return Book::findOrFail($id);
    }
}
