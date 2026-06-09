<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CapabilityController;
use App\Http\Controllers\Admin\CaseStudyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GlobalAdvantageController;
use App\Http\Controllers\Admin\IndustryController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\OfficeLocationController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProcessStepController;
use App\Http\Controllers\Admin\RedirectController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SolutionController;
use App\Http\Controllers\Admin\StatController;
use App\Http\Controllers\Admin\TechStackController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\WhyChooseFeatureController;
use Illuminate\Support\Facades\Route;

Route::get('login', [LoginController::class, 'show'])->name('login');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [LoginController::class, 'show'])->name('login');
        Route::post('login', [LoginController::class, 'store'])->name('login.store');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');

        Route::resource('capabilities', CapabilityController::class);
        Route::resource('services', ServiceController::class);
        Route::resource('solutions', SolutionController::class);
        Route::resource('industries', IndustryController::class);
        Route::resource('tech-stacks', TechStackController::class)->parameters(['tech-stacks' => 'techStack']);
        Route::resource('stats', StatController::class);
        Route::resource('pages', PageController::class);
        Route::resource('blog', BlogPostController::class);
        Route::resource('case-studies', CaseStudyController::class);
        Route::resource('testimonials', TestimonialController::class);
        Route::resource('brands', BrandController::class);
        Route::resource('why-choose-features', WhyChooseFeatureController::class);
        Route::resource('office-locations', OfficeLocationController::class);
        Route::resource('global-advantages', GlobalAdvantageController::class);
        Route::resource('process-steps', ProcessStepController::class);
        Route::resource('leads', LeadController::class)->only(['index', 'show', 'destroy']);
        Route::resource('redirects', RedirectController::class);
    });
});
