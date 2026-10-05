<?php

namespace Tests\Feature;

use App\Models\Proposal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProposalPrivacySecurityTest extends TestCase
{
    use RefreshDatabase;

    private function makeProposal(): Proposal
    {
        return Proposal::create([
            'token' => str_repeat('a', 32),
            'reference_code' => 'TW-2026-0001',
            'version' => 1,
            'client_name' => 'Test Client',
            'client_email' => 'client@example.com',
            'title' => 'Private Safari Proposal',
            'duration_days' => 5,
            'adults' => 2,
            'children' => 0,
            'currency' => 'USD',
            'total_price' => 5000,
            'status' => 'sent',
        ]);
    }

    public function test_proposal_html_is_noindex_and_has_robots_meta_and_header()
    {
        $proposal = $this->makeProposal();

        $response = $this->get(route('proposal.show', $proposal->token));

        $response->assertOk();
        $response->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive, nosnippet');
        $response->assertSee('<meta name="robots" content="noindex, nofollow, noarchive, nosnippet">', false);
    }

    public function test_proposal_pdf_has_noindex_robots_header()
    {
        $proposal = $this->makeProposal();

        $response = $this->get(route('proposal.pdf', $proposal->token));

        $response->assertOk();
        $response->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive');
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_sitemap_does_not_contain_proposal_urls()
    {
        $this->makeProposal();

        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $this->assertStringNotContainsString('/proposal/', $response->getContent());
    }

    public function test_sequential_proposal_id_is_not_valid_token()
    {
        $this->makeProposal();

        $this->get('/proposal/1')->assertNotFound();
    }

    public function test_guest_is_redirected_from_admin()
    {
        $this->get('/admin/dashboard')->assertRedirect(route('login'));
    }

    public function test_normal_authenticated_customer_cannot_access_admin()
    {
        $customer = User::factory()->create(['is_admin' => false]);

        $this->actingAs($customer)
            ->get('/admin/dashboard')
            ->assertForbidden();
    }

    public function test_admin_user_can_access_admin_dashboard()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertOk();
    }

    public function test_repeated_invalid_proposal_tokens_are_rate_limited()
    {
        $lastStatus = null;

        for ($i = 0; $i < 31; $i++) {
            $response = $this->get('/proposal/invalid-token-' . $i);
            $lastStatus = $response->getStatusCode();
        }

        $this->assertSame(429, $lastStatus);
    }
}
