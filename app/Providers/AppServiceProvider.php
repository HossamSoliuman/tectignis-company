<?php

namespace App\Providers;

use App\Models\Service;
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
     * services grouped into the three design categories (matches the services index).
     */
    private function composeHeaderNavigation(): void
    {
        View::composer('components.public.header', function (\Illuminate\View\View $view): void {
            $services = Service::active()->ordered()->get(['slug', 'title', 'category']);

            $navServiceGroups = collect(Service::CATEGORY_LABELS)
                ->map(fn (string $label, string $key) => $services->where('category', $key)->values())
                ->filter->isNotEmpty();

            $view->with('navServiceGroups', $navServiceGroups);
        });
    }
}
