<x-layout>

<div class="container my-5">
    <div class="row">
        <!-- Book Image Column with Gallery -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div id="bookImageCarousel" class="carousel slide" data-bs-ride="false">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#bookImageCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Cover"></button>
                        @if($book->sample_page_images)
                            @foreach($book->sample_page_images as $index => $image)
                                <button type="button" data-bs-target="#bookImageCarousel" data-bs-slide-to="{{ $index + 1 }}" aria-label="Page {{ $index + 1 }}"></button>
                            @endforeach
                        @endif
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="{{ $book->image_path ?? asset('images/placeholder-book.jpg') }}"
                                 class="d-block w-100 img-fluid"
                                 alt="{{ $book->title ?? 'Book Cover' }}">
                            <div class="carousel-caption d-none d-md-block">
                                <h5 class="bg-dark bg-opacity-50 p-1 rounded">Обложка</h5>
                            </div>
                        </div>
                        @if($book->sample_page_images)
                            @foreach($book->sample_page_images as $index => $image)
                                <div class="carousel-item">
                                    <img src="{{ $image }}"
                                         class="d-block w-100 img-fluid"
                                         alt="Страница {{ $index + 1 }}">
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5 class="bg-dark bg-opacity-50 p-1 rounded">Страница {{ $index + 1 }}</h5>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#bookImageCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Предыдущая</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#bookImageCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Следующая</span>
                    </button>
                </div>
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

                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">ISBN:</div>
                        <div class="col-md-8">{{ $book->isbn }}</div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Язык:</div>
                        <div class="col-md-8">{{ $book->language }}</div>
                    </div>

                    @if($book->edition)
                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Издание:</div>
                        <div class="col-md-8">{{ $book->edition }}</div>
                    </div>
                    @endif

                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Тип издания:</div>
                        <div class="col-md-8">
                            @switch($book->publication_type)
                                @case('physical')
                                    Печатная книга
                                    @break
                                @case('ebook')
                                    Электронная книга
                                    @break
                                @case('audiobook')
                                    Аудиокнига
                                    @break
                                @default
                                    {{ $book->publication_type }}
                            @endswitch
                        </div>
                    </div>

                    @if($book->publication_type == 'physical')
                        @if($book->binding_type)
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Тип переплета:</div>
                            <div class="col-md-8">
                                @switch($book->binding_type)
                                    @case('hardcover')
                                        Твердый переплет
                                        @break
                                    @case('paperback')
                                        Мягкая обложка
                                        @break
                                    @default
                                        {{ $book->binding_type }}
                                @endswitch
                            </div>
                        </div>
                        @endif

                        @if($book->pages)
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Количество страниц:</div>
                            <div class="col-md-8">{{ $book->pages }}</div>
                        </div>
                        @endif

                        @if($book->weight)
                        <div class="row mb-2">
                            <div class="col-md-4 fw-bold">Вес:</div>
                            <div class="col-md-8">{{ $book->weight }} г</div>
                        </div>
                        @endif
                    @endif

                    <div class="row mb-2">
                        <div class="col-md-4 fw-bold">Наличие:</div>
                        <div class="col-md-8">
                            @if($book->publication_type == 'physical')
                                @if($book->quantity_in_stock > 10)
                                    <span class="badge bg-success">В наличии</span>
                                @elseif($book->quantity_in_stock > 0)
                                    <span class="badge bg-warning text-dark">Осталось мало ({{ $book->quantity_in_stock }} шт.)</span>
                                @else
                                    <span class="badge bg-danger">Нет в наличии</span>
                                @endif
                            @else
                                <span class="badge bg-success">Доступно для скачивания</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 fw-bold">Цена:</div>
                        <div class="col-md-8">
                            <span class="fs-4 text-primary">{{ number_format($book->price, 2) }} ₽</span>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-start mb-4">
                        @if($book->publication_type == 'physical' && $book->quantity_in_stock <= 0)
                            <button class="btn btn-secondary" type="button" disabled>
                                <i class="bi bi-cart-plus"></i> Нет в наличии
                            </button>
                        @else
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
                        @endif

                        <form action="toggleFavoriteForm" method="POST" class="d-inline">
                        {{-- <form action="{{ route('favorites.toggle', $book->id) }}" method="POST" class="d-inline"> --}}
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary">
                                @if(auth()->check() && auth()->user()->favorites->contains($book->id))
                                    <i class="bi bi-heart-fill text-danger"></i> В избранном
                                @else
                                    <i class="bi bi-heart"></i> В избранное
                                @endif
                            </button>
                        </form>
                    </div>

                    <div class="row mb-2">
                        <div class="col-12 fw-bold fs-5 mb-2">Краткое описание:</div>
                        <div class="col-12">
                            <p>{{ $book->description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Book Specifications Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Характеристики</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th scope="row">ISBN</th>
                                        <td>{{ $book->isbn }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Автор</th>
                                        <td>{{ $book->author }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Издательство</th>
                                        <td>{{ $book->publisher }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Год издания</th>
                                        <td>{{ $book->publication_year }}</td>
                                    </tr>
                                    @if($book->edition)
                                    <tr>
                                        <th scope="row">Издание</th>
                                        <td>{{ $book->edition }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <th scope="row">Язык</th>
                                        <td>{{ $book->language }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Тип издания</th>
                                        <td>
                                            @switch($book->publication_type)
                                                @case('physical')
                                                    Печатная книга
                                                    @break
                                                @case('ebook')
                                                    Электронная книга
                                                    @break
                                                @case('audiobook')
                                                    Аудиокнига
                                                    @break
                                                @default
                                                    {{ $book->publication_type }}
                                            @endswitch
                                        </td>
                                    </tr>
                                    @if($book->publication_type == 'physical')
                                        @if($book->binding_type)
                                        <tr>
                                            <th scope="row">Тип переплета</th>
                                            <td>
                                                @switch($book->binding_type)
                                                    @case('hardcover')
                                                        Твердый переплет
                                                        @break
                                                    @case('paperback')
                                                        Мягкая обложка
                                                        @break
                                                    @default
                                                        {{ $book->binding_type }}
                                                @endswitch
                                            </td>
                                        </tr>
                                        @endif
                                        @if($book->pages)
                                        <tr>
                                            <th scope="row">Количество страниц</th>
                                            <td>{{ $book->pages }}</td>
                                        </tr>
                                        @endif
                                        @if($book->weight)
                                        <tr>
                                            <th scope="row">Вес</th>
                                            <td>{{ $book->weight }} г</td>
                                        </tr>
                                        @endif
                                    @endif
                                </tbody>
                            </table>
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
                                <div class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="bi bi-star-fill"></i>
                                        @else
                                            <i class="bi bi-star"></i>
                                        @endif
                                    @endfor
                                    <span class="ms-2 text-muted">({{ $review->rating }}/5)</span>
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
                                    <div class="rating-stars mb-2">
                                        @for($i = 5; $i >= 1; $i--)
                                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" class="visually-hidden" {{ $i == 5 ? 'checked' : '' }}>
                                            <label for="star{{ $i }}" class="star-label" title="{{ $i }} звезд"><i class="bi bi-star"></i></label>
                                        @endfor
                                    </div>
                                    <div class="selected-rating">Выбрано: <span id="ratingValue">5</span> из 5</div>
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

@push('head')
    <style>
        /* Carousel styling */
        .carousel-item img {
            height: 500px;
            object-fit: contain;
            background-color: #f8f9fa;
        }

        .carousel-caption {
            bottom: 0;
            left: 0;
            right: 0;
        }

        /* Rating stars styling */
        .rating-stars {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-start;
        }

        .star-label {
            cursor: pointer;
            font-size: 1.5rem;
            color: #ffc107;
            margin-right: 5px;
        }

        .star-label:hover ~ .star-label i,
        .star-label:hover i {
            color: #ffc107;
        }

        .selected-rating {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .visually-hidden {
            position: absolute;
            width: 1px;
            height: 1px;
            margin: -1px;
            padding: 0;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            border: 0;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Review form toggle
            const writeReviewBtn = document.getElementById('writeReviewBtn');
            const reviewFormContainer = document.getElementById('reviewFormContainer');
            const cancelReviewBtn = document.getElementById('cancelReviewBtn');
            const ratingStars = document.querySelectorAll('.star-label');
            const ratingValue = document.getElementById('ratingValue');

            if (writeReviewBtn && reviewFormContainer && cancelReviewBtn) {
                writeReviewBtn.addEventListener('click', function() {
                    reviewFormContainer.style.display = 'block';
                    writeReviewBtn.style.display = 'none';

                    // Initialize stars
                    updateStars(5);
                });

                cancelReviewBtn.addEventListener('click', function() {
                    reviewFormContainer.style.display = 'none';
                    writeReviewBtn.style.display = 'inline-block';
                });
            }

            // Star rating functionality
            if (ratingStars.length > 0 && ratingValue) {
                ratingStars.forEach(star => {
                    // When a star is clicked
                    star.addEventListener('click', function() {
                        const starValue = this.getAttribute('for').replace('star', '');
                        document.getElementById('star' + starValue).checked = true;
                        ratingValue.textContent = starValue;
                        updateStars(starValue);
                    });

                    // Hover effect
                    star.addEventListener('mouseover', function() {
                        const starValue = this.getAttribute('for').replace('star', '');
                        highlightStars(starValue);
                    });
                });

                // Reset stars when mouse leaves the rating area
                document.querySelector('.rating-stars').addEventListener('mouseleave', function() {
                    const checkedStar = document.querySelector('input[name="rating"]:checked');
                    if (checkedStar) {
                        const value = checkedStar.value;
                        highlightStars(value);
                    }
                });
            }

            // Helper function to update stars based on selection
            function updateStars(value) {
                ratingStars.forEach(star => {
                    const starNum = parseInt(star.getAttribute('for').replace('star', ''));
                    const icon = star.querySelector('i');

                    if (starNum <= value) {
                        icon.classList.remove('bi-star');
                        icon.classList.add('bi-star-fill');
                    } else {
                        icon.classList.remove('bi-star-fill');
                        icon.classList.add('bi-star');
                    }
                });

                if (ratingValue) {
                    ratingValue.textContent = value;
                }
            }

            // Helper function to highlight stars on hover
            function highlightStars(value) {
                ratingStars.forEach(star => {
                    const starNum = parseInt(star.getAttribute('for').replace('star', ''));
                    const icon = star.querySelector('i');

                    if (starNum <= value) {
                        icon.classList.remove('bi-star');
                        icon.classList.add('bi-star-fill');
                    } else {
                        icon.classList.remove('bi-star-fill');
                        icon.classList.add('bi-star');
                    }
                });
            }
        });
    </script>
@endpush
</x-layout>
