<x-layout>

<div class="container my-5">
    <div class="row">
        <!-- Book Image Column -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="{{ $book->image_path ?? asset('images/placeholder-book.jpg') }}"
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
                        @if(session()->has('cart') && in_array($book->id, session('cart')))
                            <a href="{{ route('checkout') }}" class="btn btn-success" type="button">
                                <i class="bi bi-bag-check"></i> Оформить
                            </a>
                        @else
                            <form action="{{ route('cart.item.store', $book->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-cart-plus"></i> Добавить в корзину
                                </button>
                            </form>
                        @endif
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

            <!-- Add Review Button and Form -->
            <div class="mt-4">
                <button id="writeReviewBtn" class="btn btn-outline-primary">
                    <i class="bi bi-pencil"></i> Написать отзыв
                </button>

                <div id="reviewFormContainer" class="mt-3" style="display: none;">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('reviews.store', $book->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="rating" class="form-label">Рейтинг</label>
                                    <div class="rating-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" class="visually-hidden">
                                            <label for="star{{ $i }}" class="star-label"><i class="bi bi-star"></i></label>
                                        @endfor
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="content" class="form-label">Ваш отзыв</label>
                                    <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Отправить</button>
                                <button type="button" id="cancelReviewBtn" class="btn btn-outline-secondary ms-2">Отмена</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</x-layout>
