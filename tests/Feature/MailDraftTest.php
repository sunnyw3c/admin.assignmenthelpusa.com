<?php

namespace Tests\Feature;

use App\Models\MailDraft;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MailDraftTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_save_and_update_a_direct_mail_draft(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $payload = [
            'draft_name' => 'Order follow-up',
            'mail_type' => 'direct',
            'campaign' => $this->directContent(),
        ];

        $this->actingAs($admin)
            ->post(route('mail.drafts.save'), $payload)
            ->assertRedirect()
            ->assertSessionHas('success', 'Mail draft saved.');

        $this->assertDatabaseHas('mail_drafts', [
            'user_id' => $admin->id,
            'name' => 'Order follow-up',
            'mail_type' => 'direct',
        ]);

        $payload['campaign']['subject'] = 'Updated order follow-up';
        $this->actingAs($admin)->post(route('mail.drafts.save'), $payload)->assertRedirect();

        $this->assertSame(1, MailDraft::count());
        $this->assertSame('Updated order follow-up', MailDraft::first()->content['subject']);
    }

    public function test_admin_cannot_delete_another_admins_draft(): void
    {
        $owner = User::factory()->create(['role' => 'admin']);
        $otherAdmin = User::factory()->create(['role' => 'admin']);
        $draft = MailDraft::create([
            'user_id' => $owner->id,
            'name' => 'Private draft',
            'mail_type' => 'direct',
            'content' => $this->directContent(),
        ]);

        $this->actingAs($otherAdmin)
            ->delete(route('mail.drafts.delete', $draft))
            ->assertForbidden();

        $this->assertDatabaseHas('mail_drafts', ['id' => $draft->id]);
    }

    private function directContent(): array
    {
        return [
            'subject' => 'A quick follow-up about your assignment',
            'preheader' => 'Please review your assignment details.',
            'headline' => 'Your assignment support update',
            'message' => 'We are following up regarding your requested assignment support.',
            'offer_label' => '',
            'cta_text' => 'Review your account',
            'cta_url' => 'https://assignmenthelpusa.com/dashboard',
            'accent_color' => '#2563eb',
        ];
    }
}
