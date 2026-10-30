<?php

namespace App\Support;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\ValidationResult;

/**
 * Phone numbers from the enquiry form, validated against the calling code
 * picked beside them and stored in E.164 form (+447400123456), so every lead
 * is stored the same way whatever the visitor typed.
 */
final class PhoneNumbers
{
    /**
     * Whether the number has the right length for the picked country, or for
     * the country of its own "+" code when typed in international form.
     *
     * Only the length is checked, not whether the area code or range is
     * assigned: rejecting a real lead because of outdated numbering data costs
     * more than storing a mistyped number.
     */
    public static function isValid(string $phone, ?string $isoCode): bool
    {
        $number = self::parse($phone, $isoCode);

        return $number !== null
            && PhoneNumberUtil::getInstance()->isPossibleNumberWithReason($number) === ValidationResult::IS_POSSIBLE;
    }

    /**
     * The number in E.164 form, or null when it isn't a valid number.
     */
    public static function toE164(string $phone, ?string $isoCode): ?string
    {
        if (! self::isValid($phone, $isoCode)) {
            return null;
        }

        return PhoneNumberUtil::getInstance()->format(self::parse($phone, $isoCode), PhoneNumberFormat::E164);
    }

    /**
     * A stored number grouped for reading, e.g. "+44 7400 123456". Numbers the
     * library can't read are shown as stored.
     */
    public static function display(?string $phone): ?string
    {
        if (blank($phone)) {
            return null;
        }

        $number = self::parse($phone, null);

        return $number !== null ? PhoneNumberUtil::getInstance()->format($number, PhoneNumberFormat::INTERNATIONAL) : $phone;
    }

    private static function parse(string $phone, ?string $isoCode): ?PhoneNumber
    {
        $phone = trim($phone);

        // "00" is the international prefix in most countries: "00971…" means "+971…".
        if (str_starts_with($phone, '00')) {
            $phone = '+'.substr($phone, 2);
        }

        try {
            return PhoneNumberUtil::getInstance()->parse($phone, filled($isoCode) ? strtoupper($isoCode) : null);
        } catch (NumberParseException) {
            return null;
        }
    }
}
