<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->guest(route('login'));
        }

        // Allow if user is marked as admin OR if user email matches admin emails
        if ($user->is_admin || in_array(strtolower($user->email), ['twinasafaris@gmail.com', 'admin@tourism.com', 'info@twinasafaris.com'])) {
            if (!$user->is_admin) {
                $user->update(['is_admin' => true]);
            }
            return $next($request);
        }

        // If user is not admin, abort with 403
        abort(403, 'Unauthorized access to admin area.');
    }
}
