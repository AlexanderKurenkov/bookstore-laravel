<x-layout>
    <div class="row">
        <div class="col-12 mb-4">
            <h1>Оформление заказа</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">Главная</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Корзина</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Оформление заказа</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- // TODO --}}
    {{-- <form action="{{ route('orders.store') }}" method="POST" id="checkout-form"> --}}
    @if(session()->has('cart') && count(session('cart')) > 0)
        <form action="{{ route('index') }}" method="POST" id="checkout-form">
            @csrf
            <div class="row">
                <!-- Checkout Form -->
                <div class="col-lg-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">1. Контактная информация</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label">Имя <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                        id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label">Фамилия <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                        id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Телефон <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" value="{{ old('phone') }}"
                                        placeholder="+7 (___) ___-__-__" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">2. Адрес доставки</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="address" class="form-label">Адрес <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror"
                                        id="address" name="address" value="{{ old('address') }}"
                                        placeholder="Улица, дом, квартира" required>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="city" class="form-label">Город <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                        id="city" name="city" value="{{ old('city') }}" required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="region" class="form-label">Область/Край</label>
                                    <input type="text" class="form-control @error('region') is-invalid @enderror"
                                        id="region" name="region" value="{{ old('region') }}">
                                    @error('region')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label for="postal_code" class="form-label">Индекс <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror"
                                        id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required>
                                    @error('postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="delivery_comment" class="form-label">Комментарий к доставке</label>
                                    <textarea class="form-control" id="delivery_comment" name="delivery_comment"
                                            rows="2" placeholder="Дополнительная информация для курьера">{{ old('delivery_comment') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">3. Способ доставки</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-check mb-3 p-0">
                                        <div class="card mb-2 delivery-option">
                                            <div class="card-body p-3">
                                                <input class="form-check-input" type="radio" name="delivery_method"
                                                    id="delivery_standard" value="standard"
                                                    {{ old('delivery_method') == 'standard' ? 'checked' : '' }} checked>
                                                <label class="form-check-label ms-2" for="delivery_standard">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <strong>Стандартная доставка</strong>
                                                            <p class="text-muted mb-0">2-4 рабочих дня</p>
                                                        </div>
                                                        <div class="text-end">
                                                            @php
                                                                $cartTotal = session('cart_total') ?? 0;
                                                                $standardShipping = $cartTotal >= 2000 ? 0 : 300;
                                                            @endphp
                                                            <span class="fw-bold">{{ $standardShipping > 0 ? number_format($standardShipping, 2) . ' ₽' : 'Бесплатно' }}</span>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="card delivery-option">
                                            <div class="card-body p-3">
                                                <input class="form-check-input" type="radio" name="delivery_method"
                                                    id="delivery_pickup" value="pickup"
                                                    {{ old('delivery_method') == 'pickup' ? 'checked' : '' }}>
                                                <label class="form-check-label ms-2" for="delivery_pickup">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <strong>Самовывоз из магазина</strong>
                                                            <p class="text-muted mb-0">Доступно на следующий день после оформления заказа</p>
                                                        </div>
                                                        <div class="text-end">
                                                            <span class="fw-bold">Бесплатно</span>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">4. Способ оплаты</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-check mb-3 p-0">
                                        <div class="card mb-2 payment-option">
                                            <div class="card-body p-3">
                                                <input class="form-check-input" type="radio" name="payment_method"
                                                    id="payment_card" value="card"
                                                    {{ old('payment_method') == 'card' ? 'checked' : '' }} checked>
                                                <label class="form-check-label ms-2" for="payment_card">
                                                    <div class="d-flex align-items-center">
                                                        <div>
                                                            <strong>Банковская карта</strong>
                                                            <p class="text-muted mb-0">Visa, MasterCard, МИР</p>
                                                        </div>
                                                        <div class="ms-auto">
                                                            <img src="/placeholder.svg?height=30&width=120" alt="Payment Cards" height="30">
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>

                                        <div id="card-details" class="card mb-3 payment-details">
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <label for="card_number" class="form-label">Номер карты <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control @error('card_number') is-invalid @enderror"
                                                            id="card_number" name="card_number" placeholder="XXXX XXXX XXXX XXXX">
                                                        @error('card_number')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="card_expiry" class="form-label">Срок действия <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control @error('card_expiry') is-invalid @enderror"
                                                            id="card_expiry" name="card_expiry" placeholder="ММ/ГГ">
                                                        @error('card_expiry')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="card_cvv" class="form-label">CVV/CVC <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control @error('card_cvv') is-invalid @enderror"
                                                            id="card_cvv" name="card_cvv" placeholder="XXX">
                                                        @error('card_cvv')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="card_holder" class="form-label">Имя владельца карты <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control @error('card_holder') is-invalid @enderror"
                                                            id="card_holder" name="card_holder" placeholder="Как указано на карте">
                                                        @error('card_holder')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card mb-2 payment-option">
                                            <div class="card-body p-3">
                                                <input class="form-check-input" type="radio" name="payment_method"
                                                    id="payment_cash" value="cash"
                                                    {{ old('payment_method') == 'cash' ? 'checked' : '' }}>
                                                <label class="form-check-label ms-2" for="payment_cash">
                                                    <div class="d-flex align-items-center">
                                                        <div>
                                                            <strong>Наличными при получении</strong>
                                                            <p class="text-muted mb-0">Оплата курьеру или в пункте выдачи</p>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Ваш заказ</h5>
                        </div>
                        <div class="card-body">
                            <div class="order-items mb-3">
                                @if(session()->has('cart') && count(session('cart')) > 0)
                                    @foreach(session('cart') as $item)
                                        <div class="d-flex justify-content-between mb-2">
                                            <div>
                                                <span class="fw-medium">{{ $item['title'] ?? 'Книга' }}</span>
                                                <span class="text-muted"> × {{ $item['quantity'] ?? 1 }}</span>
                                            </div>
                                            <span>{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 2) }} ₽</span>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-3">
                                        <p class="mb-0">Корзина пуста</p>
                                    </div>
                                @endif
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Товары</span>
                                <span>{{ number_format(session('cart_total') ?? 0, 2) }} ₽</span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Доставка</span>
                                <span id="shipping-cost">
                                    @php
                                        $cartTotal = session('cart_total') ?? 0;
                                        $standardShipping = $cartTotal >= 2000 ? 0 : 300;
                                    @endphp
                                    {{ $standardShipping > 0 ? number_format($standardShipping, 2) . ' ₽' : 'Бесплатно' }}
                                </span>
                            </div>

                            @if(session('promo_discount'))
                                <div class="d-flex justify-content-between mb-2 text-success">
                                    <span>Скидка по промокоду</span>
                                    <span>-{{ number_format((session('cart_total') ?? 0) * (session('promo_discount') / 100), 2) }} ₽</span>
                                </div>
                            @endif

                            <hr>

                            <div class="d-flex justify-content-between mb-3 fw-bold">
                                <span>Итого</span>
                                @php
                                    $discount = session('promo_discount') ? (session('cart_total') ?? 0) * (session('promo_discount') / 100) : 0;
                                    $total = (session('cart_total') ?? 0) - $discount + $standardShipping;
                                @endphp
                                <span class="fs-5" id="total-amount">{{ number_format($total, 2) }} ₽</span>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    {{-- TODO --}}
                                    Я согласен с <a href="{{ route('index') }}" target="_blank">условиями использования</a> и <a href="{{ route('index') }}" target="_blank">политикой конфиденциальности</a>
                                    {{-- Я согласен с <a href="{{ route('terms') }}" target="_blank">условиями использования</a> и <a href="{{ route('privacy') }}" target="_blank">политикой конфиденциальности</a> --}}
                                </label>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">Оформить заказ</button>
                                <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">Вернуться в корзину</a>
                            </div>

                            <div class="mt-3 text-center">
                                <div class="d-flex justify-content-center mb-2">
                                    <i class="bi bi-shield-lock fs-4 text-success me-2"></i>
                                    <span class="fw-medium">Безопасная оплата</span>
                                </div>
                                <small class="text-muted">Ваши данные защищены и никогда не будут переданы третьим лицам</small>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="mb-3">Нужна помощь?</h6>
                            <p class="small mb-2"><i class="bi bi-telephone me-2"></i>+7 (800) 123-45-67</p>
                            <p class="small mb-0"><i class="bi bi-envelope me-2"></i>support@bookstore.ru</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @else
        <!-- Empty Cart -->
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-cart-x fs-1 text-muted mb-3"></i>
                <h3>Ваша корзина пуста</h3>
                <p class="text-muted mb-4">Похоже, вы еще не добавили товары в корзину, чтобы оформить заказ</p>
                <a href="{{ route('catalog.index') }}" class="btn btn-primary">
                    Начать покупки
                </a>
            </div>
        </div>
    @endif


    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
        });

    </script>
    @endpush
</x-layout>
