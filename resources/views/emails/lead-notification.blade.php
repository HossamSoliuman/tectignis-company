@php
    $sourceLabels = [
        'contact' => 'Contact Enquiry',
        'consultation' => 'Consultation Request',
        'career' => 'Job Application',
        'newsletter' => 'Newsletter Subscription',
    ];
    $rows = array_filter([
        'Name' => $lead->name,
        'Email' => $lead->email,
        'Phone' => $lead->phone,
        'Subject' => $lead->subject,
        'Source' => $sourceLabels[$lead->source] ?? ucfirst((string) $lead->source),
    ]);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Enquiry</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;color:#1e293b;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;">
                    <tr>
                        <td style="background:linear-gradient(135deg,#d946ef,#9333ea);padding:20px 28px;color:#ffffff;font-size:18px;font-weight:700;">
                            {{ config('mail.from.name', config('app.name')) }} — New Website Enquiry
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px;">
                            <p style="margin:0 0 20px;font-size:14px;color:#475569;">
                                You have received a new <strong>{{ $sourceLabels[$lead->source] ?? 'enquiry' }}</strong> from the website.
                            </p>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                                @foreach ($rows as $label => $value)
                                    <tr>
                                        <td style="padding:8px 0;width:120px;vertical-align:top;font-size:13px;font-weight:700;color:#64748b;">{{ $label }}</td>
                                        <td style="padding:8px 0;font-size:14px;color:#1e293b;">{{ $value }}</td>
                                    </tr>
                                @endforeach
                            </table>

                            @if (filled($lead->message))
                                <div style="margin-top:20px;padding:16px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;">
                                    <p style="margin:0 0 6px;font-size:13px;font-weight:700;color:#64748b;">Message</p>
                                    <p style="margin:0;font-size:14px;line-height:1.6;white-space:pre-wrap;">{{ $lead->message }}</p>
                                </div>
                            @endif

                            @if ($lead->attachment)
                                <p style="margin:20px 0 0;font-size:14px;">
                                    <a href="{{ \App\Models\Setting::imageUrl($lead->attachment) ?? asset('uploads/'.$lead->attachment) }}" style="color:#9333ea;font-weight:700;">View attachment</a>
                                </p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 28px;border-top:1px solid #e2e8f0;font-size:12px;color:#94a3b8;">
                            Sent {{ $lead->created_at?->format('M j, Y g:i A') ?? now()->format('M j, Y g:i A') }} ·
                            Manage leads in your <a href="{{ route('admin.leads.index') }}" style="color:#9333ea;">admin dashboard</a>.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
