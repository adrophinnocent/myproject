<?php

if (! function_exists('__')) {
    /**
     * Laravel-style translation helper.
     * Added as a fallback because this project uses PHP 8.3-style JSON
     * translation files but the standard helper is not registered by default.
     */
    function __($key, array $replace = [], ?string $locale = null)
    {
        return app('translator')->get($key, $replace, $locale);
    }
}
