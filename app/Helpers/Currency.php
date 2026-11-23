<?php

namespace App\Helpers;

use App\Models\Setting;

/**
 * Display-currency conversion for the public site.
 *
 * Tour/safari prices are stored in USD. When a visitor picks another currency
 * from the navbar switcher, public prices are converted using the rates below.
 * Admins can override each rate from Admin > Settings (currency_rate_EUR, ...).
 * Bookings are charged in the currency the visitor selected at checkout.
 */
class Currency
{
    public const SUPPORTED = ['USD', 'EUR', 'GBP', 'TZS', 'KES', 'UGX'];

    /** Approximate fallback rates per 1 USD; admins should keep these updated. */
    public const DEFAULT_RATES = [
        'USD' => 1.0,
        'EUR' => 0.92,
        'GBP' => 0.79,
        'TZS' => 2600.0,
        'KES' => 129.0,
        'UGX' => 3700.0,
    ];

    public const SYMBOLS = [
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£',
        'TZS' => 'TSh ',
        'KES' => 'KSh ',
        'UGX' => 'USh ',
    ];

    /** The currency currently selected by the visitor (defaults to USD). */
    public static function current(): string
    {
        $code = strtoupper((string) session('currency', 'USD'));

        return in_array($code, self::SUPPORTED, true) ? $code : 'USD';
    }

    public static function rate(string $code): float
    {
        $code = strtoupper($code);

        if (! isset(self::DEFAULT_RATES[$code])) {
            return 1.0;
        }

        if ($code === 'USD') {
            return 1.0;
        }

        $rate = (float) Setting::get('currency_rate_'.$code, self::DEFAULT_RATES[$code]);

        return $rate > 0 ? $rate : self::DEFAULT_RATES[$code];
    }

    /** Convert a USD amount into the target currency, rounded sensibly. */
    public static function convert(float $usdAmount, ?string $to = null): float
    {
        $to = strtoupper($to ?? self::current());
        $converted = $usdAmount * self::rate($to);

        // Whole-unit currencies read better without cents.
        return in_array($to, ['TZS', 'KES', 'UGX'], true)
            ? round($converted, 0)
            : round($converted, 2);
    }

    public static function symbol(?string $code = null): string
    {
        return self::SYMBOLS[strtoupper($code ?? self::current())] ?? '$';
    }

    /** Format a USD amount in the visitor's selected display currency. */
    public static function format(float $usdAmount, ?string $to = null, ?int $decimals = null): string
    {
        $to = strtoupper($to ?? self::current());
        $decimals ??= in_array($to, ['TZS', 'KES', 'UGX'], true) ? 0 : 2;

        return self::symbol($to).number_format(self::convert($usdAmount, $to), $decimals);
    }
}
