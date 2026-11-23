<?php

namespace App\Http\Middleware;

use App\Helpers\SeoHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // 1. ?lang=fr is crawlable and wins over the session so Google can
        //    index each translated URL directly.
        $queryLocale = $request->query('lang');
        $locale = SeoHelper::isSupported($queryLocale)
            ? (string) $queryLocale
            : Session::get('locale', config('app.locale'));

        // 2. Fallback if not supported
        if (! SeoHelper::isSupported($locale)) {
            $locale = config('app.locale');
        }

        // 3. Remember a valid URL choice for the rest of this visitor's session
        if ($queryLocale !== null && SeoHelper::isSupported($queryLocale)) {
            Session::put('locale', $locale);
        }

        // 4. Set the application locale
        App::setLocale($locale);

        return $next($request);
    }
}
