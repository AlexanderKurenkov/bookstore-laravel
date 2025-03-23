@props(['id', 'title', 'author', 'price', 'imagePath'])

<div class="col-md-3 mb-4">
    <div class="card h-100 d-flex">
        <div class="d-flex justify-content-end" style="height: 100%;">
            <a href="{{ route('catalog.book', $id) }}">
                <img src="{{ $imagePath }}" class="card-img-top" alt="{{ $title }}">
            </a>
        </div>

        <div class="card-body d-flex flex-column justify-content-end">
            <div>
                <a href="{{ route('catalog.book', $id) }}" class="text-dark text-decoration-none">
                    <h5 class="card-title fw-bold">{{ $title }}</h5>
                </a>

                <p class="card-text text-muted">{{ $author }}</p>
            </div>
            <div>
                <p class="fw-bold">{{ number_format($price, 2) }} RUB</p>
                    <a id="addToCartBtn{{ $id }}"
                        href="javascript:void(0)"
                        class="btn btn-outline-secondary"
                        onclick="addToCart({{ $id }})"
                        data-href="{{ route('catalog.book', $id) }}"
                    >
                        {{__('Add to cart')}}
                    </a>

            </div>
        </div>
    </div>
</div>

<script>
    function addToCart(id) {
        let button = document.getElementById('addToCartBtn' + id);


        // Check if the button is clicked for the first time
        if (!button.classList.contains('added')) {
            // Change the button text and class when clicked for the first time
            button.textContent = "{{__('Checkout')}}";
            button.classList.remove('btn-outline-secondary');
            button.classList.add('btn-secondary');
            button.classList.add('added');  // Mark the button as added

            // Send a request to add the item to the cart
            let data = {
                "bookId": id,
                "quantity": 1
            };

            // console.log(`csrf-token: ${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}`);

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
            .then(data => console.log(data.message)) // Handle the response if needed (e.g., show a success message)
            .catch(error => console.error('Error:', error));
        } else {
            // On the second click, navigate to the item page
            window.location.href = button.getAttribute('data-href');
        }
    }
</script>
