<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\CatalogService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

/**
 * Контроллер для управления каталогом книг.
 */
class CatalogController extends Controller
{
    /** @var CatalogService Сервис для работы с каталогом книг */
    protected CatalogService $catalogService;

    /**
     * Конструктор контроллера.
     *
     * @param CatalogService $catalogService Экземпляр сервиса каталога
     */
    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    /**
     * Отображает главную страницу каталога книг с пагинацией.
     *
     * @return View Представление каталога
     */
    public function index(): View
    {
        // Получаем все книги с пагинацией по 8 элементов
        $books = $this->catalogService->getAllBooks()->paginate(8);
        return view('catalog.index', compact('books'));
    }

    /**
     * Отображает страницу определённой категории книг.
     *
     * @param string $url_slug URL-ключ категории
     * @return View Представление каталога с книгами выбранной категории
     */
    public function showCategory(string $url_slug): View
    {
        // Получаем параметр сортировки (по умолчанию — 'default')
        $sort = request('sort', 'default');

        // Получаем данные о книгах в указанной категории
        $data = $this->catalogService->getBooksByCategory($url_slug, $sort);

        return view('catalog.index', $data);
    }

    /**
     * Отображает страницу книги по её идентификатору.
     *
     * @param int $id Идентификатор книги
     * @return View Представление страницы книги
     */
    public function showBook(int $id): View
    {
        // Получаем данные о книге по её ID
        $book = $this->catalogService->getBookById($id);

        return view('catalog.book', compact('book'));
    }

    // ==============================================================
	// API methods
	// ==============================================================
    /**
     * Возвращает список избранных книг пользователя.
     *
     * @return JsonResponse JSON-ответ с количеством и списком избранных книг
     */
    public function listFavorites(): JsonResponse
    {
        // Получаем избранные книги пользователя
        $favorites = auth()->user()->favorites->map(function ($favorite) {
            return [
                'id' => $favorite->id,
                'title' => $favorite->title,
                'author' => $favorite->author,
                'price' => number_format($favorite->price, 2),
                'image' => $favorite->image_path,
            ];
        });

        return response()->json([
            'count' => $favorites->count(),
            'favorites' => $favorites,
        ]);
    }

    /**
     * Добавляет или удаляет книгу из списка избранного.
     *
     * @param Request $request HTTP-запрос с идентификатором книги
     * @return JsonResponse JSON-ответ с новым статусом избранного
     */
    public function toggleFavorites(Request $request): JsonResponse
    {
        // Проверяем, авторизован ли пользователь
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $user = auth()->user();
        $bookId = $request->input('bookId');

        // Ищем книгу по её ID
        $book = Book::find($bookId);

        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }

        // Переключаем статус избранного
        if ($user->favorites->contains($bookId)) {
            // Если книга уже в избранном — удаляем её
            $user->favorites()->detach($bookId);
            $isFavorite = false;
        } else {
            // Если книги нет в избранном — добавляем её
            $user->favorites()->attach($bookId);
            $isFavorite = true;
        }

        // Получаем обновлённое количество избранных книг
        $favoritesCount = $user->favorites()->count();

        return response()->json([
            'isFavorite' => $isFavorite,
            'favoritesCount' => $favoritesCount
        ]);
    }
}
