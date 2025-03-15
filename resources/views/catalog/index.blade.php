<x-layout>
    <div class="container my-4">
        <h1 class="mb-4">Book Catalog</h1>
        <div class="row">
            @foreach($books as $book)
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <img src="{{ $book->cover_image }}" class="card-img-top" alt="{{ $book->title }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $book->title }}</h5>
                            <p class="card-text text-muted">{{ $book->author }}</p>
                            <p class="fw-bold">${{ number_format($book->price, 2) }}</p>
                            <a href="{{ route('catalog.show', $book->id) }}" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $books->links() }} {{-- Laravel pagination --}}
        </div>
    </div>
</x-layout>