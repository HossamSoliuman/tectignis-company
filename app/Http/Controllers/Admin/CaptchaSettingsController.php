<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use App\Services\RecaptchaService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * Settings → CAPTCHA (spec §28.2). Super Admin only.
 */
class CaptchaSettingsController extends Controller
{
    public function __construct(private RecaptchaService $recaptcha) {}

    public function edit(): View
    {
        $updatedAt = Setting::get('recaptcha_updated_at');

        return view('admin.captcha.edit', [
            'siteKey' => $this->recaptcha->siteKey(),
            'maskedSecret' => $this->recaptcha->maskedSecret(),
            'enabled' => Setting::get('recaptcha_enabled') === '1',
            'configured' => $this->recaptcha->isConfigured(),
            'active' => $this->recaptcha->isEnabled(),
            'updatedBy' => User::find((int) Setting::get('recaptcha_updated_by'))?->name,
            'updatedAt' => $updatedAt ? Carbon::parse($updatedAt)->timezone(config('app.timezone')) : null,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'site_key' => ['nullable', 'string', 'max:255'],
            'secret_key' => ['nullable', 'string', 'max:255'],
            'enabled' => ['nullable', 'boolean'],
        ]);

        $enabled = $request->boolean('enabled');
        $hasSecret = filled($data['secret_key'] ?? null) || filled($this->recaptcha->secretKey());

        if ($enabled && (blank($data['site_key'] ?? null) || ! $hasSecret)) {
            return back()->withInput()->withErrors([
                'enabled' => 'Add both the site key and the secret key before enabling CAPTCHA.',
            ]);
        }

        $this->recaptcha->saveConfiguration(
            $data['site_key'] ?? null,
            $data['secret_key'] ?? null,
            $enabled,
            $request->user(),
        );

        return back()->with('status', 'CAPTCHA settings saved.');
    }

    /**
     * With a ticked widget, run a full round trip (site key + secret key);
     * without one, check the secret key alone.
     */
    public function test(Request $request): RedirectResponse
    {
        $token = $request->input(RecaptchaService::RESPONSE_FIELD);

        if (blank($token)) {
            $result = $this->recaptcha->testSecret();

            return back()->with($result['ok'] ? 'status' : 'captcha_test_error', $result['message']);
        }

        return $this->recaptcha->verify($token, $request->ip())
            ? back()->with('status', 'reCAPTCHA is working: Google verified the checkbox with your site key and secret key.')
            : back()->with('captcha_test_error', 'Google could not verify the checkbox. Check that the keys belong to the same reCAPTCHA v2 site and that this domain is allowed.');
    }
}
