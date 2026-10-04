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
        return 'Tectignis helps businesses build, modernize and secure their technology through custom software, AI automation, cloud, cybersecurity and IT infrastructure solutions.';
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
        return 'Navi Mumbai, Maharashtra, India';
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
            ['url' => $this->setting('social_linkedin'), 'icon' => 'fab fa-linkedin-in', 'label' => 'Visit LinkedIn'],
            ['url' => $this->setting('social_facebook'), 'icon' => 'fab fa-facebook-f', 'label' => 'Visit Facebook'],
            ['url' => $this->setting('social_instagram'), 'icon' => 'fab fa-instagram', 'label' => 'Visit Instagram'],
            ['url' => $this->setting('social_twitter'), 'icon' => 'fab fa-twitter', 'label' => 'Visit X / Twitter'],
            ['url' => $this->whatsappUrl(), 'icon' => 'fab fa-whatsapp', 'label' => 'Chat on WhatsApp'],
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
        return [
            ['name' => 'USA', 'flag' => 'us', 'focus' => 'Software, SaaS, AI, cloud and cybersecurity', 'url' => null],
            ['name' => 'UAE', 'flag' => 'ae', 'focus' => 'Software, AI, cloud, cybersecurity and enterprise IT', 'url' => null],
            ['name' => 'UK', 'flag' => 'gb', 'focus' => 'Software, SaaS, AI, cloud and managed technology', 'url' => null],
            ['name' => 'Canada', 'flag' => 'ca', 'focus' => 'Software, SaaS, AI and cloud', 'url' => null],
            ['name' => 'Europe', 'flag' => 'eu', 'focus' => 'Software, AI, cloud and cybersecurity', 'url' => null],
            ['name' => 'India', 'flag' => 'in', 'focus' => 'Software, AI, cloud, cybersecurity and infrastructure', 'url' => null],
        ];
    }

    /**
     * The approved, active company statistics in display order.
     *
     * @return Collection<int, Stat>
     */
    public function stats(): Collection
    {
        return once(fn () => Cache::rememberForever('site.stats', fn () => Stat::active()->ordered()->get()));
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
