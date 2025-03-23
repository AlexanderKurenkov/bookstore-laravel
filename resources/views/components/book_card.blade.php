@props(['id', 'title', 'author', 'price', 'imagePath', 'publisher' => null, 'publication_year' => null])

<div class="col-md-3 mb-4">
    <div class="card h-100 book-card">
        <!-- Book Image with Fixed Height -->
        <div class="book-image-container">
            <a href="{{ route('catalog.book', $id) }}">
                <img src="{{ $imagePath }}" class="card-img-top book-image" alt="{{ $title }}">
            </a>
            <!-- Favorite Button (Absolute Position) -->

                <button type="button"
                        id="favoriteBtn{{ $id }}"
                        class="btn btn-sm btn-light rounded-circle position-absolute top-0 end-0 m-2 favorite-btn"
                        onclick="toggleFavorite({{ $id }})"
                        href="#" data-bs-toggle="modal" data-bs-target="#favoritesModal">
                    <i id="favoriteIcon{{ $id }}" class="bi {{ auth()->check() && auth()->user()->favorites->contains($id) ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
                </button>
            </a>
        </div>

        <div class="card-body d-flex flex-column justify-content-between">
            <!-- Book Info -->
            <div>
                <a href="{{ route('catalog.book', $id) }}" class="text-dark text-decoration-none">
                    <h5 class="card-title fw-bold text-truncate" title="{{ $title }}">{{ $title }}</h5>
                </a>
                <p class="card-text text-muted mb-1">{{ $author }}</p>
                @if($publisher)
                    <p class="card-text small text-muted mb-1">{{ $publisher }}</p>
                @endif
                @if($publication_year)
                    <p class="card-text small text-muted mb-2">{{ $publication_year }}</p>
                @endif
            </div>

            <!-- Price and Actions -->
            <div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <p class="fw-bold fs-5 text-primary mb-0">{{ number_format($price, 2) }} RUB</p>
                </div>
                <div class="d-flex">
                    <button id="addToCartBtn{{ $id }}"
                        class="btn btn-outline-primary flex-grow-1"
                        onclick="addToCart({{ $id }})"
                        data-href="{{ route('catalog.book', $id) }}"
                    >
                        <i class="bi bi-cart-plus me-1"></i>{{__('Add to cart')}}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function addToCart(id) {
        let button = document.getElementById('addToCartBtn' + id);

        // Check if the button is clicked for the first time
        if (!button.classList.contains('added')) {
            // Change the button text and class when clicked for the first time
            button.innerHTML = '<i class="bi bi-bag-check me-1"></i>Оформить';
            button.classList.remove('btn-outline-primary');
            button.classList.add('btn-primary');
            button.classList.add('added');  // Mark the button as added

            // Add loading state
            button.disabled = true;
            const originalText = button.innerHTML;
            button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Добавляем';

            // Send a request to add the item to the cart
            let data = {
                "bookId": id,
                "quantity": 1
            };

            // Send the AJAX request using fetch
            fetch("{{ route('cart.item.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content') // CSRF token
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                // Remove loading state
                button.disabled = false;
                button.innerHTML = '<i class="bi bi-bag-check me-1"></i>Оформить';

                // Update cart count in navbar if needed
                updateCartCount(data.cartCount);
            })
            .catch(error => {
                console.error('Error:', error);
                // Remove loading state and restore original state on error
                button.disabled = false;
                button.innerHTML = originalText;
                button.classList.remove('btn-primary', 'added');
                button.classList.add('btn-outline-primary');
            });
        } else {
            // On the second click, navigate to the item page
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

    // Function to update cart count in navbar (implement if needed)
    function updateCartCount(count) {
        const cartBadge = document.querySelector('.cart-badge');
        if (cartBadge) {
            cartBadge.textContent = count;
            cartBadge.style.display = count > 0 ? 'inline-block' : 'none';
        }
    }
</script>
@endpush
@push('head')
<style>
    .book-image-container {
        position: relative;
        height: 450px; /* Fixed height for all book images */
        overflow: hidden;
    }

    .book-image {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Maintain aspect ratio and cover container */
        transition: transform 0.3s ease;
    }

    .book-card:hover .book-image {
        transform: scale(1.05);
    }

    .favorite-btn {
        opacity: 0.8;
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .favorite-btn:hover {
        opacity: 1;
        transform: scale(1.1);
    }

    .book-card {
        border: 1px solid rgba(0, 0, 0, 0.125);
        transition: all 0.3s ease;
    }

    .book-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
</style>
@endpush