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
        $queryLocale = $request->query('lang');

        if (is_string($queryLocale) && SeoHelper::isSupported($queryLocale)) {
            // A valid URL choice wins for this request and is remembered so the
            // language switcher can use clean URLs from here on.
            Session::put('locale', $queryLocale);

            // English is the default: ?lang=en is a needless duplicate URL.
            // Remember the choice first, then 301 to the clean URL so the
            // visitor still sees English after the redirect.
            if ($queryLocale === SeoHelper::defaultLocale()) {
                $query = $request->query();
                unset($query['lang']);

                $path = $request->getPathInfo();

                return redirect()->to(
                    SeoHelper::siteUrl().$path.($query === [] ? '' : '?'.http_build_query($query)),
                    301
                );
            }

            App::setLocale($queryLocale);

            return $next($request);
        }

        // No usable ?lang= value: fall back to the visitor's session choice.
        // This never affects canonical or hreflang, only the rendered UI.
        $locale = Session::get('locale', config('app.locale'));
        $locale = is_string($locale) ? $locale : config('app.locale');

        if (! SeoHelper::isSupported($locale)) {
            $locale = config('app.locale');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
