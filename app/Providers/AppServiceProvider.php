<?php

namespace App\Providers;

use App\Models\Capability;
use App\Models\Setting;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->composeHeaderNavigation();
        $this->applySmtpSettings();
    }

    /**
     * Override the mail configuration with the admin-managed SMTP settings.
     * Silently skipped when the database is unavailable (e.g. during install).
     */
    private function applySmtpSettings(): void
    {
        try {
            $smtp = Setting::where('group', 'smtp')->whereNotNull('value')->pluck('value', 'key');
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
     * Feed the public header's "Capabilities" mega-menu with the live, admin-managed
     * capabilities (rendered as headers, in their stored order) each followed by the
     * services attached to that capability, also in their stored order.
     */
    private function composeHeaderNavigation(): void
    {
        View::composer('components.public.header', function (\Illuminate\View\View $view): void {
            $navCapabilities = Capability::active()
                ->ordered()
                ->with(['services' => fn ($query) => $query->active()])
                ->get(['id', 'slug', 'title']);

            $view->with('navCapabilities', $navCapabilities);
        });
    }
}
