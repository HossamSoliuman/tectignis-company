<?php

use App\Http\Controllers\Public\BlogController;
use App\Http\Controllers\Public\CapabilityController;
use App\Http\Controllers\Public\CareersController;
use App\Http\Controllers\Public\CaseStudyController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\IndustryController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\ServiceController;
use App\Http\Controllers\Public\SolutionController;
use App\Http\Controllers\SitemapController;
use App\Models\Setting;
use Illuminate\Support\Facades\Route;

//test update

Route::get('/', HomeController::class)->name('home');

Route::get('/about', [PageController::class, 'about'])->name('about');

Route::get('/capabilities', [CapabilityController::class, 'index'])->name('capabilities.index');
Route::get('/capabilities/{slug}', [CapabilityController::class, 'show'])->name('capabilities.show');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/solutions', [SolutionController::class, 'index'])->name('solutions.index');
Route::get('/solutions/{slug}', [SolutionController::class, 'show'])->name('solutions.show');

Route::get('/industries', [IndustryController::class, 'index'])->name('industries.index');
Route::get('/industries/{slug}', [IndustryController::class, 'show'])->name('industries.show');

Route::get('/careers', [PageController::class, 'careers'])->name('careers');
Route::post('/careers', [CareersController::class, 'submit'])->name('careers.submit');

Route::get('/case-studies', [CaseStudyController::class, 'index'])->name('case-studies.index');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/technology-insights', [PageController::class, 'technologyInsights'])->name('technology-insights');
Route::get('/downloads', [PageController::class, 'downloads'])->name('downloads');
Route::get('/faqs', [PageController::class, 'faqs'])->name('faqs');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

Route::get('/legal/{slug}', [PageController::class, 'legal'])->name('legal.show');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/robots.txt', function () {
    $content = Setting::get('robots_txt', "User-agent: *\nAllow: /")."\nSitemap: ".url('/sitemap.xml');

    return response($content, 200)->header('Content-Type', 'text/plain');
})->name('robots');

require __DIR__.'/admin.php';

// Catch-all for admin-authored CMS pages. Registered LAST so all explicit routes win.
Route::get('/{page:slug}', [PageController::class, 'show'])->name('pages.show');
