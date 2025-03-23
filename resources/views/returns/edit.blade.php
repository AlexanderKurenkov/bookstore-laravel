<x-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h1 class="h4 mb-0">Возврат книги</h1>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('returns.store') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label for="order_id" class="form-label">Номер заказа <span class="text-danger">*</span></label>
                                <select class="form-select @error('order_id') is-invalid @enderror" id="order_id" name="order_id" required>
                                    <option value="">Выберите заказ</option>
                                    @foreach($deliveredOrders as $order)
                                        <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                            Заказ #{{ $order->id }} от {{ $order->created_at->format('d.m.Y') }} ({{ number_format($order->order_total, 2) }} ₽)
                                        </option>
                                    @endforeach
                                </select>
                                @error('order_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Выберите заказ, из которого хотите вернуть книгу. Отображаются только доставленные заказы.</div>
                            </div>

                            <div class="mb-4">
                                <label for="book_id" class="form-label">Книга для возврата <span class="text-danger">*</span></label>
                                <select class="form-select @error('book_id') is-invalid @enderror" id="book_id" name="book_id" required disabled>
                                    <option value="">Сначала выберите заказ</option>
                                </select>
                                @error('book_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="return_quantity" class="form-label">Количество <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('return_quantity') is-invalid @enderror" id="return_quantity" name="return_quantity" min="1" value="{{ old('return_quantity', 1) }}" required>
                                @error('return_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="return_reason" class="form-label">Причина возврата <span class="text-danger">*</span></label>
                                <select class="form-select @error('return_reason_type') is-invalid @enderror" id="return_reason_type" name="return_reason_type" required>
                                    <option value="">Выберите причину возврата</option>
                                    <option value="damaged" {{ old('return_reason_type') == 'damaged' ? 'selected' : '' }}>Товар поврежден</option>
                                    <option value="wrong_item" {{ old('return_reason_type') == 'wrong_item' ? 'selected' : '' }}>Получен не тот товар</option>
                                    <option value="quality_issue" {{ old('return_reason_type') == 'quality_issue' ? 'selected' : '' }}>Проблема с качеством</option>
                                    <option value="not_as_described" {{ old('return_reason_type') == 'not_as_described' ? 'selected' : '' }}>Не соответствует описанию</option>
                                    <option value="changed_mind" {{ old('return_reason_type') == 'changed_mind' ? 'selected' : '' }}>Передумал(а)</option>
                                    <option value="other" {{ old('return_reason_type') == 'other' ? 'selected' : '' }}>Другое</option>
                                </select>
                                @error('return_reason_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4" id="return_reason_details_container" style="{{ old('return_reason_type') == 'other' ? '' : 'display: none;' }}">
                                <label for="return_reason" class="form-label">Подробное описание причины <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('return_reason') is-invalid @enderror" id="return_reason" name="return_reason" rows="3">{{ old('return_reason') }}</textarea>
                                @error('return_reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="card mb-4 bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">Условия возврата</h5>
                                    <ul class="mb-0">
                                        <li>Возврат возможен в течение 14 дней с момента получения заказа</li>
                                        <li>Книга должна быть в исходном состоянии, без повреждений</li>
                                        <li>Возврат денежных средств производится в течение 10 рабочих дней</li>
                                        <li>Стоимость доставки при возврате оплачивается покупателем, кроме случаев брака или ошибки магазина</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input @error('agree_terms') is-invalid @enderror" type="checkbox" id="agree_terms" name="agree_terms" required {{ old('agree_terms') ? 'checked' : '' }}>
                                <label class="form-check-label" for="agree_terms">
                                    Я ознакомлен и согласен с условиями возврата
                                </label>
                                @error('agree_terms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Отправить заявку на возврат</button>
                                <a href="{{ route('dashboard.index')}}#orders" class="btn btn-outline-secondary">Отмена</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const orderSelect = document.getElementById('order_id');
            const bookSelect = document.getElementById('book_id');
            const reasonTypeSelect = document.getElementById('return_reason_type');
            const reasonDetailsContainer = document.getElementById('return_reason_details_container');
            const reasonTextarea = document.getElementById('return_reason');

            // Handle order selection change
            orderSelect.addEventListener('change', function() {
                const orderId = this.value;

                // Reset and disable book select if no order is selected
                if (!orderId) {
                    bookSelect.innerHTML = '<option value="">Сначала выберите заказ</option>';
                    bookSelect.disabled = true;
                    return;
                }

                // Enable book select and fetch books for this order
                bookSelect.disabled = true; // Temporarily disable while loading
                bookSelect.innerHTML = '<option value="">Загрузка книг...</option>';

                // Fetch books for the selected order via AJAX
                fetch(`/api/orders/${orderId}/books`)
                    .then(response => response.json())
                    .then(data => {
                        bookSelect.innerHTML = '<option value="">Выберите книгу</option>';

                        if (data.length === 0) {
                            bookSelect.innerHTML = '<option value="">Нет доступных книг для возврата</option>';
                            bookSelect.disabled = true;
                        } else {
                            data.forEach(book => {
                                const option = document.createElement('option');
                                option.value = book.id;
                                option.textContent = `${book.title} (${book.quantity} шт.)`;
                                option.dataset.maxQuantity = book.quantity;
                                bookSelect.appendChild(option);
                            });
                            bookSelect.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching books:', error);
                        bookSelect.innerHTML = '<option value="">Ошибка загрузки книг</option>';
                    });
            });

            // Handle book selection change
            bookSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const returnQuantityInput = document.getElementById('return_quantity');

                if (selectedOption && selectedOption.dataset.maxQuantity) {
                    const maxQuantity = parseInt(selectedOption.dataset.maxQuantity);
                    returnQuantityInput.max = maxQuantity;
                    returnQuantityInput.value = Math.min(returnQuantityInput.value, maxQuantity);
                }
            });

            // Handle reason type change
            reasonTypeSelect.addEventListener('change', function() {
                if (this.value === 'other') {
                    reasonDetailsContainer.style.display = 'block';
                    reasonTextarea.setAttribute('required', 'required');
                } else {
                    reasonDetailsContainer.style.display = 'none';
                    reasonTextarea.removeAttribute('required');
                }
            });

            // Initialize if values are pre-selected (e.g., after validation error)
            if (orderSelect.value) {
                // Trigger change event to load books
                const event = new Event('change');
                orderSelect.dispatchEvent(event);
            }
        });
    </script>
</x-layout>
