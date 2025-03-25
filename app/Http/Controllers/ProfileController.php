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

class ProfileController extends Controller
{
    protected ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * Display the form to edit user's profile.
     */
    public function index(Request $request): View
    {

        // TODO put into service
        // Get all orders for the currently authenticated user
        $orders = auth()->user()->orders;

        // Pass orders to the view
        return view('profile.index', compact('orders'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $this->profileService->updateProfile($request->user(), $request->validated());

        return redirect()->to(route('profile.index') . '#edit-profile')->with('status', 'profile-updated');
    }

    public function updatePassword(Request $request)
    {
        // Validate the input data
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'min:8', 'confirmed'], // Confirmed checks password_confirmation field
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Ensure the current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            // If the current password is incorrect
            // return back()->withErrors(['current_password' => 'Текущий пароль неверный.'])->withInput();
            return response()->json([
                'success' => false,
                'errors' => ['current_password' => 'Текущий пароль неверный.']
            ], 422); // 422 Unprocessable Entity is appropriate for validation errors
        }

        // Update the password
        $user->update([
            'password' => bcrypt($request->password), // Encrypt the new password
        ]);

        // Redirect with a success message
        // return redirect()->route('profile.index')->with('status', 'password-updated');

        return response()->json([
            'success' => true,
            'message' => 'Пароль был успешно обновлен.',
        ]);
    }

    /**
     * Delete the user's account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'confirm_deletion' => 'required|accepted',
        ]);

        $user = Auth::user();

        // Verify password
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
            // Delete related data
            // Note: This assumes you have proper foreign key constraints with cascade delete
            // or you need to manually delete related records here

            // Delete the user
            $user->delete();

            DB::commit();

            // Log the user out
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
