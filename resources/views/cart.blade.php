<x-layout>
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Корзина</h1>

            @if(session()->has('cart') && count(session('cart')) > 0)
                <div class="row">
                    <!-- Cart Items -->
                    <div class="col-lg-8 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col" class="ps-4">Товар</th>
                                                <th scope="col" class="text-center">Цена</th>
                                                <th scope="col" class="text-center">Количество</th>
                                                <th scope="col" class="text-center">Сумма</th>
                                                <th scope="col" class="text-center pe-4">Действия</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(session('cart') as $item)
                                                <tr>
                                                    <!-- Product -->
                                                    <td class="ps-4">
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $item['image'] ?? '/placeholder.svg?height=80&width=60' }}"
                                                                 alt="{{ $item['title'] ?? 'Книга' }}"
                                                                 class="me-3" style="width: 60px; height: 80px; object-fit: cover;">
                                                            <div>
                                                                <h6 class="mb-1">{{ $item['title'] ?? 'Название книги' }}</h6>
                                                                <p class="text-muted small mb-0">{{ $item['author'] ?? 'Автор книги' }}</p>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <!-- Price -->
                                                    <td class="text-center align-middle">
                                                        {{ number_format($item['price'] ?? 0, 2) }} ₽
                                                    </td>

                                                    <!-- Quantity -->
                                                    <td class="text-center align-middle">
                                                        <form action="{{ route('cart.item.update', $item['id'] ?? 1) }}" method="POST" class="d-inline-block">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div class="input-group input-group-sm" style="width: 120px;">
                                                                <button class="btn btn-outline-secondary" type="button"
                                                                        onclick="this.parentNode.querySelector('input').stepDown(); this.closest('form').submit();">
                                                                    <i class="bi bi-dash"></i>
                                                                </button>
                                                                <input type="number" class="form-control text-center" name="quantity"
                                                                       value="{{ $item['quantity'] ?? 1 }}" min="1" max="99">
                                                                <button class="btn btn-outline-secondary" type="button"
                                                                        onclick="this.parentNode.querySelector('input').stepUp(); this.closest('form').submit();">
                                                                    <i class="bi bi-plus"></i>
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </td>

                                                    <!-- Subtotal -->
                                                    <td class="text-center align-middle fw-bold">
                                                        {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 2) }} ₽
                                                    </td>

                                                    <!-- Actions -->
                                                    <td class="text-center align-middle pe-4">
                                                        <form action="{{ route('cart.item.destroy', $item['id'] ?? 1) }}" method="POST" class="d-inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer bg-white d-flex justify-content-between py-3">
                                <a href="{{ route('index') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-arrow-left me-2"></i>Продолжить покупки
                                </a>
                                <form action="{{ route('cart.clear') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bi bi-trash me-2"></i>Очистить корзину
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="col-lg-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Сумма заказа</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Товары ({{ array_sum(array_column(session('cart'), 'quantity')) }})</span>
                                    <span>{{ number_format(session('cart_total') ?? 0, 2) }} ₽</span>
                                </div>

                                <div class="d-flex justify-content-between mb-3">
                                    <span>Доставка</span>
                                    @php
                                        $cartTotal = session('cart_total') ?? 0;
                                        $shippingCost = $cartTotal >= 2000 ? 0 : 300;
                                    @endphp
                                    <span>{{ $shippingCost > 0 ? number_format($shippingCost, 2) . ' ₽' : 'Бесплатно' }}</span>
                                </div>

                                <!-- Promo Code -->
                                <div class="mb-3">
                                    <form action="{{ route('cart.index') }}" method="POST">
                                    {{-- <form action="{{ route('cart.promo') }}" method="POST"> --}}
                                        @csrf
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" placeholder="Промокод" name="promo_code"
                                                   value="{{ session('promo_code') ?? '' }}">
                                            <button class="btn btn-outline-secondary" type="submit">Применить</button>
                                        </div>
                                        @if(session('promo_discount'))
                                            <div class="text-success small">
                                                <i class="bi bi-check-circle me-1"></i>Промокод применен: скидка {{ session('promo_discount') }}%
                                            </div>
                                        @endif
                                        @if(session('promo_error'))
                                            <div class="text-danger small">
                                                <i class="bi bi-exclamation-circle me-1"></i>{{ session('promo_error') }}
                                            </div>
                                        @endif
                                    </form>
                                </div>

                                @if(session('promo_discount'))
                                    <div class="d-flex justify-content-between mb-3 text-success">
                                        <span>Скидка по промокоду</span>
                                        <span>-{{ number_format((session('cart_total') ?? 0) * (session('promo_discount') / 100), 2) }} ₽</span>
                                    </div>
                                @endif

                                <hr>

                                <div class="d-flex justify-content-between mb-3 fw-bold">
                                    <span>Итого</span>
                                    @php
                                        $discount = session('promo_discount') ? (session('cart_total') ?? 0) * (session('promo_discount') / 100) : 0;
                                        $total = (session('cart_total') ?? 0) - $discount + $shippingCost;
                                    @endphp
                                    <span class="fs-5">{{ number_format($total, 2) }} ₽</span>
                                </div>

                                <div class="d-grid">
                                    <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg">
                                        Оформить заказ
                                    </a>
                                </div>

                                <div class="mt-3 small text-center text-muted">
                                    <i class="bi bi-shield-lock me-1"></i>Безопасная оплата
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Info -->
                        <div class="card mt-3 shadow-sm">
                            <div class="card-body">
                                <h6 class="mb-3"><i class="bi bi-truck me-2"></i>Информация о доставке</h6>
                                <p class="small mb-1">Бесплатная доставка при заказе от 2000 ₽</p>
                                <p class="small mb-1">Стандартная доставка: 2-4 рабочих дня</p>
                                <p class="small mb-0">Экспресс-доставка: 1-2 рабочих дня (за дополнительную плату)</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty Cart -->
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-cart-x fs-1 text-muted mb-3"></i>
                        <h3>Ваша корзина пуста</h3>
                        <p class="text-muted mb-4">Похоже, вы еще не добавили товары в корзину</p>
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            Начать покупки
                        </a>
                    </div>
                </div>
            @endif

            <!-- Recommended Books -->
            @if(session()->has('cart') && count(session('cart')) > 0)
                <div class="mt-5">
                    <h3 class="mb-4">Вам также может понравиться</h3>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                        @for($i = 0; $i < 4; $i++)
                            <div class="col">
                                <div class="card h-100 book-card">
                                    <img src="/placeholder.svg?height=300&width=200" class="card-img-top" alt="Рекомендуемая книга" style="height: 300px; object-fit: cover;">
                                    <div class="card-body">
                                        <h5 class="card-title">Рекомендуемая книга {{ $i + 1 }}</h5>
                                        <p class="card-text text-muted">Автор книги</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fs-5 fw-bold text-primary">{{ (400 + $i * 50) . '.00 ₽' }}</span>
                                            <div class="d-flex">
                                                <button type="button" class="btn btn-sm btn-primary me-2">
                                                    <i class="bi bi-cart-plus"></i>
                                                </button>
                                                <a href="#" class="btn btn-sm btn-outline-secondary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layout>
