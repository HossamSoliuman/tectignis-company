<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Admin-managed Google reCAPTCHA v2 ("I'm not a robot" checkbox) protection for
 * every public form (spec §28.1–28.3).
 *
 * Keys live in the `captcha` settings group so they can be rotated without a
 * deployment; the secret is encrypted at rest and never rendered back.
 * Verification fails closed: if Google cannot confirm the token the
 * submission is rejected.
 */
class RecaptchaService
{
    public const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    public const RESPONSE_FIELD = 'g-recaptcha-response';

    /**
     * Whether public forms must currently pass a CAPTCHA check.
     */
    public function isEnabled(): bool
    {
        return Setting::get('recaptcha_enabled') === '1' && $this->isConfigured();
    }

    /**
     * Both keys are saved (regardless of the on/off switch).
     */
    public function isConfigured(): bool
    {
        return filled($this->siteKey()) && filled($this->secretKey());
    }

    public function siteKey(): ?string
    {
        return Setting::get('recaptcha_site_key') ?: null;
    }

    /**
     * The decrypted secret key. Null when unset or when it cannot be decrypted
     * (e.g. APP_KEY was rotated), so a broken secret reads as "not configured".
     */
    public function secretKey(): ?string
    {
        $stored = Setting::get('recaptcha_secret_key');

        if (blank($stored)) {
            return null;
        }

        try {
            return Crypt::decryptString($stored);
        } catch (DecryptException) {
            return null;
        }
    }

    /**
     * The secret reduced to its last four characters for display.
     */
    public function maskedSecret(): ?string
    {
        $secret = $this->secretKey();

        return $secret ? str_repeat('•', 12).substr($secret, -4) : null;
    }

    /**
     * Persist a configuration change. A null secret keeps the existing one.
     */
    public function saveConfiguration(?string $siteKey, ?string $secretKey, bool $enabled, User $updatedBy): void
    {
        Setting::set('recaptcha_site_key', $siteKey, 'captcha');

        if (filled($secretKey)) {
            Setting::set('recaptcha_secret_key', Crypt::encryptString($secretKey), 'captcha');
        }

        Setting::set('recaptcha_enabled', $enabled ? '1' : '0', 'captcha');
        Setting::set('recaptcha_updated_by', (string) $updatedBy->id, 'captcha');
        Setting::set('recaptcha_updated_at', now()->toIso8601String(), 'captcha');
    }

    /**
     * Ask Google whether a widget response token is valid. Rejects missing
     * tokens, Google-reported failures and unreachable/erroring responses.
     * Failures are logged without the token itself.
     */
    public function verify(?string $token, ?string $ip = null): bool
    {
        if (blank($token)) {
            Log::notice('reCAPTCHA rejected: no token submitted', ['ip' => $ip]);

            return false;
        }

        $result = $this->siteverify($token, $ip);

        if ($result === null) {
            Log::warning('reCAPTCHA rejected: verification service unreachable', ['ip' => $ip]);

            return false;
        }

        if (! ($result['success'] ?? false)) {
            Log::notice('reCAPTCHA rejected by Google', [
                'ip' => $ip,
                'error_codes' => $result['error-codes'] ?? [],
            ]);

            return false;
        }

        return true;
    }

    /**
     * Check the saved secret key with Google without a widget token: an
     * invalid secret yields `invalid-input-secret`, while a valid one only
     * complains about the (deliberately) bogus response.
     *
     * @return array{ok: bool, message: string}
     */
    public function testSecret(): array
    {
        if (! $this->isConfigured()) {
            return ['ok' => false, 'message' => 'Save both the site key and the secret key first.'];
        }

        $result = $this->siteverify('configuration-test');

        if ($result === null) {
            return ['ok' => false, 'message' => 'Could not reach Google to verify the configuration. Try again shortly.'];
        }

        $errors = $result['error-codes'] ?? [];

        if (in_array('invalid-input-secret', $errors, true) || in_array('missing-input-secret', $errors, true)) {
            return ['ok' => false, 'message' => 'Google rejected the secret key. Check that it matches the site key.'];
        }

        return ['ok' => true, 'message' => 'Google accepted the secret key. Tick the checkbox below and run the test again to confirm the site key too.'];
    }

    /**
     * @return array<string, mixed>|null Google's JSON reply, or null when unreachable.
     */
    private function siteverify(string $token, ?string $ip = null): ?array
    {
        try {
            $response = Http::asForm()
                ->timeout(5)
                ->connectTimeout(3)
                ->post(self::VERIFY_URL, array_filter([
                    'secret' => $this->secretKey(),
                    'response' => $token,
                    'remoteip' => $ip,
                ]));
        } catch (ConnectionException) {
            return null;
        }

        if (! $response->successful()) {
            return null;
        }

        $json = $response->json();

        return is_array($json) ? $json : null;
    }
}
