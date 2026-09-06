<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectWww
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->header('host');

        if ($host && str_starts_with($host, 'www.')) {
            $newHost = substr($host, 4);
            $newUrl = $request->getScheme() . '://' . $newHost . $request->getRequestUri();

            return redirect()->to($newUrl, 301);
        }

        return $next($request);
    }
}
