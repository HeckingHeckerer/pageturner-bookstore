<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Check if two-factor authentication is enabled
        $user = Auth::user();
        if ($user->two_factor_enabled) {
            // Generate and send two-factor code
            $code = $user->generateTwoFactorCode();
            
            // For demo purposes, we'll flash the code to the session
            // In a real application, you would send this via email
            session()->flash('two_factor_code', $code);
            
            // Mark that two-factor verification is required
            session()->put('two_factor_required', true);
            
            return redirect()->route('two-factor.verification');
        }

        return redirect()->intended(route('home', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
