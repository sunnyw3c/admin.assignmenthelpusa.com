<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class PromotionalEmail extends Mailable
{
    public function __construct(
        public array $campaign = [],
    ) {}

    public static function defaults(): array
    {
        return [
            'subject' => 'Special Offer — Get 20% Off Assignment Help',
            'preheader' => 'Expert academic support is now 20% more affordable.',
            'headline' => 'Save 20% on your next order',
            'message' => 'Get expert help with assignments, essays, research papers, and more — delivered on time, every time.',
            'offer_label' => '20% OFF',
            'promo_code' => 'WELCOME20',
            'cta_text' => 'Claim your discount',
            'cta_url' => rtrim((string) config('app.url'), '/') . '/order',
            'accent_color' => '#e63946',
        ];
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->campaign['subject'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.promotional',
            text: 'mail.promotional-text',
            with: ['campaign' => $this->campaign],
        );
    }
}
