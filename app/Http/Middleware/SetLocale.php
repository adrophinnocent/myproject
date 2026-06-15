<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $supported = ['en', 'de', 'fr', 'es', 'it', 'zh', 'nl'];

        // 1. Get locale from session
        $locale = Session::get('locale', config('app.locale'));

        // 2. Fallback if not supported
        if (!in_array($locale, $supported)) {
            $locale = config('app.locale');
        }

        // 3. Set the application locale
        App::setLocale($locale);

        return $next($request);
    }
}
