<x-layout>
    <div class="container my-5">
        <div class="row d-flex align-items-stretch">
            <div class="row">
                <!-- Book Image Column with Gallery -->
                <div class="col-md-6 mb-4">
                    <div class="card w-100">
                        <div id="bookImageCarousel" class="carousel slide" data-bs-ride="false">
                            <!-- Carousel Images -->
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="{{ $book->image_path ?? asset('images/placeholder-book.jpg') }}"
                                        class="d-block w-100 img-fluid"
                                        alt="{{ $book->title ?? 'Book Cover' }}">
                                </div>

                                @if($book->sample_page_images)
                                @foreach($book->sample_page_images as $image)
                                <div class="carousel-item">
                                    <img src="{{ $image }}"
                                        class="d-block w-100 img-fluid"
                                        alt="Страница {{ $loop->iteration }}">
                                </div>
                                @endforeach
                                @endif
                            </div>


                            <!-- Caption below image -->
                            <div class="carousel-caption position-static d-block bg-light text-dark p-2 mb-2">
                                <p class="mb-0" id="carousel-caption-text">Обложка</p>
                            </div>

                            <!-- Navigation arrows -->
                            <button class="carousel-control-prev" type="button" data-bs-target="#bookImageCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon bg-secondary rounded-circle" aria-hidden="true"></span>
                                <span class="visually-hidden">Предыдущая</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#bookImageCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon bg-secondary rounded-circle" aria-hidden="true"></span>
                                <span class="visually-hidden">Следующая</span>
                            </button>

                            <!-- Navigation dots -->
                            <div class="carousel-indicators position-static mt-2">
                                <button type="button" data-bs-target="#bookImageCarousel" data-bs-slide-to="0" class="active bg-primary" aria-current="true" aria-label="Cover"></button>
                                @if($book->sample_page_images)
                                @foreach($book->sample_page_images as $index => $image)
                                <button type="button" data-bs-target="#bookImageCarousel" data-bs-slide-to="{{ $index + 1 }}" class="bg-primary" aria-label="Page {{ $index + 1 }}"></button>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Book Details Column -->
                <div class="col-md-6">
                    <div class="card w-100">
                        <div class="card-body">
                            <h1 class="card-title mb-3">{{ $book->title }}</h1>

                            <!-- Rating Display -->
                            <div class="mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="rating-stars me-2">
                                        @if($ratingValue)
                                        <span class="ms-2">{{ $ratingValue }} из 5</span>
                                        @for($i = 5; $i >= 1; $i--) {{-- Loop in reverse --}}
                                        @if($i <= floor($ratingValue))
                                            <i class="bi bi-star-fill text-warning"></i>
                                            @elseif($i - 0.5 <= $ratingValue)
                                                <i class="bi bi-star-half text-warning"></i>
                                                @else
                                                <i class="bi bi-star text-warning"></i>
                                                @endif
                                                @endfor
                                                @else
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star text-warning"></i>
                                                    @endfor
                                                    <span class="ms-2">Нет оценок</span>
                                                    @endif
                                    </div>

                                    @if($reviewCount > 0)
                                    <a href="#reviews" class="ms-3 text-decoration-none">
                                        Отзывы: {{ $reviewCount }}
                                    </a>
                                    @endif
                                </div>
                            </div>


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

                            @php
                            $cart = session('cart', []);
                            $bookInCart = isset($cart[$book->id]); // Check if book exists in cart
                            @endphp

                            <div class="d-grid gap-2 d-md-flex align-items-center justify-content-md-start mb-4">
                                @if($book->publication_type == 'physical' && $book->quantity_in_stock <= 0)
                                    <div>
                                    <button class="btn btn-secondary" type="button" disabled>
                                        <i class="bi bi-cart-plus"></i> Нет в наличии
                                    </button>
                            </div>
                            @else
                            <div>
                                <button id="addToCartBtn{{ $book->id }}"
                                    class="btn flex-grow-1 {{ $bookInCart ? 'btn-primary added' : 'btn-outline-primary' }}"
                                    onclick="addToCartButtonClicked({{ $book->id }})"
                                    data-href="{{ route('checkout.index') }}">

                                    <i class="bi {{ $bookInCart ? 'bi-bag-check' : 'bi-cart-plus me-1' }}"></i>
                                    {{ $bookInCart ? ' Оформить' : ' Добавить' }}
                                </button>
                            </div>

                            @endif
                            <div>
                                {{-- <a class="nav-link position-relative" href="#" data-bs-toggle="modal" data-bs-target="#favoritesModal">
                                            <i class="bi bi-heart fs-3"></i>
                                        </a> --}}
                                <!-- Favorite Button (Absolute Position) -->
                                <button type="button"
                                    id="favoriteBtn{{ $book->id }}"
                                    class="btn btn-lg btn-light rounded-circle
                                                    {{-- position-absolute top-0 end-0 m-2  --}}
                                                    favorite-btn"
                                    onclick="toggleFavorite({{ $book->id }})"
                                    {{-- href="#" data-bs-toggle="modal" data-bs-target="#favoritesModal" --}}>
                                    <i id="favoriteIcon{{ $book->id }}" class="bi {{ auth()->check() && auth()->user()->favorites->contains($book->id) ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
                                </button>

                            </div>
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
    <div class="row mt-5" id="reviews">
        <div class="col-12">
            <h2 class="mb-4">Отзывы</h2>

            @if($book->reviews->count() > 0)
            @foreach($book->reviews as $review)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        {{-- <h5 class="card-title">{{ $review->user->name }}</h5> --}}
                        <h5 class="card-title">
                            <i class="bi bi-person-circle me-2"></i>{{ $review->user->first_name }} {{ $review->user->last_name }}
                        </h5>

                        <div class="text-warning">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <=$review->rating)
                                <i class="bi bi-star-fill"></i>
                                @elseif($i - 0.5 <= $review->rating)
                                    <i class="bi bi-star-half"></i>
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
                @if(auth()->check())
                @php
                $userHasReviewed = $book->reviews->contains('user_id', auth()->id());
                @endphp

                @if($userHasReviewed)
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>Вы уже оставили отзыв на эту книгу. Вы можете оставить только один отзыв для каждой книги.
                </div>
                @else
                <button id="writeReviewBtn" class="btn btn-outline-primary">
                    <i class="bi bi-pencil"></i> Написать отзыв
                </button>

                <div id="reviewFormContainer" class="mt-3" style="display: none;">
                    <div class="card">
                        <div class="card-body">
                            {{-- <form id="reviewForm" action="{{ route('reviews.store', $book->id) }}" method="POST"> --}}
                            <form id="reviewForm" action="{{ route('reviews.store', ['id' => $book->id]) }}" method="POST">

                                @csrf
                                <input type="hidden" name="book_id" value="{{ $book->id }}">
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
                                    <label for="comment" class="form-label">Ваш отзыв</label>
                                    <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Отправить</button>
                                <button type="button" id="cancelReviewBtn" class="btn btn-outline-secondary ms-2">Отмена</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
                @else
                <button id="writeReviewBtn" class="btn btn-outline-primary">
                    <i class="bi bi-pencil"></i> Написать отзыв
                </button>
                @endif
            </div>
        </div>
    </div>
    <!-- Login Modal for Reviews -->
    <div class="modal fade" id="loginReviewModal" tabindex="-1" aria-labelledby="loginReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginReviewModalLabel">
                        <i class="bi bi-person-lock me-2"></i>Требуется авторизация
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center py-4">
                        <i class="bi bi-person-lock fs-1 text-muted"></i>
                        <p class="mt-3">Чтобы оставить отзыв, необходимо войти в аккаунт</p>
                        <div class="mt-3">
                            <a href="{{ route('login') }}" class="btn btn-primary me-2">Войти</a>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary">Регистрация</a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Carousel caption update
            const carousel = document.getElementById('bookImageCarousel');
            const captionText = document.getElementById('carousel-caption-text');

            if (carousel && captionText) {
                carousel.addEventListener('slide.bs.carousel', function(event) {
                    const slideIndex = event.to;
                    if (slideIndex === 0) {
                        captionText.textContent = 'Обложка';
                    } else {
                        captionText.textContent = 'Страница ' + slideIndex;
                    }
                });
            }

            // Review form toggle
            const writeReviewBtn = document.getElementById('writeReviewBtn');
            const reviewFormContainer = document.getElementById('reviewFormContainer');
            const cancelReviewBtn = document.getElementById('cancelReviewBtn');
            const ratingStars = document.querySelectorAll('.star-label');
            const ratingValue = document.getElementById('ratingValue');

            if (writeReviewBtn) {
                writeReviewBtn.addEventListener('click', function() {
                    @if(auth() - > check())
                    @if(!$book - > reviews - > contains('user_id', auth() - > id()))
                    reviewFormContainer.style.display = 'block';
                    writeReviewBtn.style.display = 'none';

                    // Initialize stars
                    updateStars(5);
                    @else
                    // User has already reviewed this book
                    alert('Вы уже оставили отзыв на эту книгу. Вы можете оставить только один отзыв для каждой книги.');
                    @endif
                    @else
                    // Show the login modal
                    var loginModal = new bootstrap.Modal(document.getElementById('loginReviewModal'));
                    loginModal.show();
                    @endif
                });

                if (cancelReviewBtn) {
                    cancelReviewBtn.addEventListener('click', function() {
                        reviewFormContainer.style.display = 'none';
                        writeReviewBtn.style.display = 'inline-block';
                    });
                }
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

            // Check authentication before submitting review
            const reviewForm = document.getElementById('reviewForm');
            if (reviewForm) {
                reviewForm.addEventListener('submit', function(event) {
                    // Check if user is authenticated
                    const isAuthenticated = {
                        {
                            auth() - > check() ? 'true' : 'false'
                        }
                    };

                    if (!isAuthenticated) {
                        event.preventDefault();

                        // Show login modal
                        const loginModal = new bootstrap.Modal(document.getElementById('loginReviewModal'));
                        loginModal.show();
                    }
                });
            }
        });
    </script>
    @endpush

    @push('head')
    <style>
        /* Carousel styling */
        .carousel-item img {
            height: 700px;
            object-fit: contain;
            background-color: #f8f9fa;
        }

        .carousel-caption {
            position: static !important;
            padding: 3px !important;
            margin-bottom: 0 !important;
            border-radius: 0 0 4px 4px;
        }

        .carousel-indicators {
            position: static !important;
            margin: 4px 0 !important;
            justify-content: center;
        }

        .carousel-indicators [data-bs-target] {
            width: 10px !important;
            height: 10px !important;
            border-radius: 50% !important;
            margin: 0 4px !important;
            border: none !important;
            opacity: 0.4;
        }

        .carousel-indicators .active {
            opacity: 0.9;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            opacity: 0.3;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            filter: grayscale(10%);
            Convert arrow to grey opacity: 0.5;
            /* Adjust transparency */
            background-color: transparent;
            /* Remove background */
            width: 2rem;
            /* Adjust arrow size */
            height: 2rem;
        }

        .carousel-control-prev:hover .carousel-control-prev-icon,
        .carousel-control-next:hover .carousel-control-next-icon {
            opacity: 0.7;
            /* Slightly darker on hover */
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

        .star-label:hover~.star-label i,
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

</x-layout>