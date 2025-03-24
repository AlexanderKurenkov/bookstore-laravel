<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
    <div class="container">
        <!-- Logo/Brand (Left) -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('index') }}">
            <i class="bi bi-book fs-3 text-primary me-2"></i>
            <span class="fw-bold">{{__('Bookstore')}}</span>
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Search Form (Middle) -->
            <form action="{{ route('search.results') }}" method="GET" class="w-50 mx-auto">
                <div class="input-group">
                    <input type="text" class="form-control flex-grow-1" placeholder="Поиск книг..." name="query">

                    <select class="form-select flex-shrink-1" name="category" style="max-width: 250px;">
                        <option value="">Все категории</option>
                        <option value="classics">Художественная литература</option>
                        <option value="detective">Детектив</option>
                        <option value="philosophy">Философия</option>
                        <option value="poetry">Поэзия</option>
                        <option value="history">История</option>
                        <option value="fantasy">Фэнтези</option>
                        <option value="biography">Биография</option>
                        <option value="fiction">Фантастика</option>
                        <option value="science">Наука</option>
                    </select>

                    <button class="btn btn-light" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>

            <!-- User Actions (Right) -->
            <ul class="navbar-nav ms-auto">
                <!-- Favorites Button -->
                <li class="nav-item me-2">
                    <a class="nav-link position-relative" href="#" data-bs-toggle="modal" data-bs-target="#favoritesModal">
                        <i class="bi bi-heart fs-5"></i>
                        {{-- TODO use session instead --}}
                        {{-- @if(auth()->check() && auth()->user()->favorites->count() > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ auth()->user()->favorites->count() }}
                                <span class="visually-hidden">избранных книг</span>
                            </span>
                        @endif --}}
                    </a>
                </li>

                <!-- Cart Dropdown -->
                <li class="nav-item dropdown me-2">
                    <a class="nav-link position-relative" href="#" id="cartDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-cart fs-5"></i>
                        @if(session()->has('cart') && count(session('cart')) > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ count(session('cart')) }}
                                <span class="visually-hidden">товаров в корзине</span>
                            </span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="cartDropdown" style="min-width: 300px;">
                        <h6 class="dropdown-header">Корзина</h6>

                        @if(session()->has('cart') && count(session('cart')) > 0)
                            <div class="cart-items mb-3" style="max-height: 300px; overflow-y: auto;">
                                @foreach(session('cart') as $item)
                                    <div class="d-flex align-items-center mb-2 pb-2 border-bottom">
                                        <img src="{{ $item['image'] ?? '/placeholder.svg?height=50&width=40' }}"
                                             alt="{{ $item['title'] ?? 'Книга' }}" class="me-2"
                                             style="width: 40px; height: 50px; object-fit: cover;">
                                        <div class="flex-grow-1">
                                            <div class="small fw-bold">{{ $item['title'] ?? 'Название книги' }}</div>
                                            <div class="small text-muted">{{ $item['price'] ?? '0.00' }} ₽ × {{ $item['quantity'] ?? 1 }}</div>
                                        </div>
                                        <form action="{{ route('cart.item.destroy', $item['id'] ?? 1) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm text-danger">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="fw-bold">Итого:</span>
                                <span class="fw-bold">{{ session('cart_total') ?? '0.00' }} ₽</span>
                            </div>
                            <div class="d-grid gap-2">
                                <a href="{{ route('cart.index') }}" class="btn btn-outline-primary">Просмотр корзины</a>
                                <a href="{{ route('checkout.index') }}" class="btn btn-primary">Оформить заказ</a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-cart-x fs-1 text-muted"></i>
                                <p class="mt-2">Ваша корзина пуста</p>
                                <a href="{{ route('index') }}" class="btn btn-sm btn-outline-primary">Начать покупки</a>
                            </div>
                        @endif
                    </div>
                </li>

                <!-- User Account Dropdown -->
                <li class="nav-item dropdown">
                    @auth
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-5"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><h6 class="dropdown-header">{{ Auth::user()->name }}</h6></li>
                            <li><a class="dropdown-item" href="{{ route('profile.index') }}">Мой профиль</a></li>
                            {{-- TODO Why links don't work? --}}
                            {{-- <li><a class="dropdown-item" href="{{ route('profile.index') }}#orders">Мои заказы</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.index') }}#wishlist">Избранное</a></li> --}}

                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Выйти</button>
                                </form>
                            </li>
                        </ul>
                    @else
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person fs-5"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('login') }}">Войти</a></li>
                            <li><a class="dropdown-item" href="{{ route('register') }}">Регистрация</a></li>
                        </ul>
                    @endauth
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Category Submenu (Optional - for desktop only) -->
<div class="bg-light py-2 d-none d-lg-block border-bottom">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('catalog.index') }}">Все категории</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('catalog.category', 'classics') }}">Классика</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('catalog.category', 'detective') }}">Детектив</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('catalog.category', 'philosophy') }}">Философия</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('catalog.category', 'poetry') }}">Поэзия</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('catalog.category', 'history') }}">История</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('catalog.category', 'fantasy') }}">Фэнтези</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('catalog.category', 'biography') }}">Биография</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('catalog.category', 'fiction') }}">Фантастика</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('catalog.category', 'science') }}">Наука</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Favorites Modal -->
<div class="modal fade" id="favoritesModal" tabindex="-1" aria-labelledby="favoritesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="favoritesModalLabel">
                    <i class="bi bi-heart-fill text-danger me-2"></i>Избранное
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @auth
                    @if(auth()->user()->favorites->count() > 0)
                        <div class="favorites-list" style="max-height: 400px; overflow-y: auto;">
                            @foreach(auth()->user()->favorites as $favorite)
                                <div class="card mb-3">
                                    <div class="row g-0">
                                        <div class="col-md-2">
                                            <img src="{{ $favorite->image_path ?? '/placeholder.svg?height=120&width=80' }}"
                                                 class="img-fluid rounded-start" alt="{{ $favorite->title }}"
                                                 style="height: 120px; width: 80px; object-fit: cover;">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body py-2">
                                                <h5 class="card-title">{{ $favorite->title }}</h5>
                                                <p class="card-text text-muted mb-1">{{ $favorite->author }}</p>
                                                <p class="card-text"><strong class="text-primary">{{ number_format($favorite->price, 2) }} ₽</strong></p>
                                            </div>
                                        </div>
                                        <div class="col-md-2 d-flex align-items-center justify-content-center">
                                            <div class="btn-group-vertical">
                                                <a href="{{ route('catalog.book', $favorite->id) }}" class="btn btn-sm btn-outline-primary mb-2">
                                                    <i class="bi bi-eye"></i> Просмотр
                                                </a>
                                                <form action="{{ route('favorites.toggle', $favorite->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger mb-2">
                                                        <i class="bi bi-trash"></i> Удалить
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-heart fs-1 text-muted"></i>
                            <p class="mt-3">У вас пока нет избранных книг</p>
                            <p class="text-muted">Добавляйте понравившиеся книги в избранное, чтобы вернуться к ним позже</p>
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-person-lock fs-1 text-muted"></i>
                        <p class="mt-3">Войдите в аккаунт, чтобы добавлять книги в избранное</p>
                        <div class="mt-3">
                            <a href="{{ route('login') }}" class="btn btn-primary me-2">Войти</a>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary">Регистрация</a>
                        </div>
                    </div>
                @endauth
            </div>
            <div class="modal-footer">
                @auth
                    @if(auth()->user()->favorites->count() > 0)
                        <a href="{{ route('favorites.index') }}" class="btn btn-primary">Перейти в избранное</a>
                    @endif
                @endauth
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
