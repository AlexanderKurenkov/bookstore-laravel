<x-layout>
    <div class="container my-4">
        <!-- Search Results Header -->
        <div class="mb-4">
            <h1 class="mb-2">Результаты поиска по запросу: <span class="text-primary">{{ request('query') }}</span></h1>
            {{-- <p class="text-muted">
                Найдено книг: {{ $books->total() }}
            </p> --}}
        </div>

        <!-- Sorting Controls -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div class="mb-2 mb-md-0">
                        {{-- <span class="text-muted">Показано {{ $books->firstItem() ?? 0 }} - {{ $books->lastItem() ?? 0 }} из {{ $books->total() }}</span> --}}
                        <span class="text-muted">Показано книг: {{ $books->firstItem() ?? 0 }} - {{ $books->lastItem() ?? 0 }} из {{ $books->total() }}</span>
                    </div>

                    <div class="d-flex align-items-center">
                        <label for="sort-select" class="me-2 text-nowrap">Сортировать:</label>
                        <form action="{{ route('search.results') }}" method="GET" class="d-flex">
                            <!-- Preserve search query and other parameters -->
                            <input type="hidden" name="query" value="{{ request('query') }}">
                            @foreach(request()->except(['sort', 'page', 'query']) as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach

                            <select id="sort-select" name="sort" class="form-select" onchange="this.form.submit()">
                                <option value="default" {{ request('sort') == 'default' || !request('sort') ? 'selected' : '' }}>По умолчанию</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>По возрастанию цены</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>По убыванию цены</option>
                                <option value="date_added" {{ request('sort') == 'date_added' ? 'selected' : '' }}>По дате добавления</option>
                                <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>По названию</option>
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>По рейтингу</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if($books->isEmpty())
            <!-- No Results Message -->
            <div class="text-center py-5">
                <i class="bi bi-search fs-1 text-muted"></i>
                <h3 class="mt-3">Ничего не найдено</h3>
                <p class="text-muted mb-4">По вашему запросу "{{ request('query') }}" ничего не найдено. Попробуйте изменить запрос или просмотреть наш каталог.</p>
                <a href="{{ route('catalog.index') }}" class="btn btn-primary">Перейти в каталог</a>
            </div>
        @else
            <!-- Search Results -->
            <div class="row">
                @foreach($books as $book)
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

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $books->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    @push('head')
        <style>
            /* Highlight search terms in results */
            .highlight {
                background-color: rgba(255, 255, 0, 0.3);
                padding: 0 2px;
                border-radius: 2px;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Optional: Highlight search terms in results
            document.addEventListener('DOMContentLoaded', function() {
                const searchQuery = "{{ request('query') }}";
                if (searchQuery) {
                    const terms = searchQuery.split(' ').filter(term => term.length > 2);

                    if (terms.length > 0) {
                        const bookTitles = document.querySelectorAll('.card-title');
                        const bookAuthors = document.querySelectorAll('.card-text');

                        const highlightText = (elements, terms) => {
                            elements.forEach(element => {
                                let html = element.innerHTML;
                                terms.forEach(term => {
                                    const regex = new RegExp(`(${term})`, 'gi');
                                    html = html.replace(regex, '<span class="highlight">$1</span>');
                                });
                                element.innerHTML = html;
                            });
                        };

                        highlightText(bookTitles, terms);
                        highlightText(bookAuthors, terms);
                    }
                }
            });
        </script>
    @endpush
</x-layout>
