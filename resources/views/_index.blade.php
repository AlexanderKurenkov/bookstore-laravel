<x-layout>
    <!-- Hero Banner -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card bg-dark text-white rounded-3 overflow-hidden">
                <img src="{{ asset('images/banner.jpg') }}" class="card-img opacity-50" alt="Книжный магазин" style="height: 400px; object-fit: cover;">
                <div class="card-img-overlay d-flex flex-column justify-content-center">
                    <div class="container">
                        <h1 class="card-title display-4 fw-bold">Добро пожаловать в наш магазин</h1>
                        <p class="card-text fs-5 mb-4">Откройте для себя новые миры с нашей коллекцией книг</p>
                        <div class="d-flex">
                            {{-- <a href="{{ route('books.new') }}" class="btn btn-primary btn-lg me-3">Новинки</a> --}}
                            {{-- <a href="{{ route('books.bestsellers') }}" class="btn btn-outline-light btn-lg">Бестселлеры</a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="mb-4">Популярные категории</h2>
        </div>
        <div class="col-12">
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3">
                <div class="col">
                    <a href="{{ route('catalog.category', 'fiction') }}" class="text-decoration-none">
                        <div class="card h-100 text-center hover-shadow">
                            <div class="card-body">
                                <i class="bi bi-book fs-1 text-primary mb-3"></i>
                                <h5 class="card-title">Художественная литература</h5>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('catalog.category', 'non-fiction') }}" class="text-decoration-none">
                        <div class="card h-100 text-center hover-shadow">
                            <div class="card-body">
                                <i class="bi bi-journal-text fs-1 text-success mb-3"></i>
                                <h5 class="card-title">Нон-фикшн</h5>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('catalog.category', 'science') }}" class="text-decoration-none">
                        <div class="card h-100 text-center hover-shadow">
                            <div class="card-body">
                                <i class="bi bi-graph-up fs-1 text-info mb-3"></i>
                                <h5 class="card-title">Наука</h5>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('catalog.category', 'children') }}" class="text-decoration-none">
                        <div class="card h-100 text-center hover-shadow">
                            <div class="card-body">
                                <i class="bi bi-emoji-smile fs-1 text-warning mb-3"></i>
                                <h5 class="card-title">Детские книги</h5>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('catalog.category', 'business') }}" class="text-decoration-none">
                        <div class="card h-100 text-center hover-shadow">
                            <div class="card-body">
                                <i class="bi bi-briefcase fs-1 text-danger mb-3"></i>
                                <h5 class="card-title">Бизнес</h5>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('catalog.index') }}" class="text-decoration-none">
                        <div class="card h-100 text-center hover-shadow">
                            <div class="card-body">
                                <i class="bi bi-grid fs-1 text-secondary mb-3"></i>
                                <h5 class="card-title">Все категории</h5>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- New Releases -->
    <div class="row mb-5">
        <div class="col-12 d-flex justify-content-between align-items-center mb-4">
            <h2>Новинки</h2>
            {{-- TODO --}}
            <a href="{{ route('index') }}" class="btn btn-outline-primary">Смотреть все</a>
            {{-- <a href="{{ route('catalog.new') }}" class="btn btn-outline-primary">Смотреть все</a> --}}
        </div>

        <div class="col-12">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                @foreach($newBooks ?? [] as $book)
                <div class="col">
                    <div class="card h-100 book-card">
                        <div class="position-relative">
                            <img src="{{ $book->image_url ?? '/placeholder.svg?height=300&width=200' }}" class="card-img-top" alt="{{ $book->title ?? 'Новая книга' }}" style="height: 300px; object-fit: cover;">
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge bg-danger">Новинка</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $book->title ?? 'Название книги' }}</h5>
                            <p class="card-text text-muted">{{ $book->author ?? 'Автор книги' }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fs-5 fw-bold text-primary">{{ isset($book->price) ? number_format($book->price, 2) . ' ₽' : '450.00 ₽' }}</span>
                                <div class="d-flex">
                                    <form action="{{ route('cart.add', $book->id ?? 1) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary me-2">
                                            <i class="bi bi-cart-plus"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('books.show', $book->id ?? 1) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Fallback if no books are provided -->
                @if(empty($newBooks ?? []))
                    @for($i = 0; $i < 4; $i++)
                    <div class="col">
                        <div class="card h-100 book-card">
                            <div class="position-relative">
                                <img src="/placeholder.svg?height=300&width=200" class="card-img-top" alt="Новая книга" style="height: 300px; object-fit: cover;">
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-danger">Новинка</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Название книги {{ $i + 1 }}</h5>
                                <p class="card-text text-muted">Автор книги</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fs-5 fw-bold text-primary">{{ (450 + $i * 50) . '.00 ₽' }}</span>
                                    <div class="d-flex">
                                        <button type="button" class="btn btn-sm btn-primary me-2">
                                            <i class="bi bi-cart-plus"></i>
                                        </button>
                                        <a href="#" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endfor
                @endif
            </div>
        </div>
    </div>

    <!-- Bestsellers -->
    <div class="row mb-5">
        <div class="col-12 d-flex justify-content-between align-items-center mb-4">
            <h2>Бестселлеры</h2>
            {{-- TODO --}}
            <a href="{{ route('index') }}" class="btn btn-outline-primary">Смотреть все</a>
            {{-- <a href="{{ route('catalog.bestsellers') }}" class="btn btn-outline-primary">Смотреть все</a> --}}
        </div>

        <div class="col-12">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                @foreach($bestsellerBooks ?? [] as $book)
                <div class="col">
                    <div class="card h-100 book-card">
                        <div class="position-relative">
                            <img src="{{ $book->image_url ?? '/placeholder.svg?height=300&width=200' }}" class="card-img-top" alt="{{ $book->title ?? 'Бестселлер' }}" style="height: 300px; object-fit: cover;">
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge bg-success">Бестселлер</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= ($book->rating ?? 4))
                                        <i class="bi bi-star-fill text-warning"></i>
                                    @else
                                        <i class="bi bi-star text-warning"></i>
                                    @endif
                                @endfor
                                <small class="text-muted ms-1">{{ $book->reviews_count ?? rand(10, 100) }} отзывов</small>
                            </div>
                            <h5 class="card-title">{{ $book->title ?? 'Название книги' }}</h5>
                            <p class="card-text text-muted">{{ $book->author ?? 'Автор книги' }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fs-5 fw-bold text-primary">{{ isset($book->price) ? number_format($book->price, 2) . ' ₽' : '550.00 ₽' }}</span>
                                <div class="d-flex">
                                    <form action="{{ route('cart.add', $book->id ?? 1) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary me-2">
                                            <i class="bi bi-cart-plus"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('books.show', $book->id ?? 1) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Fallback if no books are provided -->
                @if(empty($bestsellerBooks ?? []))
                    @for($i = 0; $i < 4; $i++)
                    <div class="col">
                        <div class="card h-100 book-card">
                            <div class="position-relative">
                                <img src="/placeholder.svg?height=300&width=200" class="card-img-top" alt="Бестселлер" style="height: 300px; object-fit: cover;">
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-success">Бестселлер</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= 4)
                                            <i class="bi bi-star-fill text-warning"></i>
                                        @else
                                            <i class="bi bi-star text-warning"></i>
                                        @endif
                                    @endfor
                                    <small class="text-muted ms-1">{{ rand(10, 100) }} отзывов</small>
                                </div>
                                <h5 class="card-title">Бестселлер {{ $i + 1 }}</h5>
                                <p class="card-text text-muted">Известный автор</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fs-5 fw-bold text-primary">{{ (550 + $i * 50) . '.00 ₽' }}</span>
                                    <div class="d-flex">
                                        <button type="button" class="btn btn-sm btn-primary me-2">
                                            <i class="bi bi-cart-plus"></i>
                                        </button>
                                        <a href="#" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endfor
                @endif
            </div>
        </div>
    </div>

    <!-- Special Offers -->
    <div class="row mb-5">
        <div class="col-12 mb-4">
            <h2>Специальные предложения</h2>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="/placeholder.svg?height=300&width=200" class="img-fluid rounded-start h-100" alt="Специальное предложение" style="object-fit: cover;">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h3 class="card-title">Скидка 20% на все книги по психологии</h3>
                            <p class="card-text">Только до конца месяца! Используйте промокод PSYCH20 при оформлении заказа.</p>
                            <a href="{{ route('catalog.category', 'psychology') }}" class="btn btn-light">Перейти к книгам</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="/placeholder.svg?height=300&width=200" class="img-fluid rounded-start h-100" alt="Специальное предложение" style="object-fit: cover;">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h3 class="card-title">Бесплатная доставка</h3>
                            <p class="card-text">При заказе от 2000 рублей доставка бесплатна по всей России!</p>
                            <a href="{{ route('index')}}" class="btn btn-light">Подробнее о доставке</a>
                            {{-- <a href="{{ route('delivery.info') }}" class="btn btn-light">Подробнее о доставке</a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Author Spotlight -->
    <div class="row mb-5">
        <div class="col-12 mb-4">
            <h2>Автор месяца</h2>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="row g-0">
                    <div class="col-md-3">
                        <img src="/placeholder.svg?height=400&width=300" class="img-fluid rounded-start" alt="Автор месяца" style="height: 100%; object-fit: cover;">
                    </div>
                    <div class="col-md-9">
                        <div class="card-body">
                            <h3 class="card-title">Федор Достоевский</h3>
                            <p class="card-text">Один из самых значительных и известных в мире русских писателей и мыслителей. Его художественное наследие анализируется литературоведами во всем мире, а произведения переведены на более чем 170 языков.</p>
                            <p class="card-text">Романы «Преступление и наказание», «Идиот», «Бесы», «Братья Карамазовы» включены в список 100 лучших книг всех времен.</p>

                            <div class="row row-cols-2 row-cols-md-4 g-3 mt-3">
                                <div class="col">
                                    <div class="card h-100">
                                        <img src="/placeholder.svg?height=200&width=150" class="card-img-top" alt="Книга автора" style="height: 200px; object-fit: cover;">
                                        <div class="card-body p-2">
                                            <h6 class="card-title">Преступление и наказание</h6>
                                            <p class="card-text text-primary fw-bold">350.00 ₽</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card h-100">
                                        <img src="/placeholder.svg?height=200&width=150" class="card-img-top" alt="Книга автора" style="height: 200px; object-fit: cover;">
                                        <div class="card-body p-2">
                                            <h6 class="card-title">Идиот</h6>
                                            <p class="card-text text-primary fw-bold">380.00 ₽</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card h-100">
                                        <img src="/placeholder.svg?height=200&width=150" class="card-img-top" alt="Книга автора" style="height: 200px; object-fit: cover;">
                                        <div class="card-body p-2">
                                            <h6 class="card-title">Бесы</h6>
                                            <p class="card-text text-primary fw-bold">400.00 ₽</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card h-100">
                                        <img src="/placeholder.svg?height=200&width=150" class="card-img-top" alt="Книга автора" style="height: 200px; object-fit: cover;">
                                        <div class="card-body p-2">
                                            <h6 class="card-title">Братья Карамазовы</h6>
                                            <p class="card-text text-primary fw-bold">420.00 ₽</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('index', 1) }}" class="btn btn-outline-primary mt-4">Все книги автора</a>
                            {{-- <a href="{{ route('authors.show', 1) }}" class="btn btn-outline-primary mt-4">Все книги автора</a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Newsletter Subscription -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body text-center py-5">
                    <h3 class="mb-3">Подпишитесь на нашу рассылку</h3>
                    <p class="mb-4">Получайте информацию о новинках, скидках и специальных предложениях</p>
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <form action="{{ route('index') }}" method="POST" class="d-flex">
                            {{-- <form action="{{ route('newsletter.subscribe') }}" method="POST" class="d-flex"> --}}
                                @csrf
                                <input type="email" class="form-control me-2" placeholder="Ваш email" required>
                                <button type="submit" class="btn btn-primary">Подписаться</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layout>