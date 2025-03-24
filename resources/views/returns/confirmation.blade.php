<x-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <div class="d-inline-flex justify-content-center align-items-center bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-check-circle-fill text-success fs-1"></i>
                            </div>
                        </div>

                        <h1 class="h3 mb-3">Заявка на возврат принята</h1>
                        <p class="text-muted mb-4">Номер заявки: #{{ $return->id }}</p>

                        <div class="card mb-4">
                            <div class="card-body text-start">
                                <h5 class="card-title">Информация о возврате</h5>
                                <div class="row mb-2">
                                    <div class="col-md-4 fw-bold">Номер заказа:</div>
                                    <div class="col-md-8">{{ $return->order_id }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4 fw-bold">Книга:</div>
                                    <div class="col-md-8">{{ $return->book->title }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4 fw-bold">Количество:</div>
                                    <div class="col-md-8">{{ $return->return_quantity }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4 fw-bold">Причина возврата:</div>
                                    <div class="col-md-8">{{ $return->return_reason }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-4 fw-bold">Статус:</div>
                                    <div class="col-md-8">
                                        <span class="badge bg-warning text-dark">На рассмотрении</span>
                                    </div>
                                </div>
                                <div class="row mb-0">
                                    <div class="col-md-4 fw-bold">Дата заявки:</div>
                                    <div class="col-md-8">{{ $return->created_at->format('d.m.Y H:i') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4 bg-light">
                            <div class="card-body">
                                <h5 class="card-title">Что дальше?</h5>
                                <ol class="text-start mb-0">
                                    <li class="mb-2">Наш специалист рассмотрит вашу заявку в течение 1-2 рабочих дней.</li>
                                    <li class="mb-2">После одобрения заявки вы получите инструкции по отправке товара.</li>
                                    <li class="mb-2">После получения и проверки товара мы оформим возврат денежных средств.</li>
                                    <li>Возврат будет произведен тем же способом, которым была произведена оплата.</li>
                                </ol>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('profile.index')}}#orders" class="btn btn-primary">
                                Мои заказы
                            </a>
                            <a href="{{ route('index') }}" class="btn btn-outline-secondary">
                                На главную
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
