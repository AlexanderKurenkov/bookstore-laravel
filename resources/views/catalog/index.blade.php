@props(['categoryName' => __('Book catalog')])
<x-layout>
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">{{ $categoryName }}</h1>

        <!-- Sorting Controls -->
        <div class="d-flex align-items-center">
            <label for="sort-select" class="me-2 text-nowrap">Сортировать:</label>
            <form action="" method="GET" class="d-flex">
                <!-- Preserve any existing query parameters -->
                @foreach(request()->except('sort', 'page') as $key => $value)
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

    <div class="d-flex justify-content-center mt-4">
        {{ $books->links('pagination::bootstrap-5') }} {{-- Laravel pagination --}}
    </div>
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