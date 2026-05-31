<?php

use App\Http\Controllers\Public\BlogController;
use App\Http\Controllers\Public\CapabilityController;
use App\Http\Controllers\Public\CaseStudyController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/about', [PageController::class, 'about'])->name('about');

Route::get('/capabilities', [CapabilityController::class, 'index'])->name('capabilities.index');

Route::get('/case-studies', [CaseStudyController::class, 'index'])->name('case-studies.index');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

require __DIR__.'/admin.php';
