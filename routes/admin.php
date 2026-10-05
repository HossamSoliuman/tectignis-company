<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CapabilityController;
use App\Http\Controllers\Admin\CaptchaSettingsController;
use App\Http\Controllers\Admin\CareersContentController;
use App\Http\Controllers\Admin\CaseStudyCategoryController;
use App\Http\Controllers\Admin\CaseStudyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DownloadController;
use App\Http\Controllers\Admin\FaqCategoryController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\GlobalAdvantageController;
use App\Http\Controllers\Admin\IndustryController;
use App\Http\Controllers\Admin\InsightController;
use App\Http\Controllers\Admin\JobOpeningController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\LeadExportController;
use App\Http\Controllers\Admin\LeadNoteController;
use App\Http\Controllers\Admin\MailController;
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
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WhyChooseFeatureController;
use App\Models\Lead;
use Illuminate\Support\Facades\Route;

Route::get('login', [LoginController::class, 'show'])->name('login');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [LoginController::class, 'show'])->name('login');
        Route::post('login', [LoginController::class, 'store'])->name('login.store');
    });

    // Shared by both doors: a portal-only employee still needs to sign out and
    // manage their own account without holding any CMS rights.
    Route::middleware('auth')->group(function () {
        Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

        Route::get('account', [AccountController::class, 'edit'])->name('account.edit');
        Route::put('account', [AccountController::class, 'update'])->name('account.update');
    });

    // Leads (spec §26.6–26.7): reachable by Sales and Read-only users who have
    // no CMS rights; individual actions are checked by LeadPolicy.
    Route::middleware(['auth', 'can:viewAny,'.Lead::class])->group(function () {
        Route::get('leads/export', LeadExportController::class)->name('leads.export');
        Route::get('leads', [LeadController::class, 'index'])->name('leads.index');
        Route::get('leads/{lead}', [LeadController::class, 'show'])->withTrashed()->name('leads.show');
        Route::patch('leads/{lead}', [LeadController::class, 'update'])->name('leads.update');
        Route::delete('leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');
        Route::patch('leads/{lead}/restore', [LeadController::class, 'restore'])->withTrashed()->name('leads.restore');
        Route::post('leads/{lead}/notes', [LeadNoteController::class, 'store'])->name('leads.notes.store');
    });

    // Super Admin only: CAPTCHA keys (spec §28.2) and admin users.
    Route::middleware(['auth', 'can:manage-captcha'])->group(function () {
        Route::get('settings/captcha', [CaptchaSettingsController::class, 'edit'])->name('captcha.edit');
        Route::put('settings/captcha', [CaptchaSettingsController::class, 'update'])->name('captcha.update');
        Route::post('settings/captcha/test', [CaptchaSettingsController::class, 'test'])->name('captcha.test');
    });

    Route::middleware(['auth', 'can:manage-users'])->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');

        Route::get('careers-content', [CareersContentController::class, 'edit'])->name('careers-content.edit');
        Route::put('careers-content', [CareersContentController::class, 'update'])->name('careers-content.update');

        Route::get('mail', [MailController::class, 'edit'])->name('mail.edit');
        Route::put('mail', [MailController::class, 'update'])->name('mail.update');
        Route::post('mail/test', [MailController::class, 'test'])->name('mail.test');

        Route::resource('capabilities', CapabilityController::class);
        Route::resource('services', ServiceController::class);
        Route::resource('solutions', SolutionController::class);
        Route::resource('industries', IndustryController::class);
        Route::resource('tech-stacks', TechStackController::class)->parameters(['tech-stacks' => 'techStack']);
        Route::resource('stats', StatController::class);
        Route::resource('pages', PageController::class);
        Route::resource('blog', BlogPostController::class);
        Route::resource('insights', InsightController::class);
        Route::resource('faq-categories', FaqCategoryController::class)->parameters(['faq-categories' => 'faqCategory']);
        Route::resource('faqs', FaqController::class);
        Route::resource('downloads', DownloadController::class);
        Route::resource('job-openings', JobOpeningController::class)->parameters(['job-openings' => 'jobOpening']);
        Route::resource('case-studies', CaseStudyController::class);
        Route::resource('case-study-categories', CaseStudyCategoryController::class)->parameters(['case-study-categories' => 'caseStudyCategory']);
        Route::resource('testimonials', TestimonialController::class);
        Route::resource('brands', BrandController::class);
        Route::resource('why-choose-features', WhyChooseFeatureController::class);
        Route::resource('office-locations', OfficeLocationController::class);
        Route::resource('global-advantages', GlobalAdvantageController::class);
        Route::resource('process-steps', ProcessStepController::class);
        Route::resource('redirects', RedirectController::class);
    });

    require __DIR__.'/portal.php';
});
