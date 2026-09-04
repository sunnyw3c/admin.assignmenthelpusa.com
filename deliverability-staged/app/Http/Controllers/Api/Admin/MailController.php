<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PromotionalEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'campaign' => 'sometimes|array',
            'campaign.subject' => ['required_with:campaign', 'string', 'max:150', 'not_regex:/[\r\n]/'],
            'campaign.preheader' => 'nullable|string|max:180',
            'campaign.headline' => 'required_with:campaign|string|max:140',
            'campaign.message' => 'required_with:campaign|string|max:2000',
            'campaign.offer_label' => 'required_with:campaign|string|max:60',
            'campaign.promo_code' => ['nullable', 'string', 'max:32', 'regex:/^[A-Za-z0-9_-]+$/'],
            'campaign.cta_text' => 'required_with:campaign|string|max:60',
            'campaign.cta_url' => 'required_with:campaign|url:http,https|max:2048',
            'campaign.accent_color' => ['required_with:campaign', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ]);

        $campaign = array_merge(
            PromotionalEmail::defaults(),
            $validated['campaign'] ?? [],
        );

        Mail::to($validated['email'])->send(new PromotionalEmail($campaign));

        return response()->json([
            'message' => 'Email sent successfully.',
            'subject' => $campaign['subject'],
        ]);
    }
}
