<x-layout>
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Success Message -->
            <div class="text-center mb-4">
                <div class="d-inline-flex justify-content-center align-items-center bg-success bg-opacity-10 rounded-circle p-3 mb-3">
                    <i class="bi bi-check-circle-fill text-success fs-1"></i>
                </div>
                <h1 class="mb-2">Заказ успешно оформлен!</h1>
                <p class="text-muted">Спасибо за покупку! Подтверждение заказа отправлено на ваш email.</p>
            </div>

            <!-- Order Information Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0">Информация о заказе</h5>
                    {{-- TODO --}}
                    {{-- <div>
                        <button class="btn btn-sm btn-outline-secondary me-2" onclick="window.print()">
                            <i class="bi bi-printer me-1"></i>Печать
                        </button>
                        <a href="{{ route('orders.download', $order->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-download me-1"></i>Скачать PDF
                        </a>
                    </div> --}}
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h6 class="text-muted mb-2">Номер заказа</h6>
                            <p class="mb-0 fw-bold">{{ $order->order_number ?? 'ORD-' . rand(10000, 99999) }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Дата заказа</h6>
                            <p class="mb-0">{{ $order->created_at ?? now()->format('d.m.Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h6 class="text-muted mb-2">Статус оплаты</h6>
                            <span class="badge bg-success">Оплачено</span>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Статус заказа</h6>
                            <span class="badge bg-primary">Обрабатывается</span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Customer Information -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <h6 class="mb-3">Информация о покупателе</h6>
                            <p class="mb-1">{{ $order->customer_name ?? 'Иван Иванов' }}</p>
                            <p class="mb-1">{{ $order->email ?? 'ivan@example.com' }}</p>
                            <p class="mb-0">{{ $order->phone ?? '+7 (999) 123-45-67' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Адрес доставки</h6>
                            <p class="mb-1">{{ $order->address ?? 'ул. Примерная, д. 123, кв. 45' }}</p>
                            <p class="mb-1">{{ $order->city ?? 'Москва' }}, {{ $order->region ?? 'Московская область' }}</p>
                            <p class="mb-0">{{ $order->postal_code ?? '123456' }}</p>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Order Items -->
                    <h6 class="mb-3">Товары</h6>
                    <div class="table-responsive mb-4">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Товар</th>
                                    <th class="text-center">Цена</th>
                                    <th class="text-center">Кол-во</th>
                                    <th class="text-end">Сумма</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($order->items) && count($order->items) > 0)
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $item->image ?? '/placeholder.svg?height=60&width=45' }}"
                                                         alt="{{ $item->title ?? 'Книга' }}" class="me-3"
                                                         style="width: 45px; height: 60px; object-fit: cover;">
                                                    <div>
                                                        <h6 class="mb-0">{{ $item->title ?? 'Название книги' }}</h6>
                                                        <small class="text-muted">{{ $item->author ?? 'Автор книги' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ number_format($item->price ?? 450, 2) }} ₽</td>
                                            <td class="text-center">{{ $item->quantity ?? 1 }}</td>
                                            <td class="text-end">{{ number_format(($item->price ?? 450) * ($item->quantity ?? 1), 2) }} ₽</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <!-- Sample data for preview -->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="/placeholder.svg?height=60&width=45" alt="Книга" class="me-3"
                                                     style="width: 45px; height: 60px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-0">Преступление и наказание</h6>
                                                    <small class="text-muted">Федор Достоевский</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">350.00 ₽</td>
                                        <td class="text-center">1</td>
                                        <td class="text-end">350.00 ₽</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="/placeholder.svg?height=60&width=45" alt="Книга" class="me-3"
                                                     style="width: 45px; height: 60px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-0">Мастер и Маргарита</h6>
                                                    <small class="text-muted">Михаил Булгаков</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">420.00 ₽</td>
                                        <td class="text-center">2</td>
                                        <td class="text-end">840.00 ₽</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Order Summary -->
                    <div class="row">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="mb-3">Информация об оплате</h6>
                                    <div class="row mb-2">
                                        <div class="col-6 text-muted">Способ оплаты:</div>
                                        <div class="col-6 text-end">
                                            {{ $order->payment_method ?? 'Банковская карта' }}
                                            @if(($order->payment_method ?? '') == 'Банковская карта')
                                                <br><small class="text-muted">**** **** **** {{ $order->card_last4 ?? '1234' }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6 text-muted">Транзакция:</div>
                                        <div class="col-6 text-end">{{ $order->transaction_id ?? 'TXN' . rand(100000, 999999) }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 text-muted">Дата оплаты:</div>
                                        <div class="col-6 text-end">{{ $order->paid_at ?? now()->format('d.m.Y') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="mb-3">Сумма заказа</h6>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Товары:</span>
                                        <span>{{ number_format($order->subtotal ?? 1190, 2) }} ₽</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Доставка:</span>
                                        @php
                                            $shippingCost = $order->shipping_cost ?? 0;
                                        @endphp
                                        <span>{{ $shippingCost > 0 ? number_format($shippingCost, 2) . ' ₽' : 'Бесплатно' }}</span>
                                    </div>
                                    @if(($order->discount ?? 0) > 0)
                                        <div class="d-flex justify-content-between mb-2 text-success">
                                            <span>Скидка:</span>
                                            <span>-{{ number_format($order->discount ?? 0, 2) }} ₽</span>
                                        </div>
                                    @endif
                                    <hr>
                                    <div class="d-flex justify-content-between fw-bold">
                                        <span>Итого:</span>
                                        <span class="fs-5">{{ number_format($order->total ?? 1190, 2) }} ₽</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <h6 class="mb-3">Информация о доставке</h6>
                            <p class="mb-1"><strong>Способ доставки:</strong> {{ $order->delivery_method ?? 'Стандартная доставка' }}</p>
                            <p class="mb-1"><strong>Ожидаемая дата доставки:</strong> {{ $order->expected_delivery ?? now()->addDays(3)->format('d.m.Y') }}</p>
                            @if(isset($order->tracking_number))
                                <p class="mb-0"><strong>Номер отслеживания:</strong> {{ $order->tracking_number }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Отслеживание заказа</h6>
                            <p class="mb-3">Вы можете отслеживать статус вашего заказа в личном кабинете или по номеру заказа.</p>
                            {{-- TODO --}}
                            {{-- <a href="{{ route('orders.track', $order->id ?? 1) }}" class="btn btn-primary">
                                <i class="bi bi-truck me-2"></i>Отследить заказ
                            </a> --}}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="mb-3">Что дальше?</h6>
                    <div class="row">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                        <i class="bi bi-envelope text-primary fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Подтверждение по email</h6>
                                    <p class="text-muted mb-0 small">Мы отправили подтверждение заказа на ваш email.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                        <i class="bi bi-box-seam text-primary fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Подготовка заказа</h6>
                                    <p class="text-muted mb-0 small">Мы начали собирать ваш заказ и скоро отправим его.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                        <i class="bi bi-truck text-primary fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Доставка</h6>
                                    <p class="text-muted mb-0 small">Вы получите уведомление, когда заказ будет отправлен.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Support -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h6 class="mb-3">Нужна помощь?</h6>
                            <p class="mb-3">Если у вас возникли вопросы по заказу, свяжитесь с нашей службой поддержки:</p>
                            <p class="mb-1"><i class="bi bi-telephone me-2"></i>+7 (800) 123-45-67</p>
                            <p class="mb-0"><i class="bi bi-envelope me-2"></i>support@knigochei.site</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Политика возврата</h6>
                            <p class="mb-3">Вы можете вернуть товар в течение 14 дней с момента получения, если он не соответствует описанию или имеет дефекты.</p>
                            {{-- TODO --}}
                            <a href="{{ route('index') }}" class="btn btn-outline-secondary">
                            {{-- <a href="{{ route('returns') }}" class="btn btn-outline-secondary"> --}}
                                Подробнее о возврате
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-2"></i>Продолжить покупки
                </a>
                {{-- TODO --}}
                {{-- <a href="{{ route('orders.index') }}" class="btn btn-primary">
                    Мои заказы<i class="bi bi-arrow-right ms-2"></i>
                </a> --}}
            </div>
        </div>
    </div>
</x-layout>
