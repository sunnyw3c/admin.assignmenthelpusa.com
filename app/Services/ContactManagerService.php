<?php

namespace App\Services;

use App\Models\MarketingContact;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ContactManagerService
{
    /**
     * Add or update a single contact.
     */
    public function addContact(string $email, ?string $name = null, ?string $tags = null, ?string $notes = null): MarketingContact
    {
        $email = strtolower(trim($email));

        return MarketingContact::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name ? trim($name) : null,
                'tags' => $tags ? trim($tags) : null,
                'notes' => $notes ? trim($notes) : null,
                'is_subscribed' => true,
            ]
        );
    }

    /**
     * Parse and import bulk contacts from raw text (CSV, comma-separated, or newline-separated).
     *
     * @return array{imported: int, skipped: int, errors: array<string>}
     */
    public function importBulkContacts(string $rawInput, ?string $tags = null): array
    {
        $lines = preg_split('/[\r\n]+/', trim($rawInput));
        $imported = 0;
        $skipped = 0;
        $errors = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            // Support CSV "email,name" or comma-separated list
            $parts = str_getcsv($line);
            $emailCandidate = trim($parts[0] ?? '');
            $nameCandidate = trim($parts[1] ?? '');

            if (! filter_var($emailCandidate, FILTER_VALIDATE_EMAIL)) {
                // If there are multiple comma-separated emails on one line
                $subParts = explode(',', $line);
                foreach ($subParts as $subPart) {
                    $subEmail = trim($subPart);
                    if (filter_var($subEmail, FILTER_VALIDATE_EMAIL)) {
                        $this->addContact($subEmail, null, $tags);
                        $imported++;
                    } else {
                        $skipped++;
                    }
                }
                continue;
            }

            $this->addContact($emailCandidate, !empty($nameCandidate) ? $nameCandidate : null, $tags);
            $imported++;
        }

        return [
            'imported' => $imported,
            'skipped' => $skipped,
            'errors' => $errors,
        ];
    }

    /**
     * Get all active contacts with 7-day cooldown status calculated.
     */
    public function getContactsSummary(): Collection
    {
        $cutoff = now()->subDays(7);

        return MarketingContact::query()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function (MarketingContact $contact) use ($cutoff) {
                $isCooldown = $contact->last_sent_at !== null && $contact->last_sent_at->gte($cutoff);
                $daysAgo = $contact->last_sent_at ? (int) $contact->last_sent_at->diffInDays(now()) : null;

                return [
                    'id' => $contact->id,
                    'email' => $contact->email,
                    'name' => $contact->name ?? 'Contact',
                    'tags' => $contact->tags,
                    'is_subscribed' => $contact->is_subscribed,
                    'last_sent_at' => $contact->last_sent_at?->format('M j, Y g:i A'),
                    'last_sent_human' => $contact->last_sent_at?->diffForHumans(),
                    'is_cooling_down' => $isCooldown,
                    'cooldown_status' => $isCooldown
                        ? "Contacted {$daysAgo}d ago (Defers to next week)"
                        : "Ready to send",
                ];
            });
    }
}
