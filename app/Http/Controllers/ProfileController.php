<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\ProfileUpdateRequest;
use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

/**
 * Контроллер для управления профилем пользователя.
 */
class ProfileController extends Controller
{
    /**
     * @var ProfileService Сервис для работы с профилем пользователя.
     */
    protected ProfileService $profileService;

    /**
     * Создает экземпляр контроллера и инициализирует сервис профиля.
     *
     * @param ProfileService $profileService Сервис профиля.
     */
    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

/**
 * Отображает страницу редактирования профиля пользователя.
 *
 * @param Request $request HTTP-запрос.
 * @return View Представление профиля пользователя.
 */
    public function index(Request $request): View
    {
        // TODO: Переместить логику в сервис
        // Получаем все заказы аутентифицированного пользователя
        $orders = auth()->user()->orders;

        // Передаем заказы в представление
        return view('profile.index', compact('orders'));
    }

    /**
     * Обновляет информацию профиля пользователя.
     *
     * @param ProfileUpdateRequest $request Запрос с данными для обновления профиля.
     * @return RedirectResponse Перенаправление обратно с сообщением об успешном обновлении.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $this->profileService->updateProfile($request->user(), $request->validated());

        return redirect()->to(route('profile.index') . '#edit-profile')->with('status', 'profile-updated');
    }

    /**
     * Обновляет пароль пользователя.
     *
     * @param Request $request HTTP-запрос с текущим и новым паролем.
     * @return \Illuminate\Http\JsonResponse Ответ в формате JSON.
     */
    public function updatePassword(Request $request)
    {
        // Валидация входных данных
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'min:8', 'confirmed'], // confirmed проверяет поле password_confirmation
        ]);

        // Получаем аутентифицированного пользователя
        $user = Auth::user();

        // Проверяем корректность текущего пароля
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'errors' => ['current_password' => 'Текущий пароль неверный.']
            ], 422); // 422 Unprocessable Entity подходит для ошибок валидации
        }

        // Обновляем пароль
        $user->update([
            'password' => bcrypt($request->password), // Шифруем новый пароль
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Пароль был успешно обновлен.',
        ]);
    }

    /**
     * Удаляет аккаунт пользователя.
     *
     * @param Request $request HTTP-запрос с подтверждением пароля.
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse Ответ в зависимости от типа запроса.
     * @throws ValidationException Если пароль введен неверно.
     */
    public function destroy(Request $request)
    {
        // Валидация входных данных
        $request->validate([
            'password' => 'required',
            'confirm_deletion' => 'required|accepted',
        ]);

        $user = Auth::user();

        // Проверяем пароль пользователя
        if (!Hash::check($request->password, $user->password)) {
            if ($request->ajax()) {
                return response()->json([
                    'message' => 'Неверный пароль. Пожалуйста, введите правильный пароль для подтверждения удаления аккаунта.'
                ], 422);
            }

            throw ValidationException::withMessages([
                'password' => ['Неверный пароль. Пожалуйста, введите правильный пароль для подтверждения удаления аккаунта.'],
            ]);
        }

        DB::beginTransaction();
        try {
            // Удаляем связанные данные (если требуется ручное удаление, иначе каскадное удаление)
            // Удаляем пользователя
            $user->delete();

            DB::commit();

            // Разлогиниваем пользователя
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }

            return redirect()->route('index')
                ->with('success', 'Ваш аккаунт был успешно удален.');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'message' => 'Произошла ошибка при удалении аккаунта. Пожалуйста, попробуйте еще раз.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Произошла ошибка при удалении аккаунта. Пожалуйста, попробуйте еще раз.');
        }
    }
}
