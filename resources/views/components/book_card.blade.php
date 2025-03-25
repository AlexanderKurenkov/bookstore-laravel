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
                @php
                    $cart = session('cart', []);
                    $bookInCart = isset($cart[$id]); // Check if book exists in cart
                @endphp
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <p class="fw-bold fs-5 text-primary mb-0">{{ number_format($price, 2) }} RUB</p>
                </div>
                <div>
                    <button id="addToCartBtn{{ $id }}"
                            class="btn flex-grow-1 {{ $bookInCart ? 'btn-primary added' : 'btn-outline-primary' }}"
                            onclick="addToCartButtonClicked({{ $id }})"
                            data-href="{{ route('checkout.index') }}">

                        <i class="bi {{ $bookInCart ? 'bi-bag-check' : 'bi-cart-plus me-1' }}"></i>
                        {{ $bookInCart ? ' Оформить' : ' Добавить' }}
                    </button>
                </div>
                {{-- <div class="d-flex">
                    <button id="addToCartBtn{{ $id }}"
                        class="btn btn-outline-primary flex-grow-1"
                        onclick="addToCartButtonClicked({{ $id }})"
                        data-href="{{ route('checkout.index') }}"
                    >
                        <i class="bi bi-cart-plus me-1"></i>Добавить в корзину
                    </button>
                </div> --}}
            </div>
        </div>
    </div>
</div>

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