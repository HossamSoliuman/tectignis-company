<?php

namespace App\Support;

use App\Models\Setting;
use App\Models\Stat;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * The single source for global company facts (spec §10.1, §20): contact
 * details, registration numbers, social profiles, markets served and the
 * approved company statistics. Templates read from here instead of
 * hard-coding phone numbers or figures.
 */
class CompanyProfile
{
    /**
     * Priority international markets (spec §6.1), by flag code.
     *
     * @var list<string>
     */
    private const MARKETS = ['us', 'ae', 'gb', 'ca', 'eu', 'in'];

    public function name(): string
    {
        return $this->setting('site_name', 'Tectignis IT Solutions');
    }

    public function legalName(): string
    {
        return $this->setting('company_legal_name', 'Tectignis IT Solutions Pvt. Ltd.');
    }

    public function tagline(): string
    {
        return __('common.tagline');
    }

    public function phone(): string
    {
        return $this->setting('site_phone', '+91 9987805688');
    }

    public function phoneHref(): string
    {
        return 'tel:'.preg_replace('/[^+\d]/', '', $this->phone());
    }

    public function email(): string
    {
        return $this->setting('site_email', 'info@tectignis.in');
    }

    public function address(): string
    {
        return $this->setting('site_address', 'Navi Mumbai, Maharashtra, India');
    }

    public function shortLocation(): string
    {
        return __('common.location');
    }

    public function businessHours(): ?string
    {
        return $this->setting('business_hours');
    }

    public function gstin(): ?string
    {
        return $this->setting('company_gstin');
    }

    public function cin(): ?string
    {
        return $this->setting('company_cin');
    }

    public function whatsappUrl(): ?string
    {
        $number = preg_replace('/\D/', '', $this->setting('social_whatsapp', ''));

        return $number ? 'https://wa.me/'.$number : null;
    }

    public function logoUrl(): string
    {
        return Setting::imageUrl($this->setting('site_logo'), 'site_logo')
            ?? asset('assets/images/logo/Tectignis-IT-solution-logo.webp');
    }

    public function logoDarkUrl(): string
    {
        return Setting::imageUrl($this->setting('site_logo_dark'), 'site_logo_dark')
            ?? $this->logoUrl();
    }

    /**
     * Configured social profiles (WhatsApp included), skipping empty ones.
     *
     * @return list<array{url: string, icon: string, label: string}>
     */
    public function socials(): array
    {
        $socials = [
            ['url' => $this->setting('social_linkedin'), 'icon' => 'fab fa-linkedin-in', 'label' => __('common.socials.linkedin')],
            ['url' => $this->setting('social_facebook'), 'icon' => 'fab fa-facebook-f', 'label' => __('common.socials.facebook')],
            ['url' => $this->setting('social_instagram'), 'icon' => 'fab fa-instagram', 'label' => __('common.socials.instagram')],
            ['url' => $this->setting('social_twitter'), 'icon' => 'fab fa-twitter', 'label' => __('common.socials.twitter')],
            ['url' => $this->whatsappUrl(), 'icon' => 'fab fa-whatsapp', 'label' => __('common.socials.whatsapp')],
        ];

        return array_values(array_filter($socials, fn (array $social): bool => filled($social['url'])));
    }

    /**
     * Public profile URLs for the Organization schema `sameAs`.
     *
     * @return list<string>
     */
    public function profileUrls(): array
    {
        return array_values(array_filter([
            $this->setting('social_linkedin'),
            $this->setting('social_facebook'),
            $this->setting('social_instagram'),
            $this->setting('social_twitter'),
        ]));
    }

    /**
     * Priority international markets (spec §6.1). `url` stays null until the
     * market landing pages ship in Phase 6.
     *
     * @return list<array{name: string, flag: string, focus: string, url: string|null}>
     */
    public function markets(): array
    {
        return array_map(fn (string $flag): array => [
            'name' => __("common.markets.{$flag}.name"),
            'flag' => $flag,
            'focus' => __("common.markets.{$flag}.focus"),
            'url' => null,
        ], self::MARKETS);
    }

    /**
     * The priority markets as upper-case codes, for structured data.
     *
     * @return list<string>
     */
    public function areaServed(): array
    {
        return array_map(strtoupper(...), self::MARKETS);
    }

    /**
     * The approved, active company statistics in display order.
     *
     * @return Collection<int, Stat>
     */
    public function stats(): Collection
    {
        $cacheKey = Locales::cacheKey('site.stats');

        return once(fn () => Cache::rememberForever($cacheKey, fn () => Stat::active()->ordered()->get()));
    }

    /**
     * A single approved statistic by key (e.g. "projects", "countries").
     */
    public function stat(string $key): ?Stat
    {
        return $this->stats()->firstWhere('key', $key);
    }

    /**
     * The value of a statistic, or $default when it is missing or inactive.
     */
    public function statValue(string $key, ?string $default = null): ?string
    {
        return $this->stat($key)?->value ?? $default;
    }

    private function setting(string $key, ?string $default = null): ?string
    {
        $value = Setting::get($key);

        return filled($value) ? $value : $default;
    }
}
