<?php

namespace App\Rules;

use App\Support\PhoneNumbers;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneNumber implements ValidationRule
{
    /**
     * @param  string|null  $isoCode  The country picked beside the number.
     */
    public function __construct(private ?string $isoCode) {}

    /**
     * Check the number against the picked country's numbering plan. A number
     * typed with its own "+" code is checked against that code instead.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! PhoneNumbers::isValid($value, $this->isoCode)) {
            $fail(__('forms.messages.phone_invalid'));
        }
    }
}
