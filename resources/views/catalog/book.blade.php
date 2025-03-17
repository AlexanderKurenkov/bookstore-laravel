<x-layout>
    <div class="container my-5">
        <div class="row">
            <!-- Book Image Column -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="{{ $book->image_path ?? asset('images/books/2.webp') }}"
                         class="card-img-top img-fluid"
                         alt="{{ $book->title ?? 'Book Cover' }}">
                </div>
            </div>

            <!-- Book Details Column -->
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-body">
                        <h1 class="card-title mb-4">{{ $book->title }}</h1>

                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Название:</div>
                            <div class="col-md-8">{{ $book->title }}</div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Автор:</div>
                            <div class="col-md-8">{{ $book->author }}</div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Издательство:</div>
                            <div class="col-md-8">{{ $book->publisher }}</div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Год издания:</div>
                            <div class="col-md-8">{{ $book->publication_year }}</div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 fw-bold">Цена:</div>
                            <div class="col-md-8">
                                <span class="fs-4 text-primary">{{ number_format($book->price, 2) }} ₽</span>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-start mb-4">
                            <button class="btn btn-primary" type="button">
                                <i class="bi bi-cart-plus"></i> Добавить в корзину
                            </button>
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="bi bi-heart"></i> В избранное
                            </button>
                        </div>

                        <div class="row mb-2">
                            <div class="col-12 fw-bold fs-5 mb-2">Описание:</div>
                            <div class="col-12">
                                <p>{{ $book->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="row mt-5">
            <div class="col-12">
                <h2 class="mb-4">Отзывы</h2>

                @if($book->reviews->count() > 0)
                    @foreach($book->reviews as $review)
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <h5 class="card-title">{{ $review->user->name }}</h5>
                                    <div>
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i class="bi bi-star-fill text-warning"></i>
                                            @else
                                                <i class="bi bi-star text-warning"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <h6 class="card-subtitle mb-2 text-muted">{{ $review->created_at->format('d.m.Y') }}</h6>
                                <p class="card-text">{{ $review->review_comment }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="alert alert-info">
                        У этой книги пока нет отзывов. Будьте первым, кто оставит отзыв!
                    </div>
                @endif

                <!-- Add Review Button -->
                <div class="mt-4">
                    <a href="/" class="btn btn-outline-primary">
                    {{-- TODO --}}
                    {{-- <a href="{{ route('books.review.create', $book->id) }}" class="btn btn-outline-primary"> --}}
                        <i class="bi bi-pencil"></i> Написать отзыв
                    </a>
                </div>
            </div>
        </div>
    </div>
    </x-layout>
