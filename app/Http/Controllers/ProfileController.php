<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Laravel\Fortify\TwoFactorAuthenticationProvider;
use PragmaRX\Google2FA\Google2FA;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
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
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function showTwoFactorForm(Request $request)
    {
        $user = $request->user();
        $secret = $user->two_factor_secret;

        return view('profile.two-factor', [
            'user' => $user,
            'qrCodeUrl' => $secret ? app(\PragmaRX\Google2FAQRCode\Google2FA::class)
                                    ->getQRCodeUrl(config('app.name'), $user->email, decrypt($secret))
                                  : null,
        ]);
    }

    public function enableTwoFactor(Request $request): RedirectResponse 
    {
        $provider = new TwoFactorAuthenticationProvider(new Google2FA());
        
        $request->user()->forceFill([
            'two_factor_secret' => encrypt($provider->generateSecretKey()),
            'two_factor_confirmed_at' => now(),
        ])->save();

        return back()->with('status', 'two-factor-authentication-enabled');
    }

    public function disableTwoFactor(Request $request): RedirectResponse
    {
        $request->user()->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null
        ])->save();

        return back()->with('status', 'two-factor-authentication-disabled');
    }

    public function verifyTwoFactor(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $user = \App\Models\User::find(session('2fa:user:id'));
        $google2fa = new \PragmaRX\Google2FA\Google2FA();

        if ($google2fa->verifyKey(decrypt($user->two_factor_secret), $request->code)) {
            Auth::login($user);
            session()->forget('2fa:user:id');

            return redirect()->intended(match($user->role) {
                'admin' => 'admin/dashboard',
                'instructor' => 'instructor/dashboard',
                'student' => 'students/dashboard',
                default => 'dashboard',
            });
        }

        return back()->withErrors(['code' => 'Invalid two-factor authentication code.']);
    }

}
