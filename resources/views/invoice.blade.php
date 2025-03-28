<x-layout>
    @php
        $user = Auth::user();
        $delivery = $order->deliveryDetail;
        $paymentMethod = $order->payments()
            ->where('payment_status', 'success')
            ->latest()
            ->first()?->payment_method;
        $cardLastFour = $order->payments()
            ->where('payment_status', 'success')
            ->where('payment_method', 'card')
            ->latest()
            ->first()?->cardPayment?->card_last_four;
    @endphp
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Success Message -->
                <div class="text-center mb-4">
                    <div class="d-inline-flex justify-content-center align-items-center bg-success bg-opacity-10 rounded-circle p-3 mb-3">
                        <i class="bi bi-check-circle-fill text-success fs-1"></i>
                    </div>
                    <h1 class="mb-2">Заказ успешно оформлен!</h1>
                    <p class="text-muted">Спасибо за покупку!</p>
                </div>

                <!-- Order Information Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0">Информация о заказе</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <h6 class="text-muted mb-2">Номер заказа</h6>
                                <p class="mb-0 fw-bold">ORD-{{ str_pad($order->id, 6, "0", STR_PAD_LEFT) }}</p>
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

                        <!-- Replace the entire order items section with dynamic data -->
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
                                    @if(isset($order->books) && count($order->books) > 0)
                                        @foreach($order->books as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $item->image_path }}"
                                                             alt="{{ $item->title ?? 'Книга' }}" class="me-3"
                                                             style="width: 45px; height: 60px; object-fit: cover;">
                                                        <div>
                                                            <h6 class="mb-0">{{ $item->title }}</h6>
                                                            <small class="text-muted">{{ $item->author }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">{{ number_format($item->price, 2) }} ₽</td>
                                                <td class="text-center">{{ $item->pivot->quantity }}</td>
                                                <td class="text-end">{{ number_format($item->price * $item->pivot->quantity, 2) }} ₽</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center py-3">Информация о товарах недоступна</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- Order Summary -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="mb-3">Сумма заказа</h6>
                                        <hr>
                                        <div class="d-flex justify-content-between fw-bold">
                                            <span>Итого:</span>
                                            <span class="fs-5">{{ number_format($order->order_total, 2) }} ₽</span>
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
                                <p class="mb-1"><strong>Ожидаемая дата доставки:</strong> {{ $order->expected_delivery ?? now()->addDays(3)->format('d.m.Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-3">Отслеживание заказа</h6>
                                <p class="mb-3">Вы можете отслеживать статус вашего заказа в личном кабинете по номеру заказа.</p>
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
                                <a href="{{ route('terms') }}" class="btn btn-outline-secondary">
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
                </div>
            </div>
        </div>
    </div>

    @push('head')
    <style>
    @media print {
      .btn, nav, footer {
          display: none !important;
      }

      .card {
          border: none !important;
          box-shadow: none !important;
      }

      .container {
          width: 100% !important;
          max-width: 100% !important;
      }

      body {
          font-size: 12pt;
      }

      h1 {
          font-size: 18pt;
      }

      h5, h6 {
          font-size: 14pt;
      }
    }
    </style>
    @endpush
</x-layout>
