<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CatalogService
{
    public function getAllBooks() : Collection
    {
        return Book::all();
    }

    public function getBookById($id) : Model
    {
        return Book::findOrFail($id);
    }
}
