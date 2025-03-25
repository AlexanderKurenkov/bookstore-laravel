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
                    <a class="nav-link position-relative" href="#" id="cartDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="bi bi-cart fs-5"></span>

                        @if(session()->has('cart'))
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill
                            {{ count(session('cart')) > 0 ? 'bg-danger' : 'bg-secondary' }} cart-badge" id="cartBadgeCount">
                            {{ count(session('cart')) }}
                        </span>
                        @endif

                    </a>
                    <div class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="cartDropdown" style="min-width: 300px;">
                        <h6 class="dropdown-header">Корзина</h6>

                        <div id="cartDropdownContent">
                            @if(session()->has('cart') && count(session('cart')) > 0)
                                <div class="cart-items mb-3" style="max-height: 300px; overflow-y: auto;" id="cartItemsList">
                                    @foreach(session('cart') as $item)
                                        <div class="d-flex align-items-center mb-2 pb-2 border-bottom cart-item" data-item-id="{{ $item['id'] ?? 0 }}">
                                            <img src="{{ $item['image'] ?? '' }}"
                                                    alt="{{ $item['title'] ?? 'Книга' }}" class="me-2"
                                                    style="width: 40px; height: 50px; object-fit: cover;">
                                            <div class="flex-grow-1">
                                                <div class="small fw-bold">{{ $item['title'] ?? 'Название книги' }}</div>
                                                <div class="small text-muted">{{ $item['price'] ?? '0.00' }} ₽ × {{ $item['quantity'] ?? 1 }}</div>
                                            </div>
                                            <button type="button" class="btn btn-sm text-danger remove-cart-item" data-item-id="{{ $item['id'] ?? 1 }}">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="fw-bold">Итого:</span>
                                    <span class="fw-bold" id="cartTotalPrice">{{ session('cart_total') ?? '0.00' }} ₽</span>
                                </div>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('cart.index') }}" class="btn btn-outline-primary">Просмотр корзины</a>
                                    <a href="{{ route('checkout.index') }}" class="btn btn-primary">Оформить заказ</a>
                                </div>
                            @else
                                <div class="text-center py-4" id="emptyCartMessage">
                                    <i class="bi bi-cart-x fs-1 text-muted"></i>
                                    <p class="mt-2">Ваша корзина пуста</p>
                                    <a href="{{ route('index') }}" class="btn btn-sm btn-outline-primary">Начать покупки</a>
                                </div>
                            @endif
                        </div>
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

@push('scripts')
<script>
    function addToCartButtonClicked(id) {
        let button = document.getElementById('addToCartBtn' + id);

        // Check if the button is clicked for the first time
        if (!button.classList.contains('added')) {
            // Add loading state
            button.disabled = true;
            // const originalText = button.innerHTML;
            button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Добавляем';

            // Use the global addToCart function from navbar.blade.php
            try {
                // Call the global addToCart function with the book ID
                window.addToCart(id, 1);

                // Change the button text and class when clicked for the first time
                button.innerHTML = '<i class="bi bi-bag-check me-1"></i>Оформить';
                button.classList.remove('btn-outline-primary');
                button.classList.add('btn-primary');
                button.classList.add('added');  // Mark the button as added
                button.disabled = false;

            } catch (error) {
                console.error('Error:', error);
                // Remove loading state and restore original state on error
                button.disabled = false;
                button.innerHTML = originalText;
                button.classList.remove('btn-primary', 'added');
                button.classList.add('btn-outline-primary');
            }
        } else {
            // On the second click, navigate to the checkout page
            window.location.href = button.getAttribute('data-href');
        }
    }

    function toggleFavorite(id) {
        const button = document.getElementById('favoriteBtn' + id);
        const icon = document.getElementById('favoriteIcon' + id);

        // Add loading state
        button.disabled = true;

        // Send the AJAX request to toggle favorite status
        fetch("{{ route('favorites.toggle') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ bookId: id })
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 401) {
                    // User is not authenticated, redirect to login
                    window.location.href = "{{ route('login') }}";
                    throw new Error('Please login to add favorites');
                }
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Remove loading state
            button.disabled = false;

            // Update icon based on response
            if (data.isFavorite) {
                icon.classList.remove('bi-heart');
                icon.classList.add('bi-heart-fill', 'text-danger');
            } else {
                icon.classList.remove('bi-heart-fill', 'text-danger');
                icon.classList.add('bi-heart');
            }

            // Update favorites count in navbar if needed
            if (typeof updateFavoritesCount === 'function') {
                updateFavoritesCount(data.favoritesCount);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            button.disabled = false;
        });
    }

    document.addEventListener('cartUpdated', function (e) {
        e.detail.items.forEach(item => {
            let button = document.getElementById('addToCartBtn' + item.id);
            if (button) {
                button.innerHTML = '<i class="bi bi-bag-check me-1"></i> Оформить';
                button.classList.remove('btn-outline-primary');
                button.classList.add('btn-primary', 'added');
            }
        });

        // Reset buttons for items that are no longer in the cart
        document.querySelectorAll('.added').forEach(button => {
            let id = button.id.replace('addToCartBtn', '');
            const isInCart = e.detail.items.some(item => item.id == id);

            if (!isInCart) {
                button.innerHTML = '<i class="bi bi-cart-plus me-1"></i> Добавить'; // Original text
                button.classList.remove('btn-primary', 'added');
                button.classList.add('btn-outline-primary');
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Function to update cart count badge
        window.updateCartCount = function(count) {
            const cartBadge = document.getElementById('cartBadgeCount');

            if (cartBadge) {
                cartBadge.textContent = count;
                // cartBadge.style.display = count > 0 ? 'inline-block' : 'none';
                if(count > 0)
                    cartBadge.classList.replace("bg-secondary", "bg-danger");
                else
                    cartBadge.classList.replace("bg-danger", "bg-secondary");
            }
        };

        // Function to fetch cart data from server
        window.fetchCartData = function() {
            fetch('{{ route("cart.api.get") }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                updateCartDropdown(data);
            })
            .catch(error => {
                console.error('Error fetching cart data:', error);
            });
        };

        // Function to update cart dropdown content
        function updateCartDropdown(data) {
            const cartItemsList = document.getElementById('cartItemsList');
            const cartTotalPrice = document.getElementById('cartTotalPrice');
            const emptyCartMessage = document.getElementById('emptyCartMessage');
            const cartDropdownContent = document.getElementById('cartDropdownContent');

            // Update cart count
            updateCartCount(data.count);

            // If cart is empty
            if (data.count === 0) {
                cartDropdownContent.innerHTML = `
                    <div class="text-center py-4" id="emptyCartMessage">
                        <i class="bi bi-cart-x fs-1 text-muted"></i>
                        <p class="mt-2">Ваша корзина пуста</p>
                        <a href="{{ route('index') }}" class="btn btn-sm btn-outline-primary">Начать покупки</a>
                    </div>
                `;
                return;
            }

            // Build cart items HTML
            let cartItemsHtml = `
                <div class="cart-items mb-3" style="max-height: 300px; overflow-y: auto;" id="cartItemsList">
            `;

            data.items.forEach(item => {
                cartItemsHtml += `
                    <div class="d-flex align-items-center mb-2 pb-2 border-bottom cart-item" data-item-id="${item.id}">
                        <img src="${item.image || '/placeholder.svg?height=50&width=40'}"
                             alt="${item.title || 'Книга'}" class="me-2"
                             style="width: 40px; height: 50px; object-fit: cover;">
                        <div class="flex-grow-1">
                            <div class="small fw-bold">${item.title || 'Название книги'}</div>
                            <div class="small text-muted">${item.price || '0.00'} ₽ × ${item.quantity || 1}</div>
                        </div>
                        <button type="button" class="btn btn-sm text-danger remove-cart-item" data-item-id="${item.id}">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                `;
            });

            cartItemsHtml += `</div>`;

            // Add total and buttons
            cartItemsHtml += `
                <div class="d-flex justify-content-between mb-3">
                    <span class="fw-bold">Итого:</span>
                    <span class="fw-bold" id="cartTotalPrice">${data.total} ₽</span>
                </div>
                <div class="d-grid gap-2">
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-primary">Просмотр корзины</a>
                    <a href="{{ route('checkout.index') }}" class="btn btn-primary">Оформить заказ</a>
                </div>
            `;

            // Update the dropdown content
            cartDropdownContent.innerHTML = cartItemsHtml;

            // Add event listeners to new remove buttons
            attachRemoveItemListeners();
        }

        // Function to attach event listeners to remove buttons
        function attachRemoveItemListeners() {
            const removeButtons = document.querySelectorAll('.remove-cart-item');
            removeButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const itemId = this.getAttribute('data-item-id');
                    removeCartItem(itemId);
                });
            });
        }

        // Function to remove item from cart
        window.removeCartItem = function(itemId) {
            fetch(`{{ route('cart.api.destroy') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ id: itemId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart dropdown with new data
                    updateCartDropdown(data);

                    // Trigger custom event for other components to listen to
                    document.dispatchEvent(new CustomEvent('cartUpdated', {
                        detail: {
                            count: data.count,
                            total: data.total,
                            items: data.items
                        }
                    }));
                }
            })
            .catch(error => {
                console.error('Error removing item from cart:', error);
            });
        };

        // Function to add item to cart
        window.addToCart = function(itemId, quantity = 1) {
            fetch(`{{ route('cart.api.add') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ id: itemId, quantity: quantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart dropdown with new data
                    updateCartDropdown(data);

                    // Trigger custom event for other components to listen to
                    document.dispatchEvent(new CustomEvent('cartUpdated', {
                        detail: {
                            count: data.count,
                            total: data.total,
                            items: data.items
                        }
                    }));
                }
            })
            .catch(error => {
                console.error('Error adding item to cart:', error);
            });
        };

        // Attach event listeners to existing remove buttons
        attachRemoveItemListeners();
    });
    </script>
@endpush