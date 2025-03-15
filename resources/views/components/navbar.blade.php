<!-- resources/views/components/navbar.blade.php -->
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home.index') }}">Книгочей</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home.index') ? 'active' : '' }}" href="{{ route('home.index') }}">Главная</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('catalog.index') ? 'active' : '' }}" href="{{ route('catalog.index') }}">Каталог</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home.faq') ? 'active' : '' }}" href="{{ route('home.faq') }}">Вопрос-ответ</a>
                </li>
            </ul>

            <div class="d-flex">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <form class="d-flex" action="{{ route('search.index') }}" method="post" role="search">
                            @csrf
                            <input class="form-control me-2" type="search" name="query" placeholder="Найти" aria-label="Search">
                            <button class="btn btn-outline-secondary" type="submit">Поиск</button>
                        </form>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cart.index') }}">Корзина</a>
                    </li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Войти</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile.index') }}">Профиль</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Выйти
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </div>
</nav>
