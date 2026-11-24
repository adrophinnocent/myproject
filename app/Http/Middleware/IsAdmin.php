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

        // Admin access is determined by the database flag only. Email patterns
        // are not authorization: any account with "admin" in its address would
        // otherwise be able to manage the whole site.
        if ($user->is_admin) {
            return $next($request);
        }

        Log::warning("Forbidden 403 access to {$request->path()} by User ID: {$user->id}, Email: {$user->email}, IP: {$request->ip()}");

        abort(403);
    }
}
