@props(['categoryName' => __('All books'), 'categorySlug' => 'all'])
<x-layout>
<div class="container my-4">
    <div class="mb-4">
        <h1 class="mb-2">Категория: <span class="text-primary">{{ $categoryName }}</span></h1>
        {{-- <p class="text-muted">
            Найдено книг: {{ $books->total() }}
        </p> --}}
    </div>

    <!-- Sorting Controls -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div class="mb-2 mb-md-0">
                    <span class="text-muted">Показано книг: {{ $books->firstItem() ?? 0 }} - {{ $books->lastItem() ?? 0 }} из {{ $books->total() }}</span>
                </div>

                <div class="d-flex align-items-center">
                    <label for="sort-select" class="me-2 text-nowrap">Сортировать:</label>
                    {{-- <form action="{{ route('search.results') }}" method="GET" class="d-flex"> --}}

                    {{-- stay inside /catalog/category/{categorySlug} --}}
                    <form action="{{ route('catalog.category', ['url_slug' => request('category') ?? $categorySlug]) }}" method="GET" class="d-flex">

                        <!-- Preserve search query and other parameters -->
                        <input type="hidden" name="query" value="{{ request('query') }}">

                        <!-- Explicitly include the category if it exists -->
                        <input type="hidden" name="category" value="{{ request('category') ?? $categorySlug }}">

                        @foreach(request()->except(['sort', 'page', 'query', 'category']) as $key => $value)
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

    <div class="row">
        @if($books->isEmpty())
            <!-- No Results Message -->
            <div class="text-center py-5">
                <i class="bi bi-search fs-1 text-muted"></i>
                <h3 class="mt-3 mb-3">Ничего не найдено</h3>
                <a href="{{ route('catalog.index') }}" class="btn btn-primary">Перейти в каталог</a>
            </div>
        @else
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
        @endif
    </div>

    @if(!$books->isEmpty())
        <div class="d-flex justify-content-center mt-4">
            {{ $books->links('pagination::bootstrap-5') }} {{-- Laravel pagination --}}
        </div>
    @endif

</div>

@push('head')
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
    </style>
@endpush
</x-layout>