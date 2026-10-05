<?php

namespace App\Rules;

use App\Services\RecaptchaService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Request;

class Recaptcha implements ValidationRule
{
    /**
     * Run even when the token field is missing, so a bot cannot bypass the
     * check by leaving it out.
     */
    public bool $implicit = true;

    /**
     * Verify a reCAPTCHA v2 widget response with Google. Passes untouched when
     * an administrator has switched CAPTCHA off or no keys are configured.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $recaptcha = app(RecaptchaService::class);

        if (! $recaptcha->isEnabled()) {
            return;
        }

        if (! $recaptcha->verify(is_string($value) ? $value : null, Request::ip())) {
            $fail('Please confirm you are not a robot and try again.');
        }
    }
}
