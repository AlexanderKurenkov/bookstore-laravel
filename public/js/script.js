document.addEventListener('DOMContentLoaded', function () {
    if (window.location.pathname.match(/^\/catalog\/book\/\d+$/)) {
        initBookReviewFeatures();
    }
    else if (window.location.pathname.match(/^\/checkout$/)) {
        checkout();
    }
});

function checkout() {
    // Handle payment method selection
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const cardDetails = document.getElementById('card-details');

    paymentMethods.forEach(method => {
        method.addEventListener('change', function () {
            if (this.value === 'card') {
                cardDetails.style.display = 'block';
            } else {
                cardDetails.style.display = 'none';
            }
        });
    });

    // Handle delivery method selection and update shipping cost
    const deliveryMethods = document.querySelectorAll('input[name="delivery_method"]');
    const shippingCostElement = document.getElementById('shipping-cost');
    const totalAmountElement = document.getElementById('total-amount');

    deliveryMethods.forEach(method => {
        method.addEventListener('change', function () {
            let shippingCost = 0;
            let shippingText = 'Бесплатно';

            if (this.value === 'standard') {
                // @php
                //     $cartTotal = session('cart_total') ?? 0;
                //     $standardShipping = $cartTotal >= 2000 ? 0 : 300;
                // @endphp
                shippingCost = 300;
                shippingText = shippingCost > 0 ? shippingCost.toFixed(2) + ' ₽' : 'Бесплатно';
            } else if (this.value === 'express') {
                shippingCost = 500;
                shippingText = '500.00 ₽';
            } else if (this.value === 'pickup') {
                shippingCost = 0;
                shippingText = 'Бесплатно';
            }

            shippingCostElement.textContent = shippingText;

            // Update total
            // @php
            //     $cartTotal = session('cart_total') ?? 0;
            //     $discount = session('promo_discount') ? $cartTotal * (session('promo_discount') / 100) : 0;
            //     $subtotal = $cartTotal - $discount;
            // @endphp
            //TODO retrieve $cartTotal via fetch call
            const cartTotal = 1000;
            const total = cartTotal + shippingCost;
            totalAmountElement.textContent = total.toFixed(2) + ' ₽';
        });
    });

    // Form validation
    const form = document.getElementById('checkout-form');

    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }

        form.classList.add('was-validated');
    });

    // Input formatting
    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', function (e) {
        let x = e.target.value.replace(/\D/g, '').match(/(\d{0,1})(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})/);
        e.target.value = !x[2] ? x[1] : '+' + x[1] + ' (' + x[2] + ') ' + (x[3] ? x[3] + '-' + x[4] : (x[3] ? x[3] : '')) + (x[5] ? '-' + x[5] : '');
    });

    const cardNumberInput = document.getElementById('card_number');
    cardNumberInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 16) value = value.slice(0, 16);
        let formattedValue = '';
        for (let i = 0; i < value.length; i++) {
            if (i > 0 && i % 4 === 0) formattedValue += ' ';
            formattedValue += value[i];
        }
        e.target.value = formattedValue;
    });

    const cardExpiryInput = document.getElementById('card_expiry');
    cardExpiryInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 4) value = value.slice(0, 4);
        if (value.length > 2) {
            e.target.value = value.slice(0, 2) + '/' + value.slice(2);
        } else {
            e.target.value = value;
        }
    });

    const cardCvvInput = document.getElementById('card_cvv');
    cardCvvInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 3) value = value.slice(0, 3);
        e.target.value = value;
    });
}

function initBookReviewFeatures() {
    const writeReviewBtn = document.getElementById('writeReviewBtn');
    const reviewFormContainer = document.getElementById('reviewFormContainer');
    const cancelReviewBtn = document.getElementById('cancelReviewBtn');
    const ratingStars = document.querySelectorAll('.star-label');

    if (!writeReviewBtn || !reviewFormContainer || !cancelReviewBtn || ratingStars.length === 0) return;

    writeReviewBtn.addEventListener('click', function () {
        reviewFormContainer.style.display = 'block';
        writeReviewBtn.style.display = 'none';
    });

    cancelReviewBtn.addEventListener('click', function () {
        reviewFormContainer.style.display = 'none';
        writeReviewBtn.style.display = 'inline-block';
    });

    ratingStars.forEach(star => {
        star.addEventListener('mouseover', function () {
            const starValue = this.getAttribute('for').replace('star', '');
            ratingStars.forEach((s, index) => {
                s.querySelector('i').classList.toggle('bi-star-fill', index < starValue);
                s.querySelector('i').classList.toggle('bi-star', index >= starValue);
            });
        });

        star.addEventListener('click', function () {
            const starValue = this.getAttribute('for').replace('star', '');
            document.getElementById('star' + starValue).checked = true;
            ratingStars.forEach((s, index) => {
                s.querySelector('i').classList.toggle('bi-star-fill', index < starValue);
                s.querySelector('i').classList.toggle('bi-star', index >= starValue);
            });
        });
    });

    document.querySelector('.rating-stars')?.addEventListener('mouseleave', function () {
        ratingStars.forEach((star, index) => {
            const input = document.getElementById('star' + (index + 1));
            star.querySelector('i').classList.toggle('bi-star-fill', input?.checked);
            star.querySelector('i').classList.toggle('bi-star', !input?.checked);
        });
    });
}

// // Add to cart functionality
// function addToCart(id) {
//     let button = document.getElementById('addToCartBtn' + id);

//     // Check if the button is clicked for the first time
//     if (!button.classList.contains('added')) {
//         // Change the button text and class when clicked for the first time
//         button.textContent = "{{__('Checkout')}}";
//         button.classList.remove('btn-outline-secondary');
//         button.classList.add('btn-secondary');
//         button.classList.add('added');  // Mark the button as added

//         // Send a request to add the item to the cart
//         let data = {
//             "bookId": id,
//             "quantity": 1
//         };

//         // Send the AJAX request using fetch
//         fetch("{{ route('cart.item.store') }}", {
//             method: "POST",
//             headers: {
//                 "Content-Type": "application/json",
//                 "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//             },
//             body: JSON.stringify(data)
//         })
//             .then(response => response.json())
//             .then(data => console.log(data.message))
//             .catch(error => console.error('Error:', error));
//     } else {
//         // On the second click, navigate to the item page
//         window.location.href = button.getAttribute('data-href');
//     }
// }
