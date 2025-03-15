@props(['id', 'title', 'author', 'price', 'imagePath'])

<div class="col-md-3 mb-4">
    <div class="card h-100 d-flex">
        <div class="d-flex justify-content-end" style="height: 100%;">
            <a href="{{ route('catalog.show', $id) }}">
                <img src="{{ $imagePath }}" class="card-img-top" alt="{{ $title }}">
            </a>
        </div>

        <div class="card-body d-flex flex-column justify-content-end">
            <div>
                <a href="{{ route('catalog.show', $id) }}" class="text-dark text-decoration-none">
                    <h5 class="card-title fw-bold">{{ $title }}</h5>
                </a>

                <p class="card-text text-muted">{{ $author }}</p>
            </div>
            <div>
                <p class="fw-bold">{{ number_format($price, 2) }} RUB</p>
                    <a id="addToCartBtn{{ $id }}"
                        href="{{ route('catalog.show', $id) }}"
                        class="btn btn-outline-secondary"
                        onclick="addToCart({{ $id }})"
                    >
                    {{__('Add to cart')}}
                    </a>

            </div>
        </div>
    </div>
</div>

<script>
    function addToCart(id) {
        // Change the button text and class when clicked
        var button = document.getElementById('addToCartBtn' + id);
        button.textContent = "{{__('Checkout')}}";
        button.classList.remove('btn-outline-secondary');
        button.classList.add('btn-secondary');

        // Optionally, you can also send an AJAX request to add the item to the cart
        // For example, using fetch or axios
    }
</script>