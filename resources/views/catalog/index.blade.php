<x-layout>
    <div class="container my-4">
        <h1 class="mb-4">{{__('Book catalog')}}</h1>
        <div class="row">
            @foreach($books as $book)
                <x-book-card
                    :id="$book->id"
                    :title="$book->title"
                    :author="$book->author"
                    :price="$book->price"
                    :imagePath="$book->image_path"
                />
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $books->links('pagination::bootstrap-5') }} {{-- Laravel pagination --}}
        </div>
    </div>
</x-layout>
