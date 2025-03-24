<x-layout>
    <div class="container py-5">
        <div class="row">
            <!-- Profile Header -->
            <div class="col-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                    <i class="bi bi-person-circle text-primary fs-1"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-4">
                                <h1 class="fs-4 mb-1">{{ auth()->user()->name }}</h1>
                                <p class="text-muted mb-0">{{ auth()->user()->email }}</p>
                                <p class="text-muted mb-0">Клиент с {{ auth()->user()->created_at->format('d.m.Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Side Navigation and Content -->
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush" id="profile-tabs" role="tablist">
                            <a class="list-group-item list-group-item-action active d-flex align-items-center"
                               id="orders-tab" data-bs-toggle="list" href="#orders" role="tab" aria-controls="orders">
                                <i class="bi bi-box-seam me-2"></i> История заказов
                            </a>
                            <a class="list-group-item list-group-item-action d-flex align-items-center"
                               id="edit-profile-tab" data-bs-toggle="list" href="#edit-profile" role="tab" aria-controls="edit-profile">
                                <i class="bi bi-person-gear me-2"></i> Редактировать профиль
                            </a>
                            <a class="list-group-item list-group-item-action d-flex align-items-center"
                               id="wishlist-tab" data-bs-toggle="list" href="#wishlist" role="tab" aria-controls="wishlist">
                                <i class="bi bi-heart me-2"></i> Избранное
                                @if(auth()->user()->favorites->count() > 0)
                                    <span class="badge bg-primary rounded-pill ms-auto">{{ auth()->user()->favorites->count() }}</span>
                                @endif
                            </a>
                            <a class="list-group-item list-group-item-action d-flex align-items-center"
                               id="addresses-tab" data-bs-toggle="list" href="#addresses" role="tab" aria-controls="addresses">
                                <i class="bi bi-geo-alt me-2"></i> Адреса доставки
                            </a>
                            <a class="list-group-item list-group-item-action d-flex align-items-center"
                               id="security-tab" data-bs-toggle="list" href="#security" role="tab" aria-controls="security">
                                <i class="bi bi-shield-lock me-2"></i> Безопасность
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Logout Button -->
                <form action="{{ route('logout') }}" method="POST" class="mt-3">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="bi bi-box-arrow-right me-2"></i>Выйти
                    </button>
                </form>
            </div>

            <div class="col-md-9">
                <div class="tab-content" id="profile-tabsContent">
                    <!-- Orders History Tab -->
                    <div class="tab-pane fade show active" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">История заказов</h5>
                            </div>
                            <div class="card-body">
                                @if(isset($orders) && count($orders) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>№ заказа</th>
                                                    <th>Дата</th>
                                                    <th>Сумма</th>
                                                    <th>Статус</th>
                                                    <th>Действия</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($orders as $order)
                                                    <tr>

                                                        <td>ORD-{{ str_pad($order->id, 6, "0", STR_PAD_LEFT) }}</td>
                                                        <td>{{ $order->created_at->format('d.m.Y') }}</td>
                                                        <td>{{ number_format($order->order_total, 2) }} ₽</td>
                                                        <td>
                                                            @switch($order->order_status)
                                                                @case('pending')
                                                                    <span class="badge bg-warning text-dark">Оформляется</span>
                                                                    @break
                                                                @case('processing')
                                                                    <span class="badge bg-info">Комплектуется</span>
                                                                    @break
                                                                @case('shipped')
                                                                    <span class="badge bg-primary">Отправлен</span>
                                                                    @break
                                                                @case('delivered')
                                                                    <span class="badge bg-success">Доставлен</span>
                                                                    @break
                                                                @case('cancelled')
                                                                    <span class="badge bg-danger">Отменен</span>
                                                                    @break
                                                                @case('returned')
                                                                    <span class="badge bg-secondary">Возвращен</span>
                                                                    @break
                                                                @default
                                                                    <span class="badge bg-secondary">{{ $order->order_status }}</span>
                                                            @endswitch
                                                        </td>
                                                        <td>
                                                            {{-- <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                                Подробнее
                                                            </a> --}}

                                                            @if(in_array($order->order_status, ['pending', 'processing', 'shipped']))
                                                                <button type="button" class="btn btn-sm btn-outline-danger ms-1"
                                                                        data-bs-toggle="modal" data-bs-target="#cancelOrderModal"
                                                                        data-order-id="{{ $order->id }}">
                                                                    Отменить
                                                                </button>
                                                            @elseif($order->order_status === 'delivered')
                                                                <a href="{{ route('returns.edit', ['id' => $order->id]) }}" class="btn btn-sm btn-outline-warning ms-1">
                                                                    Возврат
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Edit Profile Tab -->
                    <div class="tab-pane fade" id="edit-profile" role="tabpanel" aria-labelledby="edit-profile-tab">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Редактировать профиль</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('dashboard.update') }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Имя</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                   value="{{ auth()->user()->name }}" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                   value="{{ auth()->user()->email }}" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="phone" class="form-label">Телефон</label>
                                            <input type="tel" class="form-control" id="phone" name="phone"
                                                   value="{{ auth()->user()->phone ?? '' }}"
                                                   placeholder="+7 (___) ___-__-__">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="birth_date" class="form-label">Дата рождения</label>
                                            <input type="date" class="form-control" id="birth_date" name="birth_date"
                                                   value="{{ auth()->user()->birth_date ?? '' }}">
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Пол</label>
                                            <div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="gender"
                                                           id="gender_male" value="male"
                                                           {{ auth()->user()->gender == 'male' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="gender_male">Мужской</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="gender"
                                                           id="gender_female" value="female"
                                                           {{ auth()->user()->gender == 'female' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="gender_female">Женский</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter"
                                                       {{ auth()->user()->newsletter ? 'checked' : '' }}>
                                                <label class="form-check-label" for="newsletter">
                                                    Получать новости и специальные предложения
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">
                                                Сохранить изменения
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Wishlist Tab -->
                    <div class="tab-pane fade" id="wishlist" role="tabpanel" aria-labelledby="wishlist-tab">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Избранное</h5>
                                @if(auth()->user()->favorites->count() > 0)
                                    <span class="badge bg-primary">Всего: {{ auth()->user()->favorites->count() }}</span>
                                @endif
                            </div>
                            <div class="card-body">
                                @if(auth()->user()->favorites->count() > 0)
                                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                                        @foreach(auth()->user()->favorites as $book)
                                            {{-- LEGACY --}}
                                            {{-- <div class="col">
                                                <div class="card h-100 book-card">
                                                    <div class="position-relative">
                                                        <img src="{{ $favorite->image_path ?? '/placeholder.svg?height=300&width=200' }}"
                                                             class="card-img-top" alt="{{ $favorite->title }}"
                                                             style="height: 300px; object-fit: cover;">
                                                        <form action="{{ route('favorites.toggle', $favorite->id) }}"
                                                              method="POST" class="position-absolute top-0 end-0 m-2">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-danger rounded-circle">
                                                                <i class="bi bi-heart-fill"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                    <div class="card-body">
                                                        <h5 class="card-title">{{ $favorite->title }}</h5>
                                                        <p class="card-text text-muted">{{ $favorite->author }}</p>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="fs-5 fw-bold text-primary">{{ number_format($favorite->price, 2) }} ₽</span>
                                                            <div class="d-flex">
                                                                <form action="{{ route('cart.item.store', $favorite->id) }}" method="POST">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-sm btn-primary me-2">
                                                                        <i class="bi bi-cart-plus"></i>
                                                                    </button>
                                                                </form>
                                                                <a href="{{ route('catalog.book', $favorite->id) }}" class="btn btn-sm btn-outline-secondary">
                                                                    <i class="bi bi-eye"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> --}}

                                            <x-book-card
                                                :id="$book->id"
                                                :title="$book->title"
                                                :author="$book->author"
                                                :price="$book->price"
                                                :imagePath="$book->image_path"
                                                :publisher="$book->publisher"
                                                :publication_year="$book->publication_year"
                                            />


                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="bi bi-heart fs-1 text-muted"></i>
                                        <p class="mt-3">У вас пока нет избранных книг</p>
                                        <p class="text-muted mb-4">Добавляйте понравившиеся книги в избранное, чтобы вернуться к ним позже</p>
                                        <a href="{{ route('home') }}" class="btn btn-primary">
                                            Перейти к каталогу
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Addresses Tab -->
                    <div class="tab-pane fade" id="addresses" role="tabpanel" aria-labelledby="addresses-tab">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Адреса доставки</h5>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                    <i class="bi bi-plus-lg me-1"></i>Добавить адрес
                                </button>
                            </div>
                            <div class="card-body">
                                @if(isset($addresses) && count($addresses) > 0)
                                    <div class="row row-cols-1 row-cols-md-2 g-4">
                                        @foreach($addresses as $address)
                                            <div class="col">
                                                <div class="card h-100">
                                                    <div class="card-body">
                                                        @if($address->is_default)
                                                            <span class="badge bg-primary mb-2">Основной адрес</span>
                                                        @endif
                                                        <h6 class="card-title">{{ $address->name }}</h6>
                                                        <p class="card-text">
                                                            {{ $address->address }}<br>
                                                            {{ $address->city }}, {{ $address->region }}<br>
                                                            {{ $address->postal_code }}
                                                        </p>
                                                        <div class="d-flex justify-content-end mt-3">
                                                            <button type="button" class="btn btn-sm btn-outline-primary me-2">
                                                                Редактировать
                                                            </button>
                                                            <form action="{{ route('addresses.destroy', $address->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                    Удалить
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <!-- Sample data for preview -->
                                    <div class="row row-cols-1 row-cols-md-2 g-4">
                                        <div class="col">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <span class="badge bg-primary mb-2">Основной адрес</span>
                                                    <h6 class="card-title">Домашний</h6>
                                                    <p class="card-text">
                                                        ул. Примерная, д. 123, кв. 45<br>
                                                        Москва, Московская область<br>
                                                        123456
                                                    </p>
                                                    <div class="d-flex justify-content-end mt-3">
                                                        <button type="button" class="btn btn-sm btn-outline-primary me-2">
                                                            Редактировать
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger">
                                                            Удалить
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <h6 class="card-title">Рабочий</h6>
                                                    <p class="card-text">
                                                        ул. Деловая, д. 78, офис 42<br>
                                                        Москва, Московская область<br>
                                                        123789
                                                    </p>
                                                    <div class="d-flex justify-content-end mt-3">
                                                        <button type="button" class="btn btn-sm btn-outline-primary me-2">
                                                            Редактировать
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger">
                                                            Удалить
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Security Tab -->
                    <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Безопасность</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('password.update') }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="current_password" class="form-label">Текущий пароль</label>
                                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="password" class="form-label">Новый пароль</label>
                                            <input type="password" class="form-control" id="password" name="password" required>
                                            <div class="form-text">
                                                Пароль должен содержать не менее 8 символов
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="password_confirmation" class="form-label">Подтверждение пароля</label>
                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">
                                                Изменить пароль
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <hr class="my-4">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Address Modal -->
    <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAddressModalLabel">Добавить адрес</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('addresses.store') }}" method="POST" id="addAddressForm">
                        @csrf
                        <div class="mb-3">
                            <label for="address_name" class="form-label">Название адреса</label>
                            <input type="text" class="form-control" id="address_name" name="name" placeholder="Например: Домашний, Рабочий" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Адрес</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="Улица, дом, квартира" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="city" class="form-label">Город</label>
                                <input type="text" class="form-control" id="city" name="city" required>
                            </div>
                            <div class="col-md-6">
                                <label for="region" class="form-label">Область/Край</label>
                                <input type="text" class="form-control" id="region" name="region">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="postal_code" class="form-label">Индекс</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_default" name="is_default">
                            <label class="form-check-label" for="is_default">Использовать как адрес по умолчанию</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" form="addAddressForm" class="btn btn-primary">Сохранить</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Order Modal -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelOrderModalLabel">Отмена заказа</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="cancelOrderForm" action="{{ route('orders.cancel') }}" method="POST">
                    @csrf
                    <input type="hidden" name="order_id" id="cancelOrderId">
                    <div class="modal-body">
                        <p>Вы уверены, что хотите отменить этот заказ?</p>
                        <p class="text-danger small">Внимание: После отмены заказа его нельзя будет восстановить.</p>

                        <div class="mb-3">
                            <label for="cancellation_reason" class="form-label">Причина отмены</label>
                            <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-danger">Подтвердить отмену</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Order Cancellation Success Modal -->
    <div class="modal fade" id="cancellationSuccessModal" tabindex="-1" aria-labelledby="cancellationSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancellationSuccessModalLabel">Заказ отменен</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <i class="bi bi-check-circle-fill text-success fs-1"></i>
                    </div>
                    <h5>Заказ успешно отменен</h5>
                    <p>Ваш заказ был успешно отменен. Если вы оплатили заказ, средства будут возвращены в соответствии с нашей политикой возврата.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="window.location.reload()">OK</button>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        // Handle 2FA toggle
        const twoFactorToggle = document.getElementById('two_factor_auth');
        const setup2faButton = document.getElementById('setup2fa');

        if (twoFactorToggle && setup2faButton) {
            twoFactorToggle.addEventListener('change', function() {
                if (this.checked) {
                    setup2faButton.style.display = 'inline-block';
                } else {
                    setup2faButton.style.display = 'none';
                }
            });

            // Initialize on page load
            if (twoFactorToggle.checked) {
                setup2faButton.style.display = 'inline-block';
            }
        }

        // Handle tab persistence with URL hash
        const hash = window.location.hash;
        if (hash) {
            const tab = document.querySelector(`#profile-tabs a[href="${hash}"]`);
            if (tab) {
                tab.click();
            }
        }

        // Update URL hash when tab changes
        const tabLinks = document.querySelectorAll('#profile-tabs a');
        tabLinks.forEach(link => {
            link.addEventListener('shown.bs.tab', function(e) {
                window.location.hash = e.target.getAttribute('href');
            });
        });

        // Phone input formatting
        const phoneInput = document.getElementById('phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let x = e.target.value.replace(/\D/g, '').match(/(\d{0,1})(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})/);
                e.target.value = !x[2] ? x[1] : '+' + x[1] + ' (' + x[2] + ') ' + (x[3] ? x[3] + '-' + x[4] : (x[3] ? x[3] : '')) + (x[5] ? '-' + x[5] : '');
            });
        }

        // Handle order cancellation modal
        const cancelOrderModal = document.getElementById('cancelOrderModal');
        if (cancelOrderModal) {
            cancelOrderModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const orderId = button.getAttribute('data-order-id');
                document.getElementById('cancelOrderId').value = orderId;
            });
        }

        // Handle order cancellation form submission
        const cancelOrderForm = document.getElementById('cancelOrderForm');
        if (cancelOrderForm) {
            cancelOrderForm.addEventListener('submit', function(event) {
                event.preventDefault();

                const formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Hide the cancellation modal
                    const cancelModal = bootstrap.Modal.getInstance(cancelOrderModal);
                    cancelModal.hide();

                    // Show success modal
                    const successModal = new bootstrap.Modal(document.getElementById('cancellationSuccessModal'));
                    successModal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Произошла ошибка при отмене заказа. Пожалуйста, попробуйте еще раз.');
                });
            });
        }

        // Check for cancellation success message in URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('cancellation_success')) {
            const successModal = new bootstrap.Modal(document.getElementById('cancellationSuccessModal'));
            successModal.show();
        }
    });
    </script>
@endpush

@push('head')
    <style>
    .book-card {
        transition: all 0.3s ease;
    }

    .book-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .list-group-item.active {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .list-group-item {
        border-left: none;
        border-right: none;
        padding: 0.75rem 1.25rem;
    }

    .list-group-item:first-child {
        border-top: none;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }

    .tab-pane {
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    </style>
@endpush
</x-layout>
