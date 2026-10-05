@php
    $submittedAt = ($lead->created_at ?? now())->timezone(config('app.timezone'));
    $rows = array_filter([
        'Lead ID' => $lead->id ? $lead->reference() : null,
        'Name' => $lead->name,
        'Company' => $lead->company,
        'Email' => $lead->email,
        'Phone' => $lead->phone,
        'Country' => $lead->country,
        'Service' => $lead->service,
        'Subject' => $lead->subject,
        'Source' => $lead->sourceLabel(),
    ]);
    $qualification = array_filter([
        'Budget' => $lead->budgetLabel(),
        'Timeline' => $lead->timelineLabel(),
    ]);
    $attribution = array_filter([
        'Page' => $lead->page_url,
        'UTM source' => $lead->utm_source,
        'UTM medium' => $lead->utm_medium,
        'UTM campaign' => $lead->utm_campaign,
        'UTM term' => $lead->utm_term,
        'UTM content' => $lead->utm_content,
    ]);
    $sections = array_filter([
        'Lead details' => $rows,
        'Qualification' => $qualification,
        'Attribution' => $attribution,
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
                                You have received a new <strong>{{ $lead->sourceLabel() }}</strong> from the website. Reply to this email to answer the visitor directly.
                            </p>
                            @foreach ($sections as $heading => $items)
                                <p style="margin:{{ $loop->first ? '0' : '20px' }} 0 6px;font-size:12px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:#9333ea;">{{ $heading }}</p>
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                                    @foreach ($items as $label => $value)
                                        <tr>
                                            <td style="padding:6px 0;width:130px;vertical-align:top;font-size:13px;font-weight:700;color:#64748b;">{{ $label }}</td>
                                            <td style="padding:6px 0;font-size:14px;color:#1e293b;word-break:break-word;">{{ $value }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            @endforeach

                            @if (filled($lead->message))
                                <div style="margin-top:20px;padding:16px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;">
                                    <p style="margin:0 0 6px;font-size:13px;font-weight:700;color:#64748b;">{{ $lead->sourceEnum()?->isEnquiry() ? 'Project description' : 'Message' }}</p>
                                    <p style="margin:0;font-size:14px;line-height:1.6;white-space:pre-wrap;">{{ $lead->message }}</p>
                                </div>
                            @endif

                            @if ($lead->attachment)
                                <p style="margin:20px 0 0;font-size:14px;">
                                    <a href="{{ \App\Models\Setting::imageUrl($lead->attachment) ?? asset('uploads/'.$lead->attachment) }}" style="color:#9333ea;font-weight:700;">View attachment</a>
                                </p>
                            @endif

                            @if ($lead->id)
                                <p style="margin:24px 0 0;">
                                    <a href="{{ route('admin.leads.show', $lead) }}" style="display:inline-block;padding:10px 18px;background:#9333ea;color:#ffffff;border-radius:8px;font-size:14px;font-weight:700;text-decoration:none;">Open lead in admin</a>
                                </p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 28px;border-top:1px solid #e2e8f0;font-size:12px;color:#94a3b8;">
                            Submitted {{ $submittedAt->format('M j, Y g:i A') }} ({{ $submittedAt->format('T') }}) ·
                            Manage leads in your <a href="{{ route('admin.leads.index') }}" style="color:#9333ea;">admin dashboard</a>.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
