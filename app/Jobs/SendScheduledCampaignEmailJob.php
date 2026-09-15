<?php

namespace App\Jobs;

use App\Mail\CampaignEmail;
use App\Models\CampaignDeliveryLog;
use App\Models\MarketingContact;
use App\Models\ScheduledCampaignEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendScheduledCampaignEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $timeout = 60;

    public function __construct(
        public ScheduledCampaignEmail $scheduledEmail
    ) {}

    public function handle(): void
    {
        // If cancelled or already sent, stop
        if (in_array($this->scheduledEmail->status, ['cancelled', 'sent'], true)) {
            return;
        }

        $this->scheduledEmail->update(['status' => 'processing']);

        try {
            Mail::to($this->scheduledEmail->recipient_email)->send(
                new CampaignEmail(
                    $this->scheduledEmail->campaign_content,
                    $this->scheduledEmail->mail_type,
                    $this->scheduledEmail->recipient_name
                )
            );

            $now = now();
            $this->scheduledEmail->update([
                'status' => 'sent',
                'sent_at' => $now,
                'error_message' => null,
            ]);

            // Record delivery log
            CampaignDeliveryLog::create([
                'recipient_email' => $this->scheduledEmail->recipient_email,
                'subject' => $this->scheduledEmail->campaign_content['subject'] ?? 'Promotional Campaign',
                'mail_type' => $this->scheduledEmail->mail_type,
                'sent_at' => $now,
            ]);

            // Update contact last_sent_at
            MarketingContact::where('email', $this->scheduledEmail->recipient_email)
                ->update(['last_sent_at' => $now]);

            Log::info("Campaign email delivered to: {$this->scheduledEmail->recipient_email}");
        } catch (\Throwable $e) {
            $this->scheduledEmail->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            Log::error("Exception in SendScheduledCampaignEmailJob: {$e->getMessage()}");
            throw $e;
        }
    }
}
