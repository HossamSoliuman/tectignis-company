<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    /**
     * SMTP server settings (group "smtp"), consumed by AppServiceProvider to
     * override the runtime mail configuration.
     *
     * @var list<string>
     */
    private const SMTP_KEYS = [
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'smtp_from_address',
        'smtp_from_name',
    ];

    /**
     * Per-form recipient settings (group "mail"). Each maps to a lead source.
     *
     * @var list<string>
     */
    private const RECIPIENT_KEYS = [
        'mail_to_default',
        'mail_to_contact',
        'mail_to_consultation',
        'mail_to_career',
        'mail_to_newsletter',
    ];

    public function edit(): View
    {
        $values = Setting::values();

        return view('admin.mail.edit', [
            'smtp' => collect(self::SMTP_KEYS)->mapWithKeys(fn (string $key) => [$key => $values->get($key)]),
            'recipients' => collect(self::RECIPIENT_KEYS)->mapWithKeys(fn (string $key) => [$key => $values->get($key)]),
            'siteEmail' => $values->get('site_email'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'smtp_host' => ['nullable', 'string', 'max:255'],
            'smtp_port' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'smtp_username' => ['nullable', 'string', 'max:255'],
            'smtp_password' => ['nullable', 'string', 'max:255'],
            'smtp_encryption' => ['nullable', 'in:tls,ssl'],
            'smtp_from_address' => ['nullable', 'email', 'max:255'],
            'smtp_from_name' => ['nullable', 'string', 'max:255'],
            'mail_to_default' => ['nullable', 'email', 'max:255'],
            'mail_to_contact' => ['nullable', 'email', 'max:255'],
            'mail_to_consultation' => ['nullable', 'email', 'max:255'],
            'mail_to_career' => ['nullable', 'email', 'max:255'],
            'mail_to_newsletter' => ['nullable', 'email', 'max:255'],
        ]);

        foreach (self::SMTP_KEYS as $key) {
            Setting::set($key, $data[$key] ?? null, 'smtp');
        }

        foreach (self::RECIPIENT_KEYS as $key) {
            Setting::set($key, $data[$key] ?? null, 'mail');
        }

        return back()->with('status', 'Mail settings saved.');
    }

    public function test(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'test_email' => ['required', 'email', 'max:255'],
        ]);

        try {
            Mail::raw(
                "This is a test email from your website's mail configuration.\n\nIf you received this, outgoing email is working correctly.",
                function ($message) use ($data) {
                    $message->to($data['test_email'])
                        ->subject('Test email · '.config('mail.from.name', config('app.name')));
                }
            );
        } catch (\Throwable $e) {
            return back()->with('mail_test_error', 'Failed to send: '.$e->getMessage());
        }

        return back()->with('status', 'Test email sent to '.$data['test_email'].'. Check the inbox to confirm delivery.');
    }
}
