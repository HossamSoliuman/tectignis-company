<?php

namespace App\Providers;

use App\Enums\Pillar;
use App\Models\Capability;
use App\Models\Industry;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use App\Support\CompanyProfile;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CompanyProfile::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->defineAdminGates();
        $this->shareCompanyProfile();
        $this->composeHeaderNavigation();
        $this->composeFooter();
        $this->applySmtpSettings();
    }

    /**
     * Super-Admin-only areas: CAPTCHA keys (spec §28.2) and admin users.
     */
    private function defineAdminGates(): void
    {
        Gate::define('manage-captcha', fn (User $user): bool => $user->isSuperAdmin());
        Gate::define('manage-users', fn (User $user): bool => $user->isSuperAdmin());
    }

    /**
     * Override the mail configuration with the admin-managed SMTP settings.
     * Silently skipped when the database is unavailable (e.g. during install).
     */
    private function applySmtpSettings(): void
    {
        try {
            $smtp = Setting::values();
        } catch (QueryException) {
            return;
        }

        if (! $smtp->get('smtp_host')) {
            return;
        }

        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host' => $smtp->get('smtp_host'),
            'mail.mailers.smtp.port' => (int) $smtp->get('smtp_port', 587),
            'mail.mailers.smtp.username' => $smtp->get('smtp_username'),
            'mail.mailers.smtp.password' => $smtp->get('smtp_password'),
            'mail.mailers.smtp.scheme' => $smtp->get('smtp_encryption') === 'ssl' ? 'smtps' : 'smtp',
        ]);

        if ($smtp->get('smtp_from_address')) {
            config(['mail.from.address' => $smtp->get('smtp_from_address')]);
        }

        if ($smtp->get('smtp_from_name')) {
            config(['mail.from.name' => $smtp->get('smtp_from_name')]);
        }
    }

    /**
     * Expose the single company-facts source to every public template.
     */
    private function shareCompanyProfile(): void
    {
        View::composer(['layouts.public', 'public.*', 'components.public.*', 'components.seo.*'], function (\Illuminate\View\View $view): void {
            $view->with('company', $this->app->make(CompanyProfile::class));
        });
    }

    /**
     * Feed the public header with the four business pillars (spec §3), each holding
     * the live, admin-managed services of the capabilities mapped onto it, plus the
     * active industries.
     *
     * Only capabilities flagged `show_in_menu` contribute services, so the admin can
     * keep a capability live on the site while removing it from the mega-menu.
     */
    private function composeHeaderNavigation(): void
    {
        View::composer('components.public.header', function (\Illuminate\View\View $view): void {
            $nav = Cache::rememberForever('site.nav', fn (): array => [
                'pillars' => $this->groupCapabilitiesByPillar(),
                'industries' => Industry::active()->ordered()->get(['id', 'slug', 'name']),
            ]);

            $view->with('navPillars', $nav['pillars']);
            $view->with('navIndustries', $nav['industries']);
        });
    }

    /**
     * Feed the footer's Industries column.
     */
    private function composeFooter(): void
    {
        View::composer('components.public.footer', function (\Illuminate\View\View $view): void {
            $view->with('footerIndustries', Cache::rememberForever(
                'site.footer',
                fn () => Industry::active()->ordered()->limit(6)->get(['id', 'slug', 'name']),
            ));
        });
    }

    /**
     * @return Collection<int, array{pillar: Pillar, services: Collection<int, Service>}>
     */
    private function groupCapabilitiesByPillar(): Collection
    {
        $capabilities = Capability::active()
            ->where('show_in_menu', true)
            ->ordered()
            ->with(['services' => fn ($query) => $query->active()])
            ->get(['id', 'slug', 'title', 'category']);

        return collect(Pillar::cases())->map(fn (Pillar $pillar): array => [
            'pillar' => $pillar,
            'services' => $capabilities
                ->filter(fn (Capability $capability): bool => Pillar::forCapabilityCategory($capability->category) === $pillar)
                ->flatMap(fn (Capability $capability) => $capability->services)
                ->values(),
        ]);
    }
}
