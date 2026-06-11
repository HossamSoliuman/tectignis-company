<?php

namespace App\Rules;

use App\Models\Setting;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class Recaptcha implements ValidationRule
{
    /**
     * Verify a reCAPTCHA v3 token with Google. The check is skipped entirely
     * when no secret key is configured, and fails open on network errors so a
     * Google outage never blocks a lead.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secret = Setting::get('recaptcha_secret_key');

        if (! $secret) {
            return;
        }

        if (! is_string($value) || $value === '') {
            $fail('The reCAPTCHA verification failed. Please try again.');

            return;
        }

        try {
            $response = Http::asForm()
                ->timeout(5)
                ->connectTimeout(3)
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $secret,
                    'response' => $value,
                ]);
        } catch (ConnectionException) {
            return;
        }

        if (! $response->json('success')) {
            $fail('The reCAPTCHA verification failed. Please try again.');
        }
    }
}
