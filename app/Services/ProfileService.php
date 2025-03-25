<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileService
{
    public function updateProfile(User $user, mixed $validatedData) : bool
    {
        $user->fill($validatedData);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        return $user->save();
    }

    // public function deleteUser(User $user) : bool
    // {
    //     Auth::logout();  // Log out the user
    //     // Using type casting because Model::delete() returns bool|null.
    //     return (bool)$user->delete(); // Delete the user
    // }
}
