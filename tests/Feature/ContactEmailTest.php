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

    public function test_migration_replaces_the_stale_contact_email_setting(): void
    {
        Setting::set('site_email', 'info@twinasafaris.com');

        $migration = require database_path('migrations/2026_11_24_000000_correct_site_email_setting.php');
        $migration->up();

        $this->assertSame('twinasafaris@gmail.com', Setting::get('site_email'));
    }
}
