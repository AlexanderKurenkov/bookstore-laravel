<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileService
{
    /**
     * Обновляет данные профиля пользователя.
     *
     * @param User $user Экземпляр модели пользователя.
     * @param mixed $validatedData Валидированные данные для обновления профиля.
     * @return bool Возвращает true, если обновление прошло успешно, и false в случае ошибки.
     */
    public function updateProfile(User $user, mixed $validatedData): bool
    {
        // Заполняем модель пользователя новыми данными
        $user->fill($validatedData);

        // Если email был изменен, сбрасываем метку подтверждения email
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Сохраняем изменения в базе данных
        return $user->save();
    }

    /**
     * Удаляет аккаунт пользователя и очищает сессию.
     */
    public function deleteProfile(User $user, Request $request): bool
    {
        DB::beginTransaction();

        try {
            // Удаление связанных данных при необходимости

            $user->delete();

            DB::commit();

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
