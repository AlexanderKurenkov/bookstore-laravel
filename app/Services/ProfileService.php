<?php

namespace App\Services;

use App\Models\User;

class ProfileService
{
    /**
     * Обновляет данные профиля пользователя.
     *
     * @param User $user Экземпляр модели пользователя.
     * @param mixed $validatedData Валидированные данные для обновления профиля.
     * @return bool Возвращает true, если обновление прошло успешно, и false в случае ошибки.
     */
    public function updateProfile(User $user, mixed $validatedData) : bool
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
}
