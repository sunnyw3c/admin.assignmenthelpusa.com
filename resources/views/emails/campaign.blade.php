<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $campaign['subject'] ?? 'Assignment Help USA' }}</title>
    <style>
        body, table, td, p, a, li, blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            outline: none;
            text-decoration: none;
        }
        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            height: 100% !important;
            background-color: #f4f4f7;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #333333;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f4f4f7;
            padding: 30px 15px;
        }
        .email-container {
            max-width: 580px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
        }
        .accent-bar {
            height: 6px;
            width: 100%;
            background-color: {{ $campaign['accent_color'] ?? '#e63946' }};
        }
        .email-body {
            padding: 36px 32px;
        }
        .brand-logo {
            font-size: 20px;
            font-weight: 800;
            color: #111827;
            text-decoration: none;
            letter-spacing: -0.5px;
        }
        .brand-logo span {
            color: {{ $campaign['accent_color'] ?? '#e63946' }};
        }
        .eyebrow {
            margin-top: 24px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #9ca3af;
        }
        .headline {
            margin: 8px 0 16px 0;
            font-size: 22px;
            font-weight: 800;
            line-height: 1.3;
            color: #111827;
        }
        .message-content {
            font-size: 15px;
            line-height: 1.65;
            color: #4b5563;
            white-space: pre-line;
            margin: 0 0 24px 0;
        }
        .offer-box {
            margin: 24px 0;
            padding: 18px;
            background-color: #f9fafb;
            border: 2px dashed {{ $campaign['accent_color'] ?? '#e63946' }};
            border-radius: 8px;
            text-align: center;
        }
        .offer-label {
            font-size: 18px;
            font-weight: 800;
            color: {{ $campaign['accent_color'] ?? '#e63946' }};
            margin: 0;
        }
        .promo-code-text {
            margin: 6px 0 0 0;
            font-size: 13px;
            color: #6b7280;
        }
        .promo-code-badge {
            font-family: monospace;
            font-weight: 700;
            background-color: #e5e7eb;
            color: #111827;
            padding: 3px 8px;
            border-radius: 4px;
            letter-spacing: 1px;
        }
        .cta-container {
            text-align: center;
            margin: 28px 0 10px 0;
        }
        .cta-button {
            display: inline-block;
            background-color: {{ $campaign['accent_color'] ?? '#e63946' }};
            color: #ffffff !important;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            padding: 14px 32px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        }
        .email-footer {
            padding: 24px 32px 32px 32px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            line-height: 1.6;
            border-top: 1px solid #f3f4f6;
            background-color: #fafafa;
        }
        .email-footer a {
            color: #6b7280;
            text-decoration: underline;
        }
        .preheader {
            display: none !important;
            visibility: hidden;
            mso-hide: all;
            font-size: 1px;
            line-height: 1px;
            max-height: 0px;
            max-width: 0px;
            opacity: 0;
            overflow: hidden;
        }
    </style>
</head>
<body>
    @if(!empty($campaign['preheader']))
        <span class="preheader">{{ $campaign['preheader'] }}</span>
    @endif

    <div class="email-wrapper">
        <div class="email-container">
            <div class="accent-bar"></div>

            <div class="email-body">
                <a href="{{ config('services.main_api.site_url', 'https://assignmenthelpusa.com') }}" class="brand-logo" target="_blank">
                    AssignmentHelp<span>USA</span>
                </a>

                @if(!empty($recipientName))
                    <p class="eyebrow">Hello {{ $recipientName }},</p>
                @else
                    <p class="eyebrow">A Message For You</p>
                @endif

                <h1 class="headline">{{ $campaign['headline'] ?? 'Special Update from Assignment Help USA' }}</h1>

                <div class="message-content">{{ $campaign['message'] ?? '' }}</div>

                @if(($mailType ?? 'promotional') === 'promotional' && !empty($campaign['offer_label']))
                    <div class="offer-box">
                        <p class="offer-label">{{ $campaign['offer_label'] }}</p>
                        @if(!empty($campaign['promo_code']))
                            <p class="promo-code-text">Use coupon code: <span class="promo-code-badge">{{ $campaign['promo_code'] }}</span></p>
                        @endif
                    </div>
                @endif

                @if(!empty($campaign['cta_url']) && !empty($campaign['cta_text']))
                    <div class="cta-container">
                        <a href="{{ $campaign['cta_url'] }}" class="cta-button" target="_blank">
                            {{ $campaign['cta_text'] }}
                        </a>
                    </div>
                @endif
            </div>

            <div class="email-footer">
                <p>&copy; {{ date('Y') }} Assignment Help USA. All rights reserved.</p>
                <p>24/7 Academic Assistance &bull; Confidential &bull; Plagiarism-Free</p>
                <p>
                    <a href="{{ config('services.main_api.site_url', 'https://assignmenthelpusa.com') }}/privacy-policy" target="_blank">Privacy Policy</a> &bull;
                    <a href="{{ config('services.main_api.site_url', 'https://assignmenthelpusa.com') }}/contact" target="_blank">Support</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
