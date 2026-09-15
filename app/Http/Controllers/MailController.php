<?php

namespace App\Http\Controllers;

use App\Models\MailDraft;
use App\Models\MarketingContact;
use App\Models\ScheduledCampaignEmail;
use App\Services\AdminApiService;
use App\Services\CampaignSchedulerService;
use App\Services\ContactManagerService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    private AdminApiService $api;
    private ContactManagerService $contactManager;
    private CampaignSchedulerService $scheduler;

    public function __construct()
    {
        $this->api = new AdminApiService();
        $this->contactManager = new ContactManagerService();
        $this->scheduler = new CampaignSchedulerService();
    }

    public function index()
    {
        $this->authorizeMail();

        // 1. Fetch student users from Main API
        $users = $this->api->getUsers(['role' => 'student']);
        $recipients = collect($users['data'] ?? [])
            ->filter(fn (array $user) => filter_var($user['email'] ?? null, FILTER_VALIDATE_EMAIL))
            ->map(function (array $user) {
                $orders = (int) ($user['order_count'] ?? 0);
                $joined = isset($user['created_at']) ? Carbon::parse($user['created_at']) : null;
                $daysSinceJoining = $joined ? (int) $joined->diffInDays(now()) : null;

                $score = 25 + min($orders * 12, 60);
                if ($daysSinceJoining !== null && $daysSinceJoining <= 30) {
                    $score += 15;
                } elseif ($daysSinceJoining !== null && $daysSinceJoining <= 90) {
                    $score += 8;
                }

                return [
                    'id' => $user['id'] ?? null,
                    'name' => $user['name'] ?? 'Student',
                    'email' => $user['email'],
                    'orders' => $orders,
                    'joined' => $joined?->format('M j, Y'),
                    'score' => min($score, 100),
                    'segment' => match (true) {
                        $orders >= 5 => 'Loyal customer',
                        $orders >= 2 => 'Returning customer',
                        $orders === 1 => 'First-time customer',
                        default => 'New lead',
                    },
                ];
            })
            ->sortByDesc('score')
            ->take(50)
            ->values()
            ->all();

        // 2. Saved Admin Contacts Directory
        $savedContacts = $this->contactManager->getContactsSummary();

        // 3. Campaign defaults & drafts
        $campaign = collect(array_replace($this->campaignDefaults(), old('campaign', [])))
            ->map(fn ($value) => is_string($value) ? $value : '')
            ->all();
        $mailType = old('mail_type', 'promotional');

        $drafts = MailDraft::query()
            ->where('user_id', auth()->id())
            ->latest('updated_at')
            ->get()
            ->map(function (MailDraft $draft) {
                $campaign = collect(array_replace($this->campaignDefaults(), $draft->content))
                    ->map(fn ($value) => is_string($value) ? $value : '')
                    ->all();

                return [
                    'id' => $draft->id,
                    'name' => $draft->name,
                    'mail_type' => $draft->mail_type,
                    'campaign' => $campaign,
                    'updated_at' => $draft->updated_at->diffForHumans(),
                    'delete_url' => route('mail.drafts.delete', $draft),
                ];
            })
            ->values();

        // 4. Scheduled queue items & summary stats
        $queueItems = ScheduledCampaignEmail::query()
            ->latest('scheduled_at')
            ->take(60)
            ->get();

        $queueStats = [
            'pending' => ScheduledCampaignEmail::where('status', 'pending')->count(),
            'deferred' => ScheduledCampaignEmail::where('status', 'deferred')->count(),
            'sent' => ScheduledCampaignEmail::where('status', 'sent')->count(),
            'failed' => ScheduledCampaignEmail::where('status', 'failed')->count(),
            'total_contacts' => $savedContacts->count(),
        ];

        return view('mail.index', compact(
            'recipients',
            'savedContacts',
            'campaign',
            'mailType',
            'drafts',
            'queueItems',
            'queueStats'
        ));
    }

    /**
     * Single Instant Send
     */
    public function send(Request $request)
    {
        $this->authorizeMail();

        $validated = $request->validate(array_merge(
            ['email' => 'required|email|max:255'],
            $this->campaignRules()
        ));

        try {
            Mail::to($validated['email'])->send(
                new \App\Mail\CampaignEmail(
                    $validated['campaign'],
                    $validated['mail_type']
                )
            );

            // Record contact & log
            $this->contactManager->addContact($validated['email']);
            \App\Models\CampaignDeliveryLog::create([
                'recipient_email' => $validated['email'],
                'subject' => $validated['campaign']['subject'],
                'mail_type' => $validated['mail_type'],
                'sent_at' => now(),
            ]);

            $label = $validated['mail_type'] === 'direct' ? 'Direct email' : 'Promotional email';
            return back()->with('success', $label . ' sent successfully to ' . $validated['email']);
        } catch (\Throwable $e) {
            Log::error('Failed to send email: ' . $e->getMessage(), ['exception' => $e]);
            return back()->withInput()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    /**
     * Bulk Schedule with 2-minute throttling and 7-day cooldown auto-deferral
     */
    public function bulkSchedule(Request $request)
    {
        $this->authorizeMail();

        $validated = $request->validate(array_merge(
            [
                'recipient_source' => 'required|in:manual,contacts,all_students',
                'bulk_emails' => 'nullable|string|max:100000',
                'selected_contacts' => 'nullable|array',
                'selected_contacts.*' => 'integer|exists:marketing_contacts,id',
            ],
            $this->campaignRules()
        ));

        $recipients = [];

        if ($validated['recipient_source'] === 'manual') {
            $lines = preg_split('/[\r\n,]+/', (string) ($validated['bulk_emails'] ?? ''));
            foreach ($lines as $line) {
                $email = strtolower(trim($line));
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $recipients[] = ['email' => $email];
                }
            }
        } elseif ($validated['recipient_source'] === 'contacts') {
            $contacts = MarketingContact::whereIn('id', $validated['selected_contacts'] ?? [])
                ->where('is_subscribed', true)
                ->get();
            foreach ($contacts as $c) {
                $recipients[] = ['email' => $c->email, 'name' => $c->name];
            }
        } elseif ($validated['recipient_source'] === 'all_students') {
            $users = $this->api->getUsers(['role' => 'student']);
            foreach ($users['data'] ?? [] as $u) {
                if (filter_var($u['email'] ?? null, FILTER_VALIDATE_EMAIL)) {
                    $recipients[] = ['email' => $u['email'], 'name' => $u['name'] ?? null];
                }
            }
        }

        if (empty($recipients)) {
            return back()->withInput()->with('error', 'No valid email addresses found in selection.');
        }

        $result = $this->scheduler->scheduleBatch(
            $recipients,
            $validated['campaign'],
            $validated['mail_type'],
            auth()->id()
        );

        $msg = "Scheduled {$result['total']} emails. ({$result['queued_this_week']} queued for this week every 2 mins after 6 PM, {$result['deferred_next_week']} deferred to next week due to 7-day cooldown).";

        return back()->with('success', $msg);
    }

    /**
     * Add single email ID on admin side
     */
    public function storeContact(Request $request)
    {
        $this->authorizeMail();

        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:120',
            'tags' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $this->contactManager->addContact(
            $validated['email'],
            $validated['name'] ?? null,
            $validated['tags'] ?? null,
            $validated['notes'] ?? null
        );

        return back()->with('success', "Email ID {$validated['email']} added to contacts.");
    }

    /**
     * Bulk import email IDs (Paste or CSV) on admin side
     */
    public function importContacts(Request $request)
    {
        $this->authorizeMail();

        $request->validate([
            'contacts_data' => 'nullable|string|max:200000',
            'contacts_file' => 'nullable|file|mimes:csv,txt|max:5120',
            'tags' => 'nullable|string|max:255',
        ]);

        $rawInput = (string) $request->input('contacts_data', '');

        if ($request->hasFile('contacts_file')) {
            $rawInput .= "\n" . file_get_contents($request->file('contacts_file')->getRealPath());
        }

        if (empty(trim($rawInput))) {
            return back()->with('error', 'Please provide email data or upload a CSV/TXT file.');
        }

        $res = $this->contactManager->importBulkContacts($rawInput, $request->input('tags'));

        return back()->with('success', "Successfully imported {$res['imported']} contacts (Skipped: {$res['skipped']}).");
    }

    /**
     * Delete contact
     */
    public function deleteContact(MarketingContact $contact)
    {
        $this->authorizeMail();
        $contact->delete();

        return back()->with('success', 'Contact removed from list.');
    }

    /**
     * Cancel queued email
     */
    public function cancelScheduled(ScheduledCampaignEmail $email)
    {
        $this->authorizeMail();
        if (in_array($email->status, ['pending', 'deferred'], true)) {
            $email->update(['status' => 'cancelled']);
            return back()->with('success', "Cancelled scheduled email to {$email->recipient_email}.");
        }

        return back()->with('error', 'Cannot cancel an email that is already processed.');
    }

    /**
     * Retry failed email
     */
    public function retryScheduled(ScheduledCampaignEmail $email)
    {
        $this->authorizeMail();
        $email->update([
            'status' => 'pending',
            'scheduled_at' => now(),
            'error_message' => null,
        ]);

        return back()->with('success', "Queued {$email->recipient_email} for immediate retry.");
    }

    public function saveDraft(Request $request)
    {
        $this->authorizeMail();

        $validated = $request->validate(array_merge(
            ['draft_name' => 'required|string|max:100'],
            $this->campaignRules()
        ));

        MailDraft::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'name' => $validated['draft_name'],
            ],
            [
                'mail_type' => $validated['mail_type'],
                'content' => $validated['campaign'],
            ]
        );

        return back()->with('success', 'Mail draft saved.');
    }

    public function deleteDraft(MailDraft $draft)
    {
        $this->authorizeMail();
        abort_unless($draft->user_id === auth()->id(), 403);

        $draft->delete();

        return back()->with('success', 'Mail draft deleted.');
    }

    private function campaignDefaults(): array
    {
        return [
            'subject' => 'Special Offer — Get 20% Off Assignment Help',
            'preheader' => 'Expert academic support is now 20% more affordable.',
            'headline' => 'Save 20% on your next order',
            'message' => 'Get expert help with assignments, essays, research papers, and more — delivered on time, every time.',
            'offer_label' => '20% OFF',
            'promo_code' => 'WELCOME20',
            'cta_text' => 'Claim your discount',
            'cta_url' => rtrim((string) config('services.main_api.site_url', 'https://assignmenthelpusa.com'), '/') . '/order',
            'accent_color' => '#e63946',
        ];
    }

    private function campaignRules(): array
    {
        return [
            'mail_type' => 'required|in:promotional,direct',
            'campaign.subject' => ['required', 'string', 'max:150', 'not_regex:/[\r\n]/'],
            'campaign.preheader' => 'nullable|string|max:180',
            'campaign.headline' => 'required', 'string', 'max:140',
            'campaign.message' => 'required', 'string', 'max:2000',
            'campaign.offer_label' => 'nullable|required_if:mail_type,promotional|string|max:60',
            'campaign.promo_code' => ['nullable', 'prohibited_if:mail_type,direct', 'string', 'max:32', 'regex:/^[A-Za-z0-9_-]+$/'],
            'campaign.cta_text' => 'required|string|max:60',
            'campaign.cta_url' => 'required|url:http,https|max:2048',
            'campaign.accent_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ];
    }

    private function authorizeMail(): void
    {
        abort_unless(
            auth()->check() && in_array(auth()->user()->role, ['admin', 'manager'], true),
            403
        );
    }
}
