@php
    $siteName = \App\Models\Setting::get('site_name') ?: config('app.name');
    $firstName = \Illuminate\Support\Str::before(trim($application->name), ' ') ?: $application->name;
    $position = $application->job_title ?: $application->designation_name;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>We have received your application</title>
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;color:#1e293b;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;">
                    <tr>
                        <td style="background:linear-gradient(135deg,#0ea5e9,#19587f);padding:20px 28px;color:#ffffff;font-size:18px;font-weight:700;">
                            {{ $siteName }} Careers
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px;font-size:14px;line-height:1.7;color:#334155;">
                            <p style="margin:0 0 16px;">Hi {{ $firstName }},</p>
                            <p style="margin:0 0 16px;">
                                Thank you for applying to {{ $siteName }}{{ $position ? ' for the '.$position.' role' : '' }}. We have received your application and CV, and our HR team will review them.
                            </p>
                            <p style="margin:0 0 16px;">If your profile matches what we are looking for, we will contact you about the next steps. Your application reference is <strong>{{ $application->reference() }}</strong>.</p>
                            <p style="margin:0;">Regards,<br>The {{ $siteName }} HR team</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 28px;border-top:1px solid #e2e8f0;font-size:12px;color:#94a3b8;">
                            You are receiving this because you applied for a job on {{ parse_url(config('app.url'), PHP_URL_HOST) ?: config('app.url') }}.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
