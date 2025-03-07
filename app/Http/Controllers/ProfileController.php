<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return view('profile.index');
    }

    /**
     * Display the form to edit user's profile.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $this->profileService->updateProfile($request->user(), $request->validated());

        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validate the password
        $this->validateDeletionRequest($request);

        // Get the authenticated user
        $user = Auth::user();

        // Delete the user and perform session invalidation
        $this->profileService->deleteUser($user);

        $this->invalidateSession($request);

        return redirect()->to('/');
    }

    private function validateDeletionRequest(Request $request) : void
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);
    }

    private function invalidateSession(Request $request) : void
    {
        $request->session()->invalidate();    // Invalidate session
        $request->session()->regenerateToken(); // Regenerate CSRF token
    }
}
