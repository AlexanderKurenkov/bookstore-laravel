<?php

namespace App\Services;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProfileService
{
    public function updateProfile(User $user, mixed $validatedData)
    {
        $user->fill($validatedData);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
    }

    public function deleteUser(User $user)
    {
        Auth::logout();  // Log out the user
        $user->delete(); // Delete the user
    }
}
