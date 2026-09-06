<?php

namespace App\Http\Controllers;

use App\Services\AdminApiService;
use App\Models\MailDraft;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MailController extends Controller
{
    private AdminApiService $api;

    public function __construct()
    {
        $this->api = new AdminApiService();
    }

    public function index()
    {
        $this->authorizeMail();

        $users = $this->api->getUsers(['role' => 'student']);

        $recipients = collect($users['data'] ?? [])
            ->filter(fn (array $user) => filter_var($user['email'] ?? null, FILTER_VALIDATE_EMAIL))
            ->map(function (array $user) {
                $orders = (int) ($user['order_count'] ?? 0);
                $joined = isset($user['created_at']) ? Carbon::parse($user['created_at']) : null;
                $daysSinceJoining = $joined ? (int) $joined->diffInDays(now()) : null;

                // A transparent engagement score keeps the most useful leads at
                // the top without pretending to be a predictive black box.
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

        return view('mail.index', compact('recipients', 'campaign', 'mailType', 'drafts'));
    }

    public function send(Request $request)
    {
        $this->authorizeMail();

        $validated = $request->validate(array_merge(
            ['email' => 'required|email|max:255'],
            $this->campaignRules(),
        ));

        $result = $this->api->sendPromotionalEmail(
            $validated['email'],
            $validated['campaign'],
            $validated['mail_type'],
        );

        if ($result['status'] >= 200 && $result['status'] < 300) {
            $label = $validated['mail_type'] === 'direct' ? 'Direct email' : 'Promotional email';

            return back()->with('success', $label . ' sent to ' . $request->email);
        }

        $error = $result['data']['message'] ?? 'Failed to send email. Please try again.';
        return back()->withInput()->with('error', $error);
    }

    public function saveDraft(Request $request)
    {
        $this->authorizeMail();

        $validated = $request->validate(array_merge(
            ['draft_name' => 'required|string|max:100'],
            $this->campaignRules(),
        ));

        MailDraft::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'name' => $validated['draft_name'],
            ],
            [
                'mail_type' => $validated['mail_type'],
                'content' => $validated['campaign'],
            ],
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
            'campaign.headline' => 'required|string|max:140',
            'campaign.message' => 'required|string|max:2000',
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
            403,
        );
    }
}
