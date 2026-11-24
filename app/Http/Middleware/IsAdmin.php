<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->guest(route('login'));
        }

        $adminEmails = ['twinasafaris@gmail.com', 'admin@tourism.com', 'bellainnos@gmail.com', 'info@twinasafaris.com'];

        if ($user->is_admin || in_array(strtolower($user->email), $adminEmails)) {
            if (!$user->is_admin) {
                try {
                    $user->is_admin = true;
                    $user->save();
                } catch (\Throwable $e) {}
            }
            return $next($request);
        }

        Log::warning("Forbidden 403 access to {$request->path()} by User ID: {$user->id}, Email: {$user->email}, IP: {$request->ip()}");

        return redirect()->route('home')->with('error', __('Restricted Territory: You don\'t have admin permits.'));
    }
}
