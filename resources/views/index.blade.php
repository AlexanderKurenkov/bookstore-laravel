<x-layout>
<main class="container py-3">
  <!-- Hero Banner -->
  <div class="row mb-3">
      <div class="col-12">
          <div class="card bg-dark text-white rounded-3 overflow-hidden">
              <img src="{{ asset('images/banner.jpg') }}" class="card-img opacity-50" alt="Книжный магазин" style="object-fit: cover;">
              <div class="card-img-overlay d-flex flex-column justify-content-center">
                  <div class="container">
                      <h1 class="card-title display-4 fw-bold">Добро пожаловать в наш книжный магазин</h1>
                      <p class="card-text fs-5 mb-4">Откройте для себя новые миры с нашей коллекцией книг</p>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <!-- Categories -->
  <div class="row mb-5">
      <div class="col-12">
          <h2 class="mb-4">Популярные категории</h2>
      </div>
      <div class="col-12">
          <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3">
              <div class="col">
                  <a href="{{ route('catalog.category', 'classics') }}" class="text-decoration-none">
                      <div class="card h-100 text-center hover-shadow">
                          <div class="card-body">
                              <i class="bi bi-book-half fs-1 text-primary mb-3"></i>
                              <h5 class="card-title">Классика</h5>
                          </div>
                      </div>
                  </a>
              </div>
              <div class="col">
                  <a href="{{ route('catalog.category', 'detective') }}" class="text-decoration-none">
                      <div class="card h-100 text-center hover-shadow">
                          <div class="card-body">
                              <i class="bi bi-search fs-1 text-danger mb-3"></i>
                              <h5 class="card-title">Детектив</h5>
                          </div>
                      </div>
                  </a>
              </div>
              <div class="col">
                  <a href="{{ route('catalog.category', 'philosophy') }}" class="text-decoration-none">
                      <div class="card h-100 text-center hover-shadow">
                          <div class="card-body">
                              <i class="bi bi-lightbulb fs-1 text-warning mb-3"></i>
                              <h5 class="card-title">Философия</h5>
                          </div>
                      </div>
                  </a>
              </div>
              <div class="col">
                  <a href="{{ route('catalog.category', 'poetry') }}" class="text-decoration-none">
                      <div class="card h-100 text-center hover-shadow">
                          <div class="card-body">
                              <i class="bi bi-pen fs-1 text-info mb-3"></i>
                              <h5 class="card-title">Поэзия</h5>
                          </div>
                      </div>
                  </a>
              </div>
              <div class="col">
                  <a href="{{ route('catalog.category', 'fantasy') }}" class="text-decoration-none">
                      <div class="card h-100 text-center hover-shadow">
                          <div class="card-body">
                              <i class="bi bi-stars fs-1 text-purple mb-3"></i>
                              <h5 class="card-title">Фэнтези</h5>
                          </div>
                      </div>
                  </a>
              </div>
              <div class="col">
                  <a href="{{ route('catalog.index') }}" class="text-decoration-none">
                      <div class="card h-100 text-center hover-shadow">
                          <div class="card-body">
                              <i class="bi bi-grid-3x3-gap fs-1 text-dark mb-3"></i>
                              <h5 class="card-title">Все категории</h5>
                          </div>
                      </div>
                  </a>
              </div>
          </div>
      </div>
  </div>

  <!-- New Releases -->
  <div class="row mb-5">
    <div class="col-12 d-flex justify-content-between align-items-center mb-4">
        <h2>Новинки</h2>
    </div>

    <div class="col-12">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            @php
              $newBooks = App\Models\Book::select('books.id', 'books.title', 'books.image_path', 'books.author', 'books.price')
                  ->orderByDesc('created_at')
                  ->limit(4)
                  ->get();
            @endphp

            @foreach($newBooks ?? [] as $book)
            <div class="col">
              <a href="{{ route('catalog.book', $book->id ?? 1) }}" class="text-decoration-none text-dark">
                  <div class="card h-100 book-card">
                      <div class="position-relative">
                          <img src="{{ asset($book->image_path) }}" class="card-img-top" alt="{{ $book->title ?? 'Новинка' }}" style="height: 420px; object-fit: cover;">
                          <div class="position-absolute top-0 start-0 m-2">
                              <span class="badge bg-danger">Новинка</span>
                          </div>
                      </div>
                      <div class="card-body">
                          <div class="mb-2">
                              @for($i = 1; $i <= 5; $i++)
                                  @if($i <= ($book->reviews->isEmpty() ? 0 : round($book->reviews->avg('rating'), 2)))
                                      <i class="bi bi-star-fill text-warning"></i>
                                  @else
                                      <i class="bi bi-star text-warning"></i>
                                  @endif
                              @endfor
                              <small class="text-muted ms-1">Отзывов: {{ count($book->reviews) ?? 0 }}</small>
                          </div>
                          <h5 class="card-title">{{ $book->title ?? 'Название книги' }}</h5>
                          <p class="card-text text-muted">{{ $book->author ?? 'Автор книги' }}</p>
                          <div class="d-flex justify-content-between align-items-center">
                              <span class="fs-5 fw-bold text-primary">{{ number_format($book->price, 2, '.', ' ') }} ₽</span>
                              <div class="d-flex">
                                  <a href="{{ route('catalog.book', $book->id ?? 1) }}" class="btn btn-sm btn-outline-secondary">
                                      <i class="bi bi-eye"></i>
                                  </a>
                              </div>
                          </div>
                      </div>
                  </div>
              </a>
            </div>
            @endforeach
        </div>
    </div>
  </div>

  <!-- Bestsellers -->
  <div class="row mb-5">
      <div class="col-12 d-flex justify-content-between align-items-center mb-4">
          <h2>Бестселлеры</h2>
      </div>

      <div class="col-12">
          <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
              @php
                $bestsellerBooks = App\Models\Book::select('books.id', 'books.title', 'books.image_path', 'books.author', 'books.price')
                    ->join('orders_books', 'books.id', '=', 'orders_books.book_id')
                    ->selectRaw('SUM(orders_books.quantity) as total_sold')
                    ->groupBy('books.id', 'books.title')
                    ->orderByDesc('total_sold')
                    ->limit(4)
                    ->get();
              @endphp

              @foreach($bestsellerBooks ?? [] as $book)
              <div class="col">
                <a href="{{ route('catalog.book', $book->id ?? 1) }}" class="text-decoration-none text-dark">
                    <div class="card h-100 book-card">
                        <div class="position-relative">
                            <img src="{{ asset($book->image_path) }}" class="card-img-top" alt="{{ $book->title ?? 'Бестселлер' }}" style="height: 420px; object-fit: cover;">
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge bg-success">Бестселлер</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= ($book->reviews->isEmpty() ? 0 : round($book->reviews->avg('rating'), 2)))
                                        <i class="bi bi-star-fill text-warning"></i>
                                    @else
                                        <i class="bi bi-star text-warning"></i>
                                    @endif
                                @endfor
                                <small class="text-muted ms-1">{{ $book->reviews_count ?? 0 }} отзывов</small>
                            </div>
                            <h5 class="card-title">{{ $book->title ?? 'Название книги' }}</h5>
                            <p class="card-text text-muted">{{ $book->author ?? 'Автор книги' }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fs-5 fw-bold text-primary">{{ number_format($book->price, 2, '.', ' ') }} ₽</span>
                                <div class="d-flex">
                                    <a href="{{ route('catalog.book', $book->id ?? 1) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
              </div>
              @endforeach
          </div>
      </div>
  </div>

  <!-- Information Blocks -->
  <div class="row mb-3">
      <div class="col-12 mb-4">
          <h2>Наши преимущества</h2>
      </div>

      <div class="col-md-3 mb-4">
          <div class="card h-100 text-center hover-shadow border-0 bg-light">
              <div class="card-body py-4">
                  <div class="rounded-circle bg-primary bg-opacity-10 p-3 d-inline-flex mb-3">
                      <i class="bi bi-truck fs-1 text-primary"></i>
                  </div>
                  <h4>Быстрая доставка</h4>
                  <p class="text-muted">Доставляем заказы по всей России в течение 1-3 дней в крупные города и 3-7 дней в удаленные регионы.</p>
              </div>
          </div>
      </div>

      <div class="col-md-3 mb-4">
          <div class="card h-100 text-center hover-shadow border-0 bg-light">
              <div class="card-body py-4">
                  <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-flex mb-3">
                      <i class="bi bi-shield-check fs-1 text-success"></i>
                  </div>
                  <h4>Гарантия качества</h4>
                  <p class="text-muted">Мы тщательно проверяем каждую книгу перед отправкой и гарантируем возврат в случае брака.</p>
              </div>
          </div>
      </div>

      <div class="col-md-3 mb-4">
          <div class="card h-100 text-center hover-shadow border-0 bg-light">
              <div class="card-body py-4">
                  <div class="rounded-circle bg-warning bg-opacity-10 p-3 d-inline-flex mb-3">
                      <i class="bi bi-credit-card fs-1 text-warning"></i>
                  </div>
                  <h4>Удобная оплата</h4>
                  <p class="text-muted">Принимаем все популярные способы оплаты: банковские карты, электронные деньги и наличные при получении.</p>
              </div>
          </div>
      </div>

      <div class="col-md-3 mb-4">
          <div class="card h-100 text-center hover-shadow border-0 bg-light">
              <div class="card-body py-4">
                  <div class="rounded-circle bg-info bg-opacity-10 p-3 d-inline-flex mb-3">
                      <i class="bi bi-headset fs-1 text-info"></i>
                  </div>
                  <h4>Поддержка 24/7</h4>
                  <p class="text-muted">Наша служба поддержки всегда готова ответить на ваши вопросы и помочь с выбором книг.</p>
              </div>
          </div>
      </div>
  </div>

  <!-- Reading Club -->
  <div class="row mb-3">
      <div class="col-12">
          <div class="card bg-primary text-white">
              <div class="card-body p-4">
                  <div class="row align-items-center">
                      <div class="col-lg-6">
                          <h2 class="mb-3"><i class="bi bi-people-fill me-2"></i>Книжный клуб "Читатель"</h2>
                          <p class="fs-5 mb-4">Присоединяйтесь к нашему книжному клубу и получите доступ к эксклюзивным мероприятиям, встречам с авторами и специальным предложениям!</p>
                          <ul class="list-unstyled mb-4">
                              <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i>Ежемесячные встречи и обсуждения книг</li>
                              <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i>Скидка 10% на все книги для членов клуба</li>
                              <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i>Предварительный доступ к новинкам</li>
                              <li><i class="bi bi-check-circle-fill me-2"></i>Онлайн-дискуссии и рекомендации от экспертов</li>
                          </ul>
                      </div>
                      <div class="col-lg-6 d-none d-lg-block">
                          <img src="/images/book_club.png" class="img-fluid" alt="Книжный клуб">
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <!-- Upcoming Events -->
  <div class="row mb-3">
      <div class="col-12 mb-4">
          <h2>Предстоящие мероприятия</h2>
      </div>

      <div class="col-12">
          <div class="card border-0 shadow-sm">
              <div class="card-body p-0">
                  <div class="list-group list-group-flush">
                      <div class="list-group-item p-4">
                          <div class="row align-items-center">
                              <div class="col-md-2 text-center mb-3 mb-md-0">
                                  <div class="bg-light rounded-3 py-3 px-4">
                                      <div class="fs-1 fw-bold text-primary">15</div>
                                      <div class="text-uppercase">Апреля</div>
                                  </div>
                              </div>
                              <div class="col-md-8 mb-3 mb-md-0">
                                  <h4><i class="bi bi-mic-fill me-2 text-danger"></i>Встреча с автором: Мария Петрова</h4>
                                  <p class="text-muted mb-0">Презентация новой книги "Путь к себе" и автограф-сессия. Начало в 18:00.</p>
                              </div>
                          </div>
                      </div>
                      <div class="list-group-item p-4">
                          <div class="row align-items-center">
                              <div class="col-md-2 text-center mb-3 mb-md-0">
                                  <div class="bg-light rounded-3 py-3 px-4">
                                      <div class="fs-1 fw-bold text-primary">22</div>
                                      <div class="text-uppercase">Апреля</div>
                                  </div>
                              </div>
                              <div class="col-md-8 mb-3 mb-md-0">
                                  <h4><i class="bi bi-book-fill me-2 text-success"></i>Книжный клуб: обсуждение классики</h4>
                                  <p class="text-muted mb-0">Обсуждаем роман "Мастер и Маргарита" М. Булгакова. Начало в 19:00.</p>
                              </div>
                          </div>
                      </div>
                      <div class="list-group-item p-4">
                          <div class="row align-items-center">
                              <div class="col-md-2 text-center mb-3 mb-md-0">
                                  <div class="bg-light rounded-3 py-3 px-4">
                                      <div class="fs-1 fw-bold text-primary">29</div>
                                      <div class="text-uppercase">Апреля</div>
                                  </div>
                              </div>
                              <div class="col-md-8 mb-3 mb-md-0">
                                  <h4><i class="bi bi-easel-fill me-2 text-warning"></i>Мастер-класс: Как написать свою книгу</h4>
                                  <p class="text-muted mb-0">Практические советы от профессионального писателя. Начало в 17:00.</p>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</main>

<style>
.hover-shadow:hover {
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
  transform: translateY(-3px);
  transition: all 0.3s ease;
}

.book-card {
  transition: all 0.3s ease;
}

.book-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.text-purple {
  color: #6f42c1;
}
</style>
</x-layout>