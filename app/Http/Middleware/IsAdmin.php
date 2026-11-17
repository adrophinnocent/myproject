<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->guest(route('login'));
        }

        if ($user->is_admin || in_array(strtolower($user->email), ['twinasafaris@gmail.com', 'admin@tourism.com', 'info@twinasafaris.com'])) {
            if (! $user->is_admin) {
                $user->update(['is_admin' => true]);
            }
            return $next($request);
        }

        Log::warning("Forbidden 403 access to {$request->path()} by User ID: {$user->id}, Email: {$user->email}, IP: {$request->ip()}");

        abort(403, 'Restricted Territory: You don\'t have necessary admin permits.');
    }
}
