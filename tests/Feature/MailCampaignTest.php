<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MailCampaignTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_send_a_custom_campaign(): void
    {
        Http::fake([
            '*/api/admin/mail/send' => Http::response(['message' => 'Email sent successfully.']),
        ]);

        $admin = User::factory()->create(['role' => 'admin']);
        $campaign = $this->campaign();

        $response = $this->actingAs($admin)->post(route('mail.send'), [
            'email' => 'student@example.com',
            'campaign' => $campaign,
        ]);

        $response
            ->assertRedirect()
            ->assertSessionHas('success', 'Promotional email sent to student@example.com');

        Http::assertSent(fn ($request) =>
            str_ends_with($request->url(), '/api/admin/mail/send')
            && $request['email'] === 'student@example.com'
            && $request['campaign'] === $campaign
        );
    }

    public function test_campaign_content_is_strictly_validated(): void
    {
        Http::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $campaign = $this->campaign();
        $campaign['subject'] = "Unsafe\r\nBcc: recipient@example.com";
        $campaign['cta_url'] = 'javascript:alert(1)';
        $campaign['accent_color'] = 'red';

        $this->actingAs($admin)
            ->post(route('mail.send'), [
                'email' => 'student@example.com',
                'campaign' => $campaign,
            ])
            ->assertSessionHasErrors([
                'campaign.subject',
                'campaign.cta_url',
                'campaign.accent_color',
            ]);

        Http::assertNothingSent();
    }

    public function test_non_campaign_roles_cannot_send_mail(): void
    {
        Http::fake();

        $writer = User::factory()->create(['role' => 'writer']);

        $this->actingAs($writer)
            ->post(route('mail.send'), [
                'email' => 'student@example.com',
                'campaign' => $this->campaign(),
            ])
            ->assertForbidden();

        Http::assertNothingSent();
    }

    private function campaign(): array
    {
        return [
            'subject' => 'Save on expert assignment help',
            'preheader' => 'A useful preview for the inbox.',
            'headline' => 'Get expert help today',
            'message' => 'Our academic experts are ready to help with your next assignment.',
            'offer_label' => '20% OFF',
            'promo_code' => 'WELCOME20',
            'cta_text' => 'Start an order',
            'cta_url' => 'https://assignmenthelpusa.com/order',
            'accent_color' => '#e63946',
        ];
    }
}
