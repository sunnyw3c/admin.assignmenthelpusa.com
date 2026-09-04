<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $campaign['subject'] }}</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f4f7;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">
<div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;">{{ $campaign['preheader'] }}</div>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f7;padding:40px 12px;">
    <tr>
        <td align="center">
            <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">
                <tr>
                    <td style="background-color:{{ $campaign['accent_color'] }};border-radius:12px 12px 0 0;padding:30px 40px;text-align:center;">
                        <h1 style="margin:0;color:#ffffff;font-size:22px;font-weight:700;letter-spacing:-0.3px;">Assignment Help USA</h1>
                        <p style="margin:6px 0 0;color:rgba(255,255,255,0.8);font-size:13px;">Expert Academic Assistance</p>
                    </td>
                </tr>
                <tr>
                    <td style="background:#ffffff;padding:36px 40px;">
                        <p style="margin:0 0 16px;font-size:16px;color:#374151;line-height:1.6;">Hi there,</p>
                        <h2 style="margin:0 0 14px;font-size:25px;line-height:1.3;color:#111827;letter-spacing:-0.4px;">{{ $campaign['headline'] }}</h2>
                        <p style="margin:0 0 24px;font-size:15px;color:#4b5563;line-height:1.7;">{!! nl2br(e($campaign['message'])) !!}</p>

                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
                            <tr>
                                <td style="background:#fafafa;border:1px dashed {{ $campaign['accent_color'] }};border-radius:10px;padding:20px 24px;text-align:center;">
                                    <p style="margin:0 0 4px;font-size:13px;color:#6b7280;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Special offer</p>
                                    <p style="margin:0;font-size:28px;font-weight:700;color:{{ $campaign['accent_color'] }};">{{ $campaign['offer_label'] }}</p>
                                    @if($campaign['promo_code'])
                                        <p style="margin:7px 0 0;font-size:13px;color:#6b7280;">Use code <strong style="color:#111827;">{{ $campaign['promo_code'] }}</strong></p>
                                    @endif
                                </td>
                            </tr>
                        </table>

                        <p style="margin:0 0 12px;font-size:14px;font-weight:600;color:#111827;">Why students choose us:</p>
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
                            @foreach(['Expert help across academic subjects', 'Original, quality-checked work', '24/7 support and on-time delivery'] as $item)
                                <tr>
                                    <td style="padding:5px 0;font-size:14px;color:#4b5563;"><span style="color:{{ $campaign['accent_color'] }};font-weight:700;margin-right:8px;">✓</span> {{ $item }}</td>
                                </tr>
                            @endforeach
                        </table>

                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
                            <tr>
                                <td align="center">
                                    <a href="{{ $campaign['cta_url'] }}" style="display:inline-block;background-color:{{ $campaign['accent_color'] }};color:#ffffff;font-size:15px;font-weight:600;text-decoration:none;padding:14px 36px;border-radius:8px;">{{ $campaign['cta_text'] }} →</a>
                                </td>
                            </tr>
                        </table>

                        <p style="margin:0;font-size:13px;color:#9ca3af;line-height:1.6;">Have questions? Reply to this email or visit <a href="{{ config('app.url') }}" style="color:{{ $campaign['accent_color'] }};text-decoration:none;">{{ config('app.url') }}</a>.</p>
                    </td>
                </tr>
                <tr>
                    <td style="background:#f9fafb;border-top:1px solid #e5e7eb;border-radius:0 0 12px 12px;padding:20px 40px;text-align:center;">
                        <p style="margin:0;font-size:12px;color:#9ca3af;line-height:1.6;">© {{ date('Y') }} Assignment Help USA. All rights reserved.<br>You are receiving this email because you have an account on our platform.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
