<?php

namespace App\Services;

use App\Models\CampaignDeliveryLog;
use App\Models\MarketingContact;
use App\Models\ScheduledCampaignEmail;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CampaignSchedulerService
{
    /**
     * Staggered interval between emails in minutes.
     */
    public const INTERVAL_MINUTES = 2;

    /**
     * Target daily schedule start hour (6:00 PM = 18:00).
     */
    public const SCHEDULE_HOUR = 18;

    /**
     * Anti-fatigue cooldown period in days.
     */
    public const COOLDOWN_DAYS = 7;

    /**
     * Schedule a batch of recipients with 2-minute throttling and 7-day auto-deferral.
     *
     * @param array<int, string|array{email: string, name?: string|null}> $recipients
     * @param array<string, mixed> $campaignContent
     * @return array{total: int, queued_this_week: int, deferred_next_week: int, scheduled_items: Collection}
     */
    public function scheduleBatch(
        array $recipients,
        array $campaignContent,
        string $mailType = 'promotional',
        ?int $userId = null
    ): array {
        $cleanRecipients = $this->sanitizeRecipients($recipients);
        $cooldownCutoff = now()->subDays(self::COOLDOWN_DAYS);

        // Find recent sends within 7 days
        $emails = array_column($cleanRecipients, 'email');
        $recentlySentEmails = CampaignDeliveryLog::query()
            ->whereIn('recipient_email', $emails)
            ->where('sent_at', '>=', $cooldownCutoff)
            ->pluck('recipient_email')
            ->map(fn ($e) => strtolower($e))
            ->flip()
            ->all();

        // Also check MarketingContact last_sent_at
        $contactRecentlySent = MarketingContact::query()
            ->whereIn('email', $emails)
            ->where('last_sent_at', '>=', $cooldownCutoff)
            ->pluck('email')
            ->map(fn ($e) => strtolower($e))
            ->flip()
            ->all();

        // Calculate base start time for today's batch (6:00 PM or current time if already past 6:00 PM)
        $todayTarget = now()->setTime(self::SCHEDULE_HOUR, 0, 0);
        $thisWeekBaseTime = now()->gt($todayTarget) ? now()->addMinute() : $todayTarget;

        // Calculate base start time for next week's deferred batch
        $nextWeekBaseTime = now()->addDays(7)->setTime(self::SCHEDULE_HOUR, 0, 0);

        $thisWeekIndex = 0;
        $nextWeekIndex = 0;
        $scheduledRecords = [];

        foreach ($cleanRecipients as $item) {
            $email = strtolower($item['email']);
            $name = $item['name'] ?? null;

            $isCooldown = isset($recentlySentEmails[$email]) || isset($contactRecentlySent[$email]);

            if ($isCooldown) {
                // Defer to next week
                $scheduledAt = (clone $nextWeekBaseTime)->addMinutes($nextWeekIndex * self::INTERVAL_MINUTES);
                $status = 'deferred';
                $nextWeekIndex++;
            } else {
                // Queue for this week
                $scheduledAt = (clone $thisWeekBaseTime)->addMinutes($thisWeekIndex * self::INTERVAL_MINUTES);
                $status = 'pending';
                $thisWeekIndex++;
            }

            $record = ScheduledCampaignEmail::create([
                'user_id' => ($userId && \App\Models\User::where('id', $userId)->exists()) ? $userId : null,
                'recipient_email' => $email,
                'recipient_name' => $name,
                'mail_type' => $mailType,
                'campaign_content' => $campaignContent,
                'scheduled_at' => $scheduledAt,
                'status' => $status,
            ]);

            // Ensure contact is saved in marketing_contacts directory
            MarketingContact::updateOrCreate(
                ['email' => $email],
                ['name' => $name, 'is_subscribed' => true]
            );

            $scheduledRecords[] = $record;
        }

        return [
            'total' => count($cleanRecipients),
            'queued_this_week' => $thisWeekIndex,
            'deferred_next_week' => $nextWeekIndex,
            'scheduled_items' => collect($scheduledRecords),
        ];
    }

    /**
     * Deduplicate and validate recipient list.
     *
     * @param array<int, string|array{email: string, name?: string|null}> $recipients
     * @return array<int, array{email: string, name: string|null}>
     */
    private function sanitizeRecipients(array $recipients): array
    {
        $unique = [];

        foreach ($recipients as $item) {
            if (is_string($item)) {
                $email = strtolower(trim($item));
                $name = null;
            } else {
                $email = strtolower(trim($item['email'] ?? ''));
                $name = !empty($item['name']) ? trim($item['name']) : null;
            }

            if (filter_var($email, FILTER_VALIDATE_EMAIL) && !isset($unique[$email])) {
                $unique[$email] = [
                    'email' => $email,
                    'name' => $name,
                ];
            }
        }

        return array_values($unique);
    }
}
