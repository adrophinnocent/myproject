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
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            $adminEmails = ['twinasafaris@gmail.com', 'admin@tourism.com', 'bellainnos@gmail.com', 'info@twinasafaris.com'];
            if ($user->is_admin || in_array(strtolower($user->email), $adminEmails)) {
                return redirect()->route('admin.dashboard');
            }
        }

        return view('login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        $adminEmails = ['twinasafaris@gmail.com', 'admin@tourism.com', 'bellainnos@gmail.com', 'info@twinasafaris.com'];

        if ($user && ($user->is_admin || in_array(strtolower($user->email), $adminEmails))) {
            if (!$user->is_admin) {
                try {
                    $user->is_admin = true;
                    $user->save();
                } catch (\Throwable $e) {}
            }
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home')->with('success', __('Welcome back!'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', __('You have been logged out successfully.'));
    }
}
