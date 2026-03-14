<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class TwoFactorController extends Controller
{
    /**
     * Show the two-factor verification form.
     */
    public function showVerificationForm()
    {
        return view('auth.two-factor-verification');
    }

    /**
     * Verify the two-factor authentication code.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:4',
        ]);

        $user = Auth::user();

        if ($user->verifyTwoFactorCode($request->code)) {
            Session::forget('two_factor_required');
            return redirect()->intended(route('dashboard', absolute: false));
        }

        return back()->withErrors(['code' => 'The provided code is invalid.']);
    }

    /**
     * Resend the two-factor authentication code.
     */
    public function resend()
    {
        $user = Auth::user();
        $code = $user->generateTwoFactorCode();

        // Send email with the code (you can customize this)
        // For now, we'll just flash the code to the session for demo purposes
        Session::flash('two_factor_code', $code);

        return back()->with('status', 'A new verification code has been sent to your email.');
    }

    /**
     * Show the two-factor settings page.
     */
    public function showSettings()
    {
        return view('profile.two-factor-settings');
    }

    /**
     * Update two-factor authentication settings.
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'two_factor_enabled' => 'boolean',
        ]);

        $user = Auth::user();
        
        if ($request->has('two_factor_enabled')) {
            $user->enableTwoFactor();
            $message = 'Two-factor authentication has been enabled.';
        } else {
            $user->disableTwoFactor();
            $message = 'Two-factor authentication has been disabled.';
        }

        return back()->with('status', $message);
    }
}