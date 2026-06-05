<?php

namespace App\Providers;

use App\Models\Capability;
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
