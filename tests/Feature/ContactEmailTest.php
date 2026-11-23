<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_show_only_the_correct_contact_email(): void
    {
        foreach (['/', '/about', '/contact'] as $path) {
            $response = $this->get($path);

            $response->assertOk();
            $response->assertSee('twinasafaris@gmail.com');
            $response->assertDontSee('info@twinasafaris.com');
        }
    }

    public function test_header_has_no_email_and_footer_uses_the_public_address(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $footerPosition = strpos($html, '<footer');
        $this->assertNotFalse($footerPosition);

        $beforeFooter = substr($html, 0, $footerPosition);
        $footer = substr($html, $footerPosition);

        $this->assertStringNotContainsString('mailto:', $beforeFooter);
        $this->assertStringContainsString('mailto:twinasafaris@gmail.com', $footer);
        $this->assertStringContainsString('twinasafaris@gmail.com', $footer);
    }

    public function test_stale_email_setting_never_reaches_public_pages(): void
    {
        Setting::set('site_email', 'info@twinasafaris.com');

        foreach (['/', '/about', '/contact'] as $path) {
            $response = $this->get($path);

            $response->assertOk();
            $response->assertSee('twinasafaris@gmail.com');
            $response->assertDontSee('info@twinasafaris.com');
        }
    }

    public function test_public_email_helper_normalises_the_stale_address(): void
    {
        Setting::set('site_email', 'info@twinasafaris.com');
        $this->assertSame('twinasafaris@gmail.com', Setting::publicEmail());

        Setting::set('site_email', 'bookings@example.com');
        $this->assertSame('bookings@example.com', Setting::publicEmail());
    }

    public function test_migration_replaces_the_stale_contact_email_setting(): void
    {
        Setting::set('site_email', 'info@twinasafaris.com');

        $migration = require database_path('migrations/2026_11_24_000000_correct_site_email_setting.php');
        $migration->up();

        $this->assertSame('twinasafaris@gmail.com', Setting::get('site_email'));
    }
}
