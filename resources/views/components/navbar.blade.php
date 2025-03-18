<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
    <div class="container">
        <!-- Logo/Brand -->
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
            <!-- Search Form (Moved from main page) -->
            <form action="{{ route('search.results') }}" method="GET" class="d-flex mx-auto my-2 my-lg-0 col-12 col-lg-5">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Поиск книг..." name="query" aria-label="Search">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>

            <!-- User Actions -->
            <ul class="navbar-nav ms-lg-3">
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
                            <li><a class="dropdown-item" href="{{ route('profile') }}">Мой профиль</a></li>
                            <li><a class="dropdown-item" href="{{ route('orders') }}">Мои заказы</a></li>
                            <li><a class="dropdown-item" href="{{ route('wishlist') }}">Избранное</a></li>
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
                        <a class="nav-link text-dark" href="{{ route('catalog.category', 'biography') }}">Биографии</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('catalog.category', 'education') }}">Учебная</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
