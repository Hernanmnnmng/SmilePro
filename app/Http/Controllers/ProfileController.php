<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        try {
            return view('profile.edit', [
                'user' => $request->user(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading profile edit form', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return view('profile.edit', [
                'user' => $request->user(),
            ])->with('error', 'Er is een fout opgetreden bij het laden van het profiel.');
        }
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            $request->user()->fill($request->validated());

            if ($request->user()->isDirty('email')) {
                $request->user()->email_verified_at = null;
            }

            $request->user()->save();
            
            Log::info('Profile updated successfully', [
                'user_id' => Auth::id()
            ]);

            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating profile', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Er is een fout opgetreden bij het bijwerken van uw profiel. Probeer het opnieuw.')->withInput();
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            $request->validateWithBag('userDeletion', [
                'password' => ['required', 'current_password'],
            ]);

            $user = $request->user();
            $userId = $user->id;

            Auth::logout();

            $user->delete();
            
            Log::info('User account deleted', [
                'user_id' => $userId
            ]);

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect::to('/');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors(), 'userDeletion');
        } catch (\Exception $e) {
            Log::error('Error deleting user account', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Er is een fout opgetreden bij het verwijderen van uw account. Probeer het opnieuw.');
        }
    }
}
