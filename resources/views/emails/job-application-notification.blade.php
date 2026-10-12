@php
    $submittedAt = ($application->created_at ?? now())->timezone(config('app.timezone'));
    $rows = array_filter([
        'Application ID' => $application->id ? $application->reference() : null,
        'Position' => $application->appliedFor(),
        'Designation' => $application->designation_name,
        'Name' => $application->name,
        'Email' => $application->email,
        'Phone' => $application->phone,
        'Current location' => $application->location,
        'Experience' => $application->experienceLabel(),
        'Current role' => $application->current_role,
        'Notice period' => $application->notice_period,
        'LinkedIn' => $application->linkedin_url,
        'Portfolio / GitHub' => $application->portfolio_url,
        'CV' => $application->hasResume() ? 'Attached in the Careers admin' : null,
    ]);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Job Application</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;color:#1e293b;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;">
                    <tr>
                        <td style="background:linear-gradient(135deg,#0ea5e9,#19587f);padding:20px 28px;color:#ffffff;font-size:18px;font-weight:700;">
                            {{ config('mail.from.name', config('app.name')) }} — New Job Application
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px;">
                            <p style="margin:0 0 20px;font-size:14px;color:#475569;">
                                A candidate has applied through the careers page. Reply to this email to contact them directly.
                            </p>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                                @foreach ($rows as $label => $value)
                                    <tr>
                                        <td style="padding:6px 0;width:140px;vertical-align:top;font-size:13px;font-weight:700;color:#64748b;">{{ $label }}</td>
                                        <td style="padding:6px 0;font-size:14px;color:#1e293b;word-break:break-word;">{{ $value }}</td>
                                    </tr>
                                @endforeach
                            </table>

                            @if (filled($application->cover_letter))
                                <div style="margin-top:20px;padding:16px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;">
                                    <p style="margin:0 0 6px;font-size:13px;font-weight:700;color:#64748b;">Cover letter</p>
                                    <p style="margin:0;font-size:14px;line-height:1.6;white-space:pre-wrap;">{{ $application->cover_letter }}</p>
                                </div>
                            @endif

                            @if ($application->id)
                                <p style="margin:24px 0 0;">
                                    <a href="{{ route('admin.job-applications.show', $application) }}" style="display:inline-block;padding:10px 18px;background:#19587f;color:#ffffff;border-radius:8px;font-size:14px;font-weight:700;text-decoration:none;">Open application in admin</a>
                                </p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 28px;border-top:1px solid #e2e8f0;font-size:12px;color:#94a3b8;">
                            Submitted {{ $submittedAt->format('M j, Y g:i A') }} ({{ $submittedAt->format('T') }}) ·
                            Manage applications in the <a href="{{ route('admin.job-applications.index') }}" style="color:#19587f;">Careers admin</a>.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
