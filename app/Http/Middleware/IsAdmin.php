<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->guest(route('login'));
        }

        if (! $request->user()->is_admin) {
            abort(403, 'Unauthorized access to admin area.');
        }

        return $next($request);
    }
}
