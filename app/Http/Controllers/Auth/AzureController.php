<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class AzureController extends Controller
{
    /**
     * Redirect the user to the Microsoft Azure AD login page.
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('azure')
            ->scopes(['openid', 'profile', 'email', 'User.Read'])
            ->redirect();
    }

    /**
     * Handle the OAuth callback from Microsoft Azure AD.
     *
     * SECURITY: Only users pre-registered by an administrator (by azure_id or email)
     * are permitted to log in. Unregistered Azure accounts are rejected.
     */
    public function callback(): RedirectResponse
    {
        try {
            $azureUser = Socialite::driver('azure')->user();
        } catch (\Exception $e) {
            Log::error('Azure OAuth callback error: ' . $e->getMessage());
            return redirect()->route('login')
                ->withErrors(['azure' => 'Microsoft login failed. Please try again.']);
        }

        // --- 1. Look up user ONLY in the local database (no auto-create) ---
        $user = User::where('azure_id', $azureUser->getId())->first()
            ?? User::where('email', $azureUser->getEmail())->first();

        // --- 2. Reject if not registered ---
        if (!$user) {
            Log::warning('Azure SSO rejected — unregistered account: ' . $azureUser->getEmail());
            return redirect()->route('login')
                ->withErrors(['azure' =>
                    'Your Microsoft account (' . $azureUser->getEmail() . ') is not registered in this system. '
                    . 'Please ask your administrator to grant you access first.'
                ]);
        }

        // --- 3. Reject if inactive ---
        if (!$user->is_active) {
            return redirect()->route('login')
                ->withErrors(['azure' => 'Your account is currently inactive. Please contact your administrator.']);
        }

        // --- 4. Sync Azure identity token & last login time ---
        $user->update([
            'azure_id'     => $azureUser->getId(),
            'azure_token'  => $azureUser->token,
            'last_login_at' => now(),
        ]);

        // --- 5. Log in the user ---
        Auth::login($user, true);
        request()->session()->regenerate();

        // --- 6. Role-based redirect ---
        return $this->redirectByRole($user);
    }

    /**
     * Redirect user to the appropriate dashboard based on their role.
     */
    private function redirectByRole(User $user): RedirectResponse
    {
        return redirect()->route('dashboard');
    }
}
