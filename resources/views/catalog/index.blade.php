@props(['categoryName' => __('Book catalog')])
<x-layout>
    <div class="container my-4">
        <h1 class="mb-4">{{ $categoryName }}</h1>
        <div class="row">
            @foreach($books as $book)
                <x-book-card
                    :id="$book->id"
                    :title="$book->title"
                    :author="$book->author"
                    :price="$book->price"
                    :imagePath="$book->image_path"
                    :publisher="$book->publisher"
                    :publication_year="$book->publication_year"
                />
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $books->links('pagination::bootstrap-5') }} {{-- Laravel pagination --}}
        </div>
    </div>

    @push('head')
        <style>
            .hover-shadow:hover {
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
                transform: translateY(-3px);
                transition: all 0.3s ease;
            }

            .book-card {
                transition: all 0.3s ease;
            }

            .book-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            }
        </style>
    @endpush
</x-layout>
