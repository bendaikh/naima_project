<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Log detailed debugging
        \Log::info('Login attempt received', [
            'email' => $credentials['email'],
            'password_provided' => !empty($credentials['password']),
            'password_length' => strlen($credentials['password'] ?? ''),
            'remember' => $request->boolean('remember'),
            'request_method' => $request->method(),
            'csrf_token_present' => $request->has('_token'),
        ]);

        // Check if user exists in database
        $userExists = \App\Models\User::where('email', $credentials['email'])->exists();
        \Log::info('User lookup', [
            'email' => $credentials['email'],
            'exists' => $userExists,
        ]);

        $attemptResult = Auth::attempt($credentials, $request->boolean('remember'));
        
        \Log::info('Auth attempt result', [
            'email' => $credentials['email'],
            'result' => $attemptResult ? 'success' : 'failed',
            'auth_check' => Auth::check(),
            'authenticated_user' => Auth::user()?->email ?? 'none',
        ]);

        if ($attemptResult) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
