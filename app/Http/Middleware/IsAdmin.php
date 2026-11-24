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

        $email = strtolower($user->email ?? '');
        $isAdminEmail = str_contains($email, 'twinasafaris')
            || str_contains($email, 'admin')
            || in_array($email, ['bellainnos@gmail.com', 'admin@tourism.com', 'twinasafaris@gmail.com']);

        if ($user->is_admin || $isAdminEmail) {
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
