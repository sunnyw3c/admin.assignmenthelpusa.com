<?php

namespace App\Jobs;

use App\Models\CampaignDeliveryLog;
use App\Models\MarketingContact;
use App\Models\ScheduledCampaignEmail;
use App\Services\AdminApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendScheduledCampaignEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;
    public int $timeout = 60;

    public function __construct(
        public ScheduledCampaignEmail $scheduledEmail
    ) {}

    public function handle(AdminApiService $api): void
    {
        // If cancelled or already sent, stop
        if (in_array($this->scheduledEmail->status, ['cancelled', 'sent'], true)) {
            return;
        }

        $this->scheduledEmail->update(['status' => 'processing']);

        try {
            $result = $api->sendPromotionalEmail(
                $this->scheduledEmail->recipient_email,
                $this->scheduledEmail->campaign_content,
                $this->scheduledEmail->mail_type
            );

            if ($result['status'] >= 200 && $result['status'] < 300) {
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
            } else {
                $error = $result['data']['message'] ?? 'Failed with HTTP status ' . $result['status'];
                $this->scheduledEmail->update([
                    'status' => 'failed',
                    'error_message' => $error,
                ]);
                Log::warning("Campaign email failed for: {$this->scheduledEmail->recipient_email} - {$error}");
            }
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
