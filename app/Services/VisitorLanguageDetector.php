<?php

namespace App\Services;

use App\Support\Countries;
use App\Support\Locales;
use Illuminate\Http\Request;

/**
 * The live language that suits a visitor best. A live language their browser
 * prefers over English wins, regional tags included (ar-EG is Arabic, es-419
 * Spanish). Otherwise their country decides (Egypt is Arabic, Mexico
 * Spanish, see config/locales.php), because most browsers in those markets
 * run in English whatever the visitor reads. English is the fallback.
 *
 * Callers can leave the country out: on a Google Ads landing page the ad
 * already chose the language, so only the browser may override it.
 */
class VisitorLanguageDetector
{
    public function detect(Request $request, bool $useCountry = true): string
    {
        $enabled = Locales::enabled();

        if (count($enabled) < 2) {
            return Locales::default();
        }

        $browserLanguage = $request->getPreferredLanguage($enabled);

        if ($browserLanguage !== null && ! Locales::isDefault($browserLanguage) && Locales::isEnabled($browserLanguage)) {
            return $browserLanguage;
        }

        if (! $useCountry) {
            return Locales::default();
        }

        $countryLanguage = $this->languageOfCountry(Countries::detect($request));

        if ($countryLanguage !== null && Locales::isEnabled($countryLanguage)) {
            return $countryLanguage;
        }

        return Locales::default();
    }

    /**
     * The supported language a country is listed under, or null when it isn't
     * listed.
     */
    public function languageOfCountry(?string $isoCode): ?string
    {
        if ($isoCode === null) {
            return null;
        }

        foreach (config('locales.countries') as $code => $isoCodes) {
            if (in_array(strtoupper($isoCode), $isoCodes, true)) {
                return $code;
            }
        }

        return null;
    }
}
