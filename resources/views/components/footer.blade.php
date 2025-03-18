<footer class="bg-dark text-white mt-4 pt-4 pb-2" style="font-size: 0.875rem;">
    <div class="container">
        <div class="row text-center justify-content-center">
            <!-- About Section -->
            <div class="col-md-4 mb-4">
                <h6 class="mb-3">О магазине</h6>
                <p>Книги на любой вкус. Мы стремимся предоставить лучший сервис и качественную продукцию.</p>
            </div>

            <!-- Contact Section -->
            <div class="col-md-4 mb-4">
                <h6 class="mb-3">Контакты</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="bi bi-geo-alt me-2"></i>Адрес: ул. Книжная, д. 77, г. Москва
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-telephone me-2"></i>Телефон: 8-800-123-45-67
                    </li>
                    <li>
                        <i class="bi bi-envelope me-2"></i>Email: info@knogochei.ru
                    </li>
                </ul>
            </div>

            <!-- Social Media Section -->
            <div class="col-md-4 mb-4">
                <h6 class="mb-3">Мы в соцсетях</h6>
                <div class="d-flex justify-content-center">
                    <a href="#" class="text-white text-decoration-none mx-2">
                        <i class="bi bi-telegram fs-4"></i>
                    </a>

                    <a href="#" class="text-white text-decoration-none mx-2">
                        <i class="bi bi-youtube fs-4"></i>
                    </a>
                </div>
            </div>
        </div>

        <hr class="bg-light my-3 opacity-25">

        <div class="row">
            <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                <p class="mb-0">&copy; 2025 Интернет-магазин Книгочей. Все права защищены.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <ul class="list-inline mb-0">
                    {{-- TODO --}}
                    <li class="list-inline-item"><a href="{{ route('index') }}" class="text-white text-decoration-none">Условия использования</a></li>
                    {{-- <li class="list-inline-item"><a href="{{ route('terms') }}" class="text-white text-decoration-none">Условия использования</a></li> --}}
                    <li class="list-inline-item"><span class="text-muted mx-1">|</span></li>
                    {{-- TODO --}}
                    <li class="list-inline-item"><a href="{{ route('index') }}" class="text-white text-decoration-none">Политика конфиденциальности</a></li>
                    {{-- <li class="list-inline-item"><a href="{{ route('privacy') }}" class="text-white text-decoration-none">Политика конфиденциальности</a></li> --}}
                </ul>
            </div>
        </div>
    </div>
</footer>
