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
use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Admin\DownloadController;
use App\Http\Controllers\Admin\FaqCategoryController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\GlobalAdvantageController;
use App\Http\Controllers\Admin\GlossaryTermController;
use App\Http\Controllers\Admin\IndustryController;
use App\Http\Controllers\Admin\InsightController;
use App\Http\Controllers\Admin\JobApplicationController;
use App\Http\Controllers\Admin\JobApplicationExportController;
use App\Http\Controllers\Admin\JobApplicationNoteController;
use App\Http\Controllers\Admin\JobOpeningController;
use App\Http\Controllers\Admin\LandingPageController;
use App\Http\Controllers\Admin\LanguageController;
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
use App\Http\Controllers\Admin\TranslationController;
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
        Route::delete('leads', [LeadController::class, 'bulkDestroy'])->name('leads.bulk-destroy');
        Route::get('leads/{lead}', [LeadController::class, 'show'])->withTrashed()->name('leads.show');
        Route::patch('leads/{lead}', [LeadController::class, 'update'])->name('leads.update');
        Route::delete('leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');
        Route::patch('leads/{lead}/restore', [LeadController::class, 'restore'])->withTrashed()->name('leads.restore');
        Route::post('leads/{lead}/notes', [LeadNoteController::class, 'store'])->name('leads.notes.store');
    });

    // Careers (spec §26.8, §28.10): job openings, designations and candidate
    // applications. Reachable by HR, who has no CMS or Leads rights; candidate
    // data never appears in Leads (spec §27). Application actions are checked
    // by JobApplicationPolicy.
    Route::middleware(['auth', 'can:access-careers'])->group(function () {
        Route::get('job-applications/export', JobApplicationExportController::class)->name('job-applications.export');
        Route::get('job-applications', [JobApplicationController::class, 'index'])->name('job-applications.index');
        Route::get('job-applications/{jobApplication}', [JobApplicationController::class, 'show'])->name('job-applications.show');
        Route::patch('job-applications/{jobApplication}', [JobApplicationController::class, 'update'])->name('job-applications.update');
        Route::delete('job-applications/{jobApplication}', [JobApplicationController::class, 'destroy'])->name('job-applications.destroy');
        Route::patch('job-applications/{jobApplication}/archive', [JobApplicationController::class, 'archive'])->name('job-applications.archive');
        Route::patch('job-applications/{jobApplication}/unarchive', [JobApplicationController::class, 'unarchive'])->name('job-applications.unarchive');
        Route::get('job-applications/{jobApplication}/resume', [JobApplicationController::class, 'resume'])->name('job-applications.resume');
        Route::post('job-applications/{jobApplication}/notes', [JobApplicationNoteController::class, 'store'])->name('job-applications.notes.store');

        Route::resource('job-openings', JobOpeningController::class)->except(['show'])->parameters(['job-openings' => 'jobOpening']);
        Route::patch('designations/{designation}/toggle', [DesignationController::class, 'toggle'])->name('designations.toggle');
        Route::resource('designations', DesignationController::class)->except(['show']);

        Route::get('careers-content', [CareersContentController::class, 'edit'])->name('careers-content.edit');
        Route::put('careers-content', [CareersContentController::class, 'update'])->name('careers-content.update');
    });

    // Super Admin only: CAPTCHA keys (spec §28.2) and admin users.
    Route::middleware(['auth', 'can:manage-captcha'])->group(function () {
        Route::get('settings/captcha', [CaptchaSettingsController::class, 'edit'])->name('captcha.edit');
        Route::put('settings/captcha', [CaptchaSettingsController::class, 'update'])->name('captcha.update');
        Route::post('settings/captcha/test', [CaptchaSettingsController::class, 'test'])->name('captcha.test');
    });

    // Super Admin only: switching languages on, translated service pages and
    // language detection.
    Route::middleware(['auth', 'can:manage-languages'])->group(function () {
        Route::get('settings/languages', [LanguageController::class, 'edit'])->name('languages.edit');
        Route::put('settings/languages', [LanguageController::class, 'update'])->name('languages.update');
        Route::put('settings/languages/{language}', [LanguageController::class, 'toggle'])->name('languages.toggle');
    });

    Route::middleware(['auth', 'can:manage-users'])->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');

        Route::get('mail', [MailController::class, 'edit'])->name('mail.edit');
        Route::put('mail', [MailController::class, 'update'])->name('mail.update');
        Route::post('mail/test', [MailController::class, 'test'])->name('mail.test');

        Route::resource('capabilities', CapabilityController::class);
        Route::resource('services', ServiceController::class);
        Route::resource('landing-pages', LandingPageController::class)->except(['show']);
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
        Route::resource('case-studies', CaseStudyController::class);
        Route::resource('case-study-categories', CaseStudyCategoryController::class)->parameters(['case-study-categories' => 'caseStudyCategory']);
        Route::resource('testimonials', TestimonialController::class);
        Route::resource('brands', BrandController::class);
        Route::resource('why-choose-features', WhyChooseFeatureController::class);
        Route::resource('office-locations', OfficeLocationController::class);
        Route::resource('global-advantages', GlobalAdvantageController::class);
        Route::resource('process-steps', ProcessStepController::class);
        Route::resource('redirects', RedirectController::class);

        // Translations tab: check, export batches, import after review, glossary.
        Route::get('translations', [TranslationController::class, 'index'])->name('translations.index');
        Route::post('translations/export', [TranslationController::class, 'export'])->name('translations.export');
        Route::post('translations/import', [TranslationController::class, 'import'])->name('translations.import');
        Route::post('translations/import/confirm', [TranslationController::class, 'confirm'])->name('translations.confirm');
        Route::post('translations/glossary', [GlossaryTermController::class, 'store'])->name('translations.glossary.store');
        Route::delete('translations/glossary/{glossaryTerm}', [GlossaryTermController::class, 'destroy'])->name('translations.glossary.destroy');
    });

    require __DIR__.'/portal.php';
});
