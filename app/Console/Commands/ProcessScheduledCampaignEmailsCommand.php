<?php

namespace App\Console\Commands;

use App\Jobs\SendScheduledCampaignEmailJob;
use App\Models\ScheduledCampaignEmail;
use Illuminate\Console\Command;

class ProcessScheduledCampaignEmailsCommand extends Command
{
    protected $signature = 'campaign:process-queue {--limit=10 : Maximum emails to dispatch in one run}';
    protected $description = 'Dispatch pending scheduled campaign emails that are due for delivery';

    public function handle(): int
    {
        $limit = (int) $this->option('limit');

        $dueEmails = ScheduledCampaignEmail::query()
            ->whereIn('status', ['pending', 'deferred'])
            ->where('scheduled_at', '<=', now())
            ->orderBy('scheduled_at', 'asc')
            ->limit($limit)
            ->get();

        if ($dueEmails->isEmpty()) {
            $this->info('No due campaign emails found.');
            return self::SUCCESS;
        }

        $this->info("Found {$dueEmails->count()} due campaign emails. Dispatching to queue...");

        foreach ($dueEmails as $email) {
            $email->update(['status' => 'processing']);
            SendScheduledCampaignEmailJob::dispatch($email);
            $this->line("-> Queued dispatch for: {$email->recipient_email} (Scheduled: {$email->scheduled_at})");
        }

        return self::SUCCESS;
    }
}
